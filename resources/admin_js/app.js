import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import App from './App.vue'
import router from './router/index.js'
import { Loading } from 'element-ui'
import store from './store'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(ElementUI)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/**
 * axios 前后拦截器
 */
// 请求拦截器
let loading
let showLoading
let needLoadingRequestCount = 0
axios.interceptors.request.use(
    config => {

        // 显示载入动画
        config.dontShowLoading === true ? showLoading = false : showLoading = true;
        if (showLoading) {
            if (needLoadingRequestCount === 0) {
                loading = Loading.service({
                    lock: true,
                    background: 'rgba(239, 239, 239, 0.5)'
                })
            }
            needLoadingRequestCount++
        }

        // 如果是 refresh token 请求, 则使用 refresh_token 作为 auth 头
        if (config.url == '/api/v0/admin/refresh-token') {
            const refreshToken = localStorage.getItem('refresh_token');
            refreshToken && (config.headers.Authorization = 'Bearer ' + refreshToken);
            return config;
        }

        // 每次发送请求之前判断vuex中是否存在token
        // 如果存在，则统一在http请求的header都加上token，这样后台根据token判断你的登录情况
        // 即使本地存在token，也有可能token是过期的，所以在响应拦截器中要对返回状态进行判断
        const accessToken = localStorage.getItem('access_token');
        accessToken && (config.headers.Authorization = 'Bearer ' + accessToken);
        return config;
    },
    error => {
        return Promise.error(error);
    }
);

// 响应拦截器
axios.interceptors.response.use(
    response => {
        // 隐藏载入动画
        needLoadingRequestCount--
        if (needLoadingRequestCount === 0) {
            if (showLoading) loading.close();
        }


        if (response.status === 201) {
            app.$message({
                message: '写入成功',
                type: 'success'
            });
        }
        if (response.status === 204) {
            app.$message({
                message: '删除成功',
                type: 'success'
            });
        }

        // 如果返回的状态码为200，说明接口请求成功，可以正常拿到数据
        // 否则的话抛出错误
        let code = response.status.toString();
        if (code.charAt(0) == '2') {
            return Promise.resolve(response);
        } else {
            return Promise.reject(response);
        }
    },
    // 服务器状态码不是2开头的的情况
    // 这里可以跟你们的后台开发人员协商好统一的错误状态码
    // 然后根据返回的状态码进行一些操作，例如登录过期提示，错误提示等等
    // 下面列举几个常见的操作，其他需求可自行扩展
    error => {
        needLoadingRequestCount--
        if (needLoadingRequestCount === 0) {
            if (showLoading) loading.close();
        }

        if (error.response.status) {
            switch (error.response.status) {

                case 401:
                    let response = error.response;

                    // 如果请求是 Refresh-Token 请求, 则即便返回 401 也不会再次发送 refresh token 请求
                    if (response.config.url == '/api/v0/admin/refresh-token') {
                        if (error.response.status == 401) {
                            app.$message({
                                type: 'error',
                                message: '登录超时, 请重新登录',
                            })
                            localStorage.removeItem('access_token');
                            localStorage.removeItem('refresh_token');
                            app.$router.push('/login')
                        }
                        break;
                    }

                    // 尝试使用 refresh token 刷新 aceess token
                    let originalRequest = error.config
                    return axios.get('/api/v0/admin/refresh-token')
                        .then((response) => {
                            // 获取新的 token 并再次发送失败的请求
                            localStorage.setItem('access_token', response.data.access_token);
                            return axios(originalRequest);
                        })
                        .catch((error) => {
                            // refresh 遇到非 401 错误, 交给外层 switch 处理
                        })
                    break;

                case 403:
                    app.$message({
                        type: 'error',
                        message: '403 权限不足',
                    })
                    break;

                case 404:
                    app.$message({
                        type: 'error',
                        message: '404 网络请求不存在',
                    })
                    break;

                case 422:
                    var htmlMessage = ''
                    Object.keys(error.response.data.errors).forEach((index) => {
                        htmlMessage += '<li style="margin: 5px 0">' + error.response.data.errors[index] + '</li>'
                    })
                    app.$message({
                        dangerouslyUseHTMLString: true,
                        type: 'error',
                        message: '<ul>' + htmlMessage + '</ul>',
                    })
                    break;

                // Too Many Requests
                case 429:
                    var htmlMessage = ''
                    Object.keys(error.response.data.errors).forEach((index) => {
                        htmlMessage += '<li style="margin: 5px 0">' + error.response.data.errors[index] + '</li>'
                    })
                    app.$message({
                        dangerouslyUseHTMLString: true,
                        type: 'error',
                        message: '<ul>' + htmlMessage + '</ul>',
                    })
                    break;

                // 其他错误，直接抛出错误提示
                default:
                    console.error(error.response)
                    app.$message({
                        type: 'error',
                        message: error.response.status + ' ' + error.response.data.message,
                    })
                    break;
            }
            return Promise.reject(error.response);
        }
    }
);

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App),
});

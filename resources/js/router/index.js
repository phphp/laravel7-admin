import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

const router = new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "index",
            path: '/',
            component: resolve => void (require(['../components/admin/MainComponent.vue'], resolve)),
            redirect: '/index', // 默认显示 index 子组件
            children: [
                {
                    path: '/index',
                    component: resolve => void (require(['../components/admin/IndexComponent.vue'], resolve)),
                },
                {
                    path: '/sub',
                    component: resolve => void (require(['../components/admin/SubComponent.vue'], resolve)),
                }
            ]
        },

        {
            name: "login",
            path: '/login',
            component: resolve => void (require(['../components/LoginComponent.vue'], resolve)),
        },
    ]
})

// 导航守卫
router.beforeEach((to, from, next) => {
    // next() 表示放行/跳转
    if (to.path === '/login') return next()

    const token = window.sessionStorage.getItem('token')
    if (!token) return next('/login')

    next()
})

export default router
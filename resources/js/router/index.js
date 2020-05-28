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
                    meta: { title: '控制台' }
                },
                {
                    path: '/admins',
                    component: resolve => void (require(['../components/admin/AdminsComponent.vue'], resolve)),
                    meta: { title: '管理员列表' }
                },
                {
                    path: '/admins/create',
                    component: resolve => void (require(['../components/admin/AdminsCreateComponent.vue'], resolve)),
                    meta: { title: '添加管理员' }
                },
                {
                    path: '/admins/edit/:id',
                    component: resolve => void (require(['../components/admin/AdminsEditComponent.vue'], resolve)),
                    meta: { title: '修改管理员' }
                },

                {
                    path: '/sub',
                    component: resolve => void (require(['../components/admin/SubComponent.vue'], resolve)),
                    meta: { title: 'sub' }
                },
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

    const accessToken = localStorage.getItem('access_token')
    if (!accessToken) return next('/login')

    next()
})

export default router
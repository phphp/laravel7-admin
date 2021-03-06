import Vue from 'vue'
import store from '../store'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

const router = new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "login",
            path: '/login',
            component: resolve => void (require(['../components/LoginComponent.vue'], resolve)),
        },
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
                    path: '/permissions',
                    // name: 'PermissionsComponent',
                    component: resolve => void (require(['../components/admin/PermissionsComponent.vue'], resolve)),
                    meta: { title: '权限列表' }
                },
                {
                    path: '/permissions/create',
                    component: resolve => void (require(['../components/admin/PermissionsCreateComponent.vue'], resolve)),
                    meta: { title: '添加权限' }
                },
                {
                    path: '/permissions/edit/:id',
                    component: resolve => void (require(['../components/admin/PermissionsEditComponent.vue'], resolve)),
                    meta: { title: '修改权限' }
                },

                {
                    path: '/roles',
                    component: resolve => void (require(['../components/admin/RolesComponent.vue'], resolve)),
                    meta: { title: '角色列表' }
                },
                {
                    path: '/roles/create',
                    component: resolve => void (require(['../components/admin/RolesCreateComponent.vue'], resolve)),
                    meta: { title: '添加角色' }
                },
                {
                    path: '/roles/edit/:id',
                    component: resolve => void (require(['../components/admin/RolesEditComponent.vue'], resolve)),
                    meta: { title: '修改角色' }
                },

                {
                    path: '/edit-profile',
                    component: resolve => void (require(['../components/admin/EditProfileComponent.vue'], resolve)),
                    meta: { title: '修改资料' }
                },
                {
                    path: '*',
                    component: resolve => void (require(['../components/admin/NotFoundComponent.vue'], resolve)),
                    meta: { title: '404' }
                },
            ]
        },

    ]
})

// 导航守卫
router.beforeEach((to, from, next) => {
    if (to.meta.title) {
        document.title = to.meta.title;
    } else {
        document.title = '后台';
    }

    // 记录标签
    store.commit('pushTabs', { path: to.fullPath, title: document.title })

    // 跳转登录页, 清空 store 数据
    if (to.path === '/login') {
        store.commit('setTabs', [])
        return next()
    }

    const accessToken = localStorage.getItem('access_token')
    if (!accessToken) return next('/login')

    next()
})

export default router
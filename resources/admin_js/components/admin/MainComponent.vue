<template>
    <el-container class="full-container">

        <el-aside :width="isCollapse ? '65px' : '220px'">
            <el-menu
                :collapse="isCollapse"
                :collapse-transition="false"
                router
                :default-active="$route.path"
                unique-opened
                >

                <el-menu-item index="/index">
                    <i class="el-icon-menu"></i>
                    <span slot="title">控制台</span>
                </el-menu-item>

                <el-submenu index="4">
                    <template slot="title">
                        <i class="el-icon-s-custom"></i>
                        <span slot="title">管理员</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item index="/admins">列表</el-menu-item>
                        <el-menu-item index="/admins/create">添加</el-menu-item>
                    </el-menu-item-group>
                </el-submenu>

                <el-submenu index="5">
                    <template slot="title">
                        <i class="el-icon-s-management"></i>
                        <span slot="title">角色设置</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item index="/roles">列表</el-menu-item>
                        <el-menu-item index="/roles/create">添加</el-menu-item>
                    </el-menu-item-group>
                </el-submenu>

                <el-submenu index="6">
                    <template slot="title">
                        <i class="el-icon-collection"></i>
                        <span slot="title">权限设置</span>
                    </template>
                    <el-menu-item-group>
                        <el-menu-item index="/permissions">列表</el-menu-item>
                        <el-menu-item index="/permissions/create">添加</el-menu-item>
                    </el-menu-item-group>
                </el-submenu>

                <el-menu-item @click="changeCollapse" index="#">
                    <i v-if="!isCollapse" class="el-icon-s-fold"></i>
                    <i v-if="isCollapse" class="el-icon-s-unfold"></i>
                </el-menu-item>

            </el-menu>
        </el-aside>

        <el-container>

            <el-header class="page-header">
                <el-row :gutter="20" type="flex" align="middle">
                    <el-col :span="14">
                        <el-page-header @back="goBack" :content="$route.meta.title"></el-page-header>
                    </el-col>
                    <el-col :span="10" style="text-align: right;">
                        <el-dropdown @command="handleCommand">
                            <span>
                                {{$store.getters.greeting}}
                                <i class="el-icon-setting" style="margin-right: 15px"></i>
                            </span>
                            <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="editProfile">修改资料</el-dropdown-item>
                            <el-dropdown-item command="logout" divided>退出登录</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </el-col>
                </el-row>
            </el-header>

            <div class="tabs-container">
                <el-tag
                    class="tabs"
                    :key="key"
                    v-for="(tab, key) in $store.state.tabs"
                    :type="$route.meta.title == tab.title ? '' : 'info'"
                    closable
                    :disable-transitions="true"
                    @click="handleClickTab(tab)"
                    @close="handleCloseTab(tab)">
                    {{tab.title}}
                </el-tag>
            </div>

            <el-main>
                <keep-alive>
                    <router-view></router-view>
                </keep-alive>
            </el-main>
        </el-container>

    </el-container>



</template>

<script>
    export default {
        data() {
            return {
                isCollapse: false,
            }
        },
        mounted() {
            // 判断是否有 vuex user 来判断是否需要查询数据
            if (this.$store.state.currentUser.name == '游客') {
                this.fetchProfile();
            }

            this.$store.commit('setActivatedRequest', localStorage.getItem('activated_request'))
            this.isCollapse = JSON.parse(localStorage.getItem('nav_is_collapse'))
        },
        methods: {
            changeCollapse() {
                this.isCollapse = !this.isCollapse
                localStorage.setItem('nav_is_collapse', this.isCollapse);
            },
            goBack() {
                window.history.back();
            },
            handleCommand(command) {
                eval('this.'+command+'()');
            },
            logout() {
                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                this.$router.push('/login')
                this.$message('退出成功');
            },
            editProfile() {
                this.$router.push('/edit-profile')
            },
            fetchProfile() {
                axios.get(`/api/v0/admin/profile`)
                    .then( (response) => {
                        this.$store.commit('setCurrentUser', response.data.data)
                    })
                    .catch( (error) => {

                    });
            },
            handleClickTab(tab) {
                this.$router.push({ path: tab.path });
            },
            handleCloseTab(tab) {
                if (this.$store.state.tabs.length == 1 && this.$route.path == '/index') return;

                let tabs = this.$store.state.tabs;
                let closeNo = tabs.indexOf(tab);
                tabs.splice(closeNo, 1);
                let newTabs = tabs;
                this.$store.commit('setTabs', newTabs)

                if ( tab.title == this.$route.meta.title ) {
                    if (newTabs.length == 0)
                        return this.$router.push({ path: '/index' })
                    if (newTabs[closeNo+1] != undefined)
                        return this.$router.push({ path: newTabs[closeNo+1].path })
                    this.$router.push({ path: newTabs[closeNo-1].path })
                }
            }
        }
    }
</script>

<style scoped>
    .full-container {
        height: 100%;
    }
    .el-aside {
        height: 100vh;
        border-right: solid 1px #e6e6e6;

    }
    .el-menu {
        border-right: none;
    }
    .page-header .el-row {
        height: 60px;
    }
    .el-dropdown {
        cursor: pointer;
    }
    .tabs-container {
        border-bottom: 1px solid #EFEFEF;
        padding: 0 15px;
    }
    .tabs {
        border-bottom: none;
        border-radius: 4px 4px 0 0;
    }
</style>

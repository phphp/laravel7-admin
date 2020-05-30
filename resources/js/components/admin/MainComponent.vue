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

                <el-submenu index="1">
                    <template slot="title">
                        <i class="el-icon-location"></i>
                        <span slot="title">导航一</span>
                    </template>
                    <el-menu-item-group>
                        <span slot="title">分组一</span>
                        <el-menu-item index="1-1">选项1</el-menu-item>
                        <el-menu-item index="1-2">选项2</el-menu-item>
                    </el-menu-item-group>
                    <el-menu-item-group title="分组2">
                        <el-menu-item index="1-3">选项3</el-menu-item>
                    </el-menu-item-group>
                    <el-submenu index="1-4">
                        <span slot="title">选项4</span>
                        <el-menu-item index="1-4-1">选项1</el-menu-item>
                    </el-submenu>
                </el-submenu>

                <el-menu-item index="2">
                    <i class="el-icon-menu"></i>
                    <span slot="title">导航二</span>
                </el-menu-item>

                <el-menu-item index="3" disabled>
                    <i class="el-icon-document"></i>
                    <span slot="title">导航三</span>
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

                <el-menu-item @click="changeCollapse">
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
                        <el-dropdown>
                            <i class="el-icon-setting" style="margin-right: 15px"></i>
                            <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item>查看</el-dropdown-item>
                            <el-dropdown-item>新增</el-dropdown-item>
                            <el-dropdown-item>删除</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </el-col>
                </el-row>
            </el-header>

            <el-main>
                <router-view></router-view>
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
            this.isCollapse = JSON.parse(localStorage.getItem('nav_is_collapse'))
        },
        methods: {
            changeCollapse() {
                this.isCollapse = !this.isCollapse
                localStorage.setItem('nav_is_collapse', this.isCollapse);
            },
            goBack() {
                window.history.back();
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

</style>

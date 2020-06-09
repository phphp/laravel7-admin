<template>
    <div class="container">

        <el-form ref="form" :model="form" :rules="rules" label-width="80px" status-icon>
            <el-form-item label="名称" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="看守器" prop="guard_name">
                <el-input v-model="form.guard_name"></el-input>
            </el-form-item>

            <el-form-item label="选择权限" prop="permissions">
                <el-select v-model="form.permissions" multiple placeholder="请选择权限" style="width: 100%">
                    <el-option
                        v-for="item in permissions"
                        :key="item.id"
                        :label="item.name + ' (' + item.guard_name + ')'"
                        :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="handleSubmit">立即创建</el-button>
            </el-form-item>
        </el-form>


    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                },
                permissions: {},
                rules: {
                    name: [
                        { required: true, message: '请输入名称', trigger: 'blur' },
                        { min: 1, max: 255, message: '长度在 1 到 255 个字符', trigger: 'blur' },
                    ],
                    guard_name: [
                        { required: true, message: '请输入看守器名称', trigger: 'blur' },
                        { min: 1, max: 255, message: '长度在 1 到 255 个字符', trigger: 'blur' },
                    ],
                },
            }
        },
        mounted() {
            this.fetchPermissions();
        },
        methods: {
            handleSubmit() {
                this.$refs.form.validate(async valid => {
                    if (valid) {
                        axios.post(`/api/v0/admin/roles`, this.form)
                            .then( (response) => {
                                this.$router.push('/roles')
                            })
                            .catch( (error) => {

                            });
                    }
                });
            },

            fetchPermissions(pageNum=1) {
                this.currentPage = pageNum
                axios.get(`/api/v0/admin/permissions?page=${pageNum}&per_page=100`)
                    .then( (response) => {
                        this.permissions = response.data.data.data
                        this.total = response.data.data.total
                        this.pageSize = parseInt(response.data.data.per_page)
                    })
                    .catch( (error) => {

                    });
            },

        }
    }
</script>

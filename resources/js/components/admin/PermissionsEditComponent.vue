<template>
    <div class="container">

        <el-form ref="form" :model="form" :rules="rules" label-width="80px" status-icon>

            <el-form-item label="名称" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="看守器" prop="guard_name">
                <el-input v-model="form.guard_name"></el-input>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="handleSubmit">修改</el-button>
            </el-form-item>
        </el-form>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    role: 2 // 默认选择角色
                },
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
                roles: [],
            }
        },
        mounted() {
            this.fetchPermission(this.$route.params.id)
        },
        methods: {
            handleSubmit() {
                this.$refs.form.validate(async valid => {
                    if (valid) {
                        axios.put(`/api/v0/admin/permissions/${this.$route.params.id}`, this.form)
                            .then( (response) => {

                            })
                            .catch( (error) => {

                            });
                    }
                });
            },

            // 查询权限
            fetchPermission(id) {
                axios.get(`/api/v0/admin/permissions/${id}`)
                    .then( (response) => {
                        this.form = response.data.data
                    })
                    .catch( (error) => {

                    });
            },
        }
    }
</script>

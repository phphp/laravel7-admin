<template>
    <div class="container">

        <el-form ref="form" :model="form" :rules="rules" label-width="80px" status-icon>
            <el-form-item label="名称" prop="name">
                <el-input v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="邮箱" prop="email">
                <el-input v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
                <el-input type="password" v-model="form.password" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="confirmPassword">
                <el-input type="password" v-model="form.confirmPassword" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item label="选择角色" prop="role">
                <el-select v-model="form.role">
                    <el-option v-for="role in roles" :key="role.id" :label="role.name" :value="role.id"></el-option>
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
            var validatePassword = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请输入密码'));
                } else {
                    if (this.form.confirmPassword !== '') {
                        this.$refs.form.validateField('confirmPassword');
                    }
                    callback();
                }
            };
            var confirmPassword = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('请再次输入密码'));
                } else if (value !== this.form.password) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                form: {
                    role: 2 // 默认选择角色
                },
                rules: {
                    name: [
                        { required: true, message: '请输入名称', trigger: 'blur' },
                        { min: 1, max: 255, message: '长度在 1 到 255 个字符', trigger: 'blur' },
                    ],
                    email: [
                        { required: true, message: '请输入邮箱地址', trigger: 'blur' },
                        { type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] },
                        { min: 1, max: 255, message: '长度在 1 到 255 个字符', trigger: 'blur' },
                    ],
                    password: [
                        { required: true, message: '请输入密码', trigger: 'blur' },
                        { validator: validatePassword, trigger: 'blur' },
                        { min: 6, max: 64, message: '长度在 6 到 64 个字符', trigger: 'blur' },
                    ],
                    confirmPassword: [
                        { required: true, message: '请重复输入密码', trigger: 'blur' },
                        { validator: confirmPassword, trigger: 'blur' },
                        { min: 6, max: 64, message: '长度在 6 到 64 个字符', trigger: 'blur' },
                    ],
                    role: [
                        { required: true, message: '请选择角色', trigger: 'blur' },
                    ],

                },
                roles: [],
            }
        },
        mounted() {
            this.fetchRoles();
        },
        methods: {
            handleSubmit() {
                this.$refs.form.validate(async valid => {
                    if (valid) {
                        axios.post(`/api/v0/admin/admins`, this.form)
                            .then( (response) => {
                                this.$router.push('/admins')
                            })
                            .catch( (error) => {

                            });
                    }
                });
            },

            // 查询所有角色
            fetchRoles() {
                axios.get(`/api/v0/admin/roles`)
                    .then( (response) => {
                        this.roles = response.data.data.data
                    })
                    .catch( (error) => {

                    });
            },
        }
    }
</script>

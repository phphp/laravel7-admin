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

            <el-form-item>
                <el-button type="primary" @click="handleSubmit">修改</el-button>
            </el-form-item>
        </el-form>

        <el-divider content-position="center">使用设置</el-divider>

        <el-form label-width="150px" label-position="right">
            <el-form-item label="切换标签时刷新数据">
                <el-tooltip class="item" effect="dark" content="即时显示最新的数据, 但每次载入都会查询最新的数据, 适合于数据竞争激烈的环境" placement="left">
                    <el-switch v-model="activatedRequest"></el-switch>
                </el-tooltip>
            </el-form-item>
        </el-form>


    </div>
</template>

<script>
    export default {
        data() {
            var validatePassword = (rule, value, callback) => {
                if (this.form.confirmPassword !== '') {
                    this.$refs.form.validateField('confirmPassword');
                }
                callback();
            };
            var confirmPassword = (rule, value, callback) => {
                 if (value !== this.form.password && (this.form.password != '' && value != '')) {
                    callback(new Error('两次输入密码不一致!'));
                } else {
                    callback();
                }
            };
            return {
                form: {
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
                        { validator: validatePassword, trigger: 'blur' },
                        { min: 6, max: 64, message: '长度在 6 到 64 个字符', trigger: 'blur' },
                    ],
                    confirmPassword: [
                        { validator: confirmPassword, trigger: 'blur' },
                        { min: 6, max: 64, message: '长度在 6 到 64 个字符', trigger: 'blur' },
                    ],
                },
                activatedRequest: true,
            }
        },
        mounted() {
            this.fetchProfile()
        },
        watch: {
            activatedRequest() {
                this.$store.commit('setActivatedRequest', this.activatedRequest)
                localStorage.setItem('activated_request', this.activatedRequest);
            }
        },
        methods: {
            handleSubmit() {
                this.$refs.form.validate(async valid => {
                    if (valid) {
                        axios.post(`/api/v0/admin/profile/`, this.form)
                            .then( (response) => {
                                // this.$router.push('/admins')
                            })
                            .catch( (error) => {

                            });
                    }
                });
            },

            // 查询个人信息
            fetchProfile() {
                axios.get(`/api/v0/admin/profile`)
                    .then( (response) => {
                        this.form = response.data.data
                    })
                    .catch( (error) => {

                    });
            },
        }
    }
</script>

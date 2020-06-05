<template>
    <div class="container">

        <div class="form-container">
            <el-form label-width="" :model="formData" :rules="rules" ref="formData">
                <el-form-item label="" prop="name">
                    <el-input v-model="formData.name" placeholder="用户">
                        <i slot="prefix" class="el-input__icon el-icon-user-solid"></i>
                    </el-input>
                </el-form-item>

                <el-form-item label="" prop="password">
                    <el-input placeholder="密码" v-model="formData.password" show-password>
                        <i slot="prefix" class="el-input__icon el-icon-unlock"></i>
                    </el-input>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" plain style="float: right" @click="login('formData')">登录</el-button>
                </el-form-item>
            </el-form>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                formData: {
                    name: '',
                    password: '',
                },
                rules: {
                    name: [
                        { required: true, message: '请输入用户名', trigger: 'blur' },
                        { min: 3, max: 20, message: '长度在 3 到 20 个字符', trigger: 'blur' },
                    ],
                    password: [
                        { required: true, message: '请输入密码', trigger: 'blur' },
                        { min: 6, max: 64, message: '长度在 6 到 64 个字符', trigger: 'blur' },
                    ],
                },
            }
        },
        mounted() {

        },
        methods: {
            login(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {

                        axios.post('/api/v0/admin/login', this.formData)
                            .then( (response) => {
                                localStorage.setItem('access_token', response.data.access_token);
                                localStorage.setItem('refresh_token', response.data.refresh_token);
                                this.$router.push('/index')
                            })
                            .catch( (error) => {

                            });

                    } else {
                        console.log('表单前端验证失败');
                        return false;
                    }
                });
            }
        },
    }
</script>

<style scoped>

.container {
    height: 100vh;
    background-color: #EFEFEF;
    background-image: url('https://cn.bing.com/th?id=OHR.LastJedi_ZH-CN8789881870_1920x1080.jpg&rf=LaDigue_1920x1080.jpg&pid=hp');
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}
.form-container {
    width: 300px;
}
</style>

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
        },
        methods: {
            handleSubmit() {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        axios.post(`/api/v0/admin/permissions`, this.form)
                            .then( (response) => {
                                this.form = {}
                                this.$store.dispatch('removeTab', { path: this.$router.history.current.fullPath, title: document.title })
                                .then((resolve)=>{
                                    this.$router.push('/permissions')
                                });
                            })
                            .catch( (error) => {

                            });
                    }
                });
            },

        }
    }
</script>

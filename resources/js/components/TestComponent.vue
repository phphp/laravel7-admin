<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                        testttttt
                </div>
            </div>

            <el-input v-model="test" placeholder='123'></el-input>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                test: '321'
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

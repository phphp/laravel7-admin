<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                        index
                </div>
            </div>
    <button @click="cb">click</button>
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
            axios.get('/api/v0/admin/test')
                .then( (response) => {
                    console.log(response.data)
                })
                .catch( (error) => {

                });
        },
        methods: {
            cb() {
                axios.get('/api/v0/admin/test', {'dontShowLoading': true})
                    .then( (response) => {
                        console.log(response.data)
                    })
                    .catch( (error) => {

                    });
            }
        }
    }
</script>

<template>
    <div class="container">
        <el-table
            stripe
            border
            :data="permissions"
            style="width: 100%">
            <el-table-column
                prop="id"
                label="ID"
                width="60">
            </el-table-column>
            <el-table-column
                prop="name"
                label="名称">
            </el-table-column>
            <el-table-column
                prop="guard_name"
                label="看守器"
                width="100">
            </el-table-column>
            <el-table-column
                prop="created_at"
                label="添加日期"
                width="180">
            </el-table-column>
            <el-table-column
                prop="updated_at"
                label="修改日期"
                width="180">
            </el-table-column>
            <el-table-column label="操作" width="125">
                <template v-slot="scope">
                    <el-button
                        type="primary"
                        icon="el-icon-edit"
                        @click="showEditPermission(scope.row.id)"
                        size="mini"
                    ></el-button>
                    <el-button
                        type="danger"
                        @click="deletePermission(scope.row.id)"
                        icon="el-icon-delete"
                        size="mini"
                    ></el-button>
                </template>
            </el-table-column>

        </el-table>

        <div class="pagination-container">
            <el-pagination
                background
                layout="prev, pager, next, sizes, total, jumper"
                :page-sizes="[15, 30, 50, 100]"
                :total="total"
                :page-size="pageSize"
                :current-page="currentPage"
                @current-change="handleCurrentChange"
                @size-change="handleSizeChange"
                >
            </el-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                permissions: [],
                total: null, // 分页总数, 使用 null 而不使用 0 避免载入时无法相应链接参数的变化
                pageSize: 15, // 分页尺寸
                currentPage: 1, // 当前页码
                isCreated: true, // 判断是否是第一次创建
            }
        },
        created() {
            // 设置分页信息
            if (this.$route.query.page !== undefined) this.currentPage = parseInt(this.$route.query.page);
            if (this.$route.query.per_page !== undefined) this.pageSize = parseInt(this.$route.query.per_page);
            this.fetchPermission()
        },
        activated() {
            if (!this.isCreated && this.$store.state.activatedRequest) {
                this.fetchPermission()
            } else {
                this.isCreated = false
            }
        },
        methods: {
            fetchPermission() {
                let query = {
                    'page': this.currentPage,
                    'per_page': this.pageSize
                }
                this.$router.push( { path: this.$route.path, query: query } ).catch(error=>{}) ;

                axios.get(`/api/v0/admin/permissions?page=${this.currentPage}&per_page=${this.pageSize}`)
                    .then( (response) => {
                        this.permissions = response.data.data.data
                        this.total = response.data.data.total
                        this.pageSize = parseInt(response.data.data.per_page)
                    })
                    .catch( (error) => {
                        console.log(error)
                    });
            },

            // 监听修改页码
            handleCurrentChange(pageNum) {
                this.currentPage = pageNum
                this.fetchPermission();
            },

            // 监听修改分页尺寸
            handleSizeChange(pageSize) {
                this.pageSize = pageSize;
                this.fetchPermission();
            },

            showEditPermission(id) {
                this.$router.push('/permissions/edit/' + id)
            },
            deletePermission(id) {
                this.$confirm('确认删除', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.delete(`/api/v0/admin/permissions/${id}`)
                    .then( (response) => {
                        // 删除数组中相应 id
                        for ( var key in this.permissions ) {
                            if ( this.permissions[key].id == id ) {
                                this.permissions.splice(key, 1);
                                break
                            }
                        }
                    })
                    .catch( (error) => {

                    });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
        }
    }
</script>

<style scoped>
.pagination-container {
    margin-top: 20px;
}
.el-pagination__jump,
.el-pagination__total {
    vertical-align: baseline !important;
}
</style>

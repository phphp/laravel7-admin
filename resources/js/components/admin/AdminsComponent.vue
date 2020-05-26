<template>
    <div class="container">
        <el-table
            stripe
            border
            :data="admins"
            style="width: 100%">
            <el-table-column
                prop="id"
                label="ID"
                width="60">
            </el-table-column>
            <el-table-column
                prop="name"
                label="名称"
                width="100">
            </el-table-column>
            <el-table-column
                prop="email"
                label="邮箱">
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
            <el-table-column
                prop="active"
                label="启用"
                width="60">
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
                admins: [],
                total: 0, // 分页总数
                pageSize: 15, // 分页尺寸
                currentPage: 1, // 当前页码
            }
        },
        mounted() {
            // 查询所有管理员
            this.fetchAdmins();
        },
        methods: {
            fetchAdmins(pageNum=1) {
                this.currentPage = pageNum
                axios.get(`/api/v0/admin/admins?page=${pageNum}&per_page=${this.pageSize}`)
                    .then( (response) => {
                        this.admins = response.data.data.data
                        this.total = response.data.data.total
                        this.pageSize = parseInt(response.data.data.per_page)
                    })
                    .catch( (error) => {

                    });
            },

            // 监听修改页码
            handleCurrentChange(pageNum) {
                this.fetchAdmins(pageNum);
            },

            // 监听修改分页尺寸
            handleSizeChange(pageSize) {
                // this.fetchAdmins(pageNum);
                this.pageSize = pageSize;
                this.fetchAdmins();
            },
        },
    }
</script>

<style scope>
.pagination-container {
    margin-top: 20px;
}
.el-pagination__jump,
.el-pagination__total {
    vertical-align: baseline !important;
}
</style>

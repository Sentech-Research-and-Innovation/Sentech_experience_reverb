<template>
    <div class="shadow-border col-12 py-2">
        <el-table
            v-loading="loading"
            :element-loading-svg="svg"
            class="custom-loading-svg"
            element-loading-svg-view-box="-10, -10, 50, 50"
            :data="rows.data"
            style="width: 100%"
            height="600"
        >
            <el-table-column
                prop="SiteName"
                label="Site Name"
                width="150"
                fixed="left"
            />
            <el-table-column prop="Classification_x" label="Class" width="80" />
            <el-table-column
                prop="DeviceName"
                label="Device Name"
                width="250"
                fixed="left"
            />
            <el-table-column prop="item_id" label="Sensor ID" width="300" />
            <el-table-column prop="date" label="Date" width="100" />
            <el-table-column prop="date" label="Day" width="100" />
            <el-table-column label="In Alarm" width="100">
                <template #default="scope">
                    <div
                        v-if="scope.row.alarm == 0"
                        style="background-color: rgb(232, 9, 9); color: #ffff"
                    >
                        Alarm
                    </div>
                    <div
                        v-else-if="scope.row.alarm == 1"
                        style="background-color: rgb(65, 232, 9); color: #ffff"
                    >
                        Normal
                    </div>
                    <div v-else style="background-color: #ffc107; color: #ffff">
                        Pre-Alarm
                    </div>
                </template>
            </el-table-column>
            <el-table-column
                prop="target_value"
                label="Predicted Value"
                width="105"
            />
            <el-table-column
                prop="lowerPreAlarmTsh"
                label="lowerPreTsh"
                width="105"
            />
            <el-table-column
                prop="lowerAlarmTsh"
                label="lowerTsh"
                width="105"
            />
            <el-table-column
                prop="upperPreAlarmTsh"
                label="upperPreTsh"
                width="105"
            />
            <el-table-column
                prop="upperAlarmTsh"
                label="upperTsh"
                width="105"
            />
        </el-table>

        <el-pagination
            :current-page="pagination.currentPage"
            :page-size="pagination.perPage"
            :total="pagination.total"
            @current-change="handlePageChange"
            layout="prev, pager, next"
            style="margin-top: 20px; text-align: center"
        />
    </div>
</template>

<script>
import { defineComponent, ref, computed, watch } from "vue";
import axios from "axios";
import { predictionsFilterStore } from "../../../../stores/predictionFiltersDetailed";

export default defineComponent({
    setup() {
        const rows = ref({ data: [], total: 0 });
        const loading = ref(false);
        const svg = `
      <path class="path" d="
        M 30 15
        L 28 17
        M 25.61 25.61
        A 15 15, 0, 0, 1, 15 30
        A 15 15, 0, 1, 1, 27.99 7.5
        L 15 15
      " style="stroke-width: 4px; fill: rgba(0, 0, 0, 0)"/>`;

        const filterStore = predictionsFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);

        const pagination = ref({
            currentPage: 1,
            perPage: 100,
            total: 0,
        });

        const getPredictions = async (page = 1) => {
            loading.value = true;
            try {
                const response = await axios.post(
                    "/admin/predictive-maintenance/predictions/filtered",
                    {
                        params: searchFilter.value,
                        page: page,
                        per_page: pagination.value.perPage,
                    }
                );
                rows.value = response.data;
                pagination.value.total = response.data.total; // Update total count
                pagination.value.currentPage = response.data.current_page; // Update current page
            } catch (error) {
                console.error("Error fetching predictions:", error);
            } finally {
                loading.value = false;
            }
        };

        const handlePageChange = (page) => {
            getPredictions(page);
        };

        watch(
            searchFilter,
            () => {
                getPredictions();
            },
            { immediate: true }
        );

        return {
            rows,
            loading,
            svg,
            pagination,
            handlePageChange,
        };
    },
});
</script>

<style>
.el-table .el-table__cell {
    padding: 0px !important;
}

.el-table {
    width: 80%;
}

.el-table .cell {
    padding: 1px !important;
    text-align: center;
}

.example-showcase .el-loading-mask {
    z-index: 9;
}
</style>

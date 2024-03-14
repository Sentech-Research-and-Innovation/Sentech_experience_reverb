<template>
    <div>
        <nav
            class="navbar navbar-expand-lg navbar-light py-3 mb-2 justify-content-between"
        >
            <ul class="navbar-nav mr-auto pt-1">
                <li
                    class="nav-item mr-5"
                    :class="{
                        active:
                            $page.url === '/admin/predictive-maintenance/index',
                    }"
                >
                    <a
                        class="nav-link py-0"
                        href="/admin/predictive-maintenance/index"
                        >Master View</a
                    >
                </li>

                <li
                    class="nav-item mr-5"
                    :class="{
                        active:
                            $page.url ===
                            '/admin/predictive-maintenance/predictions/detailed-view',
                    }"
                >
                    <Link
                        class="nav-link"
                        href="/admin/predictive-maintenance/predictions/detailed-view"
                        >Detailed View</Link
                    >
                </li>
            </ul>
            <ul>
                <div v-if="!loading">
                    <el-button
                        :icon="Collection"
                        class="fs-4 reportsLink pt-4"
                        @click="centerDialogVisible = true"
                    />
                    <!-- @click="printReport" -->
                </div>
                <pulse-loader :loading="loading" :color="color"></pulse-loader>
            </ul>
        </nav>

        <el-dialog
            v-model="centerDialogVisible"
            width="30%"
            left
            :show-close="false"
        >
            <div class="col-12">
                <h2>Export reports</h2>
            </div>
            <div class="col-12 pt-2">
                <span class="fs-6">
                    Select the type of document you wan to export
                </span>
            </div>
            <div class="col-12 pt-3">
                <div class="d-flex justify-content-between">
                    <div class="col-6 ml-0 pl-0">
                        <div
                            class="col-12 rounded py-5 text-center"
                            :style="{
                                border: isActive('pdf')
                                    ? '1px solid #409eff'
                                    : '1px solid #c0c4cc',
                                fontSize: '40px',
                                color: isActive('pdf') ? '#409eff' : '#c0c4cc',
                                cursor: 'pointer',
                            }"
                            @click="setActive('pdf')"
                        >
                            <i class="far fa-file-pdf"></i>
                            PDF
                        </div>
                    </div>
                    <div class="col-6 mx-0 px-0">
                        <div
                            class="col-12 rounded py-5 text-center"
                            :style="{
                                border: isActive('csv')
                                    ? '1px solid #409eff'
                                    : '1px solid #c0c4cc',
                                fontSize: '40px',
                                color: isActive('csv') ? '#409eff' : '#c0c4cc',
                                cursor: 'pointer',
                            }"
                            @click="setActive('csv')"
                        >
                            <i class="far fa-file-excel"></i>
                            CSV
                        </div>
                    </div>
                </div>
            </div>
            <template #footer>
                <span class="dialog-footer">
                    <el-button @click="centerDialogVisible = false"
                        >Cancel</el-button
                    >
                    <el-button
                        type="primary"
                        @click="printReport"
                        :disabled="!activeType"
                    >
                        Download {{ activeType }}
                    </el-button>
                </span>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { Collection } from "@element-plus/icons-vue";
import { predictionsFilterStore } from "../../stores/predictionsFilter";
import PulseLoader from "vue-spinner/src/PulseLoader.vue";

export default defineComponent({
    components: { Link, PulseLoader },

    setup() {
        const filterStore = predictionsFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);

        const loading = ref(false);
        const search = ref({
            searchFilter,
        });

        const centerDialogVisible = ref(false);

        const printReport = async () => {
            centerDialogVisible.value = false;
            loading.value = true;

            await axios
                .post("/admin/reports/predictive-maintenance", {
                    searchData: search.value,
                    reportType: activeType.value,
                })
                .catch(() => {
                    loading.value = false;
                });
        };

        const activeType = ref(null);

        const isActive = (type) => {
            return activeType.value === type;
        };

        const setActive = (type) => {
            activeType.value = type;
        };

        const color = ref("#144f9f");

        return {
            Collection,
            printReport,
            loading,
            centerDialogVisible,
            isActive,
            setActive,
            activeType,
            color,
        };
    },
});
</script>

<style scoped>
.nav-link {
    color: #a8abb2;
    font-size: 14px;
    padding: 0px !important;
    margin: 0px !important;
}

.active {
    border-bottom: 3px solid #144f9f !important;
    color: #144f9f !important;
}
.reportsLink {
    color: #144f9f;
    cursor: pointer;
    border: none !important;
    background: none !important;
}
</style>

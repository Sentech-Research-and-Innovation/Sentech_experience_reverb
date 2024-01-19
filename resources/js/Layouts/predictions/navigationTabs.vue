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
                <el-tooltip
                    content="Print a report"
                    placement="top"
                    effect="light"
                >
                    <el-button
                        :icon="Collection"
                        @click="printReport"
                        class="fs-4 reportsLink pt-4"
                    />
                </el-tooltip>
            </ul>
        </nav>
    </div>
</template>

<script>
import { defineComponent, ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { Collection } from "@element-plus/icons-vue";
import { predictionsFilterStore } from "../../stores/predictionsFilter";

export default defineComponent({
    components: { Link },

    setup() {
        const filterStore = predictionsFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            searchFilter,
        });

        const printReport = async () => {
            await axios.post("/admin/reports/predictive-maintenance", {
                searchData: search.value,
            });
        };
        return {
            Collection,
            printReport,
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
    color: #144f9f !important;
    cursor: pointer;
    border: none !important;
    background: none !important;
}
</style>

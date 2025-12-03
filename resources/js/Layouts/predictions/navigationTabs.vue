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
                        v-if="!loading"
                        circle
                        plain
                        :icon="Download"
                        class="reportsLink pt-2"
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
import { Download } from "@element-plus/icons-vue";
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
            try {
                const response = await axios.post(
                    "/admin/reports/predictive-maintenance",
                    {
                        searchData: search.value,
                        reportType: activeType.value,
                    },
                    {
                        responseType: "blob",
                    }
                );

                loading.value = false;

                console.log("Response received:", response);

                const contentDisposition =
                    response.headers["content-disposition"];
                console.log("Content-Disposition header:", contentDisposition);

                if (contentDisposition) {
                    const filenameRegex =
                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(contentDisposition);
                    let filename = `Sentiment analysis report.${
                        activeType.value === "pdf" ? "pdf" : "csv"
                    }`;
                    if (matches != null && matches[1]) {
                        filename = matches[1].replace(/['"]/g, "");
                    }

                    console.log("Filename determined:", filename);

                    const mimeType =
                        activeType.value === "pdf"
                            ? "application/pdf"
                            : "text/csv";
                    const blob = new Blob([response.data], { type: mimeType });
                    console.log("Blob created with MIME type:", mimeType);

                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement("a");
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }
            } catch (error) {
                console.error("Error generating or downloading report:", error);
                loading.value = false;

            // Extract the actual error message from the Blob
                if (error.response && error.response.data instanceof Blob) {
                    try {
                        const errorText = await error.response.data.text();
                        console.error("Server error details:", errorText);

                        // Try to parse as JSON
                        try {
                            const errorJson = JSON.parse(errorText);
                            console.error("Parsed error JSON:", errorJson);
                            //alert(`Server Error: ${errorJson.message || JSON.stringify(errorJson)}`);
                        } catch (e) {
                            // If not JSON, just show the text
                            console.error("Error is plain text:", errorText);
                            //alert(`Server Error: ${errorText}`);
                        }
                    } catch (blobError) {
                        console.error("Could not read error blob:", blobError);
                    }
                }
            }
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
            Download,
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
    font-size: 20px !important;
}
</style>

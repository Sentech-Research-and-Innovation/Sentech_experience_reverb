<template>
    <div class="col-12 px-0 nav-wrap d-none d-lg-block d-md-none d-xl-block">
        <div class="row">
            <div class="col-5">
                <nav
                    class="navbar navbar-expand-lg navbar-light1 py-3 mb-0"
                    style="border: none"
                >
                    <div class="navbar-collapse">
                        <ul class="navbar-nav mr-auto pt-2" id="nav">
                            <li class="mr-2">
                                <el-button
                                    v-if="!loading"
                                    plain
                                    style="
                                        font-size: 18px;
                                        height: 0px;
                                        width: 0px;
                                        border: none;
                                        background: none !important;
                                    "
                                    :icon="QuestionFilled"
                                    @click="open = true"
                                />
                            </li>
                            <li
                                class="nav-item mr-3 filter-items"
                                :class="{
                                    active:
                                        $page.url === '/admin/sentiments/all',
                                }"
                            >
                                <a
                                    class="nav-a py-0"
                                    href="/admin/sentiments/all"
                                    >All</a
                                >
                            </li>
                            <li
                                class="nav-item mr-3"
                                :class="{
                                    active:
                                        $page.url ===
                                        '/admin/sentiments/overview',
                                }"
                            >
                                <a
                                    class="nav-a"
                                    href="/admin/sentiments/overview"
                                    >Overview</a
                                >
                            </li>
                            <li
                                class="nav-item mr-3"
                                :class="{
                                    active:
                                        $page.url ===
                                        '/admin/sentiments/timelines',
                                }"
                            >
                                <a
                                    class="nav-a"
                                    href="/admin/sentiments/timelines"
                                    >Time lines</a
                                >
                            </li>
                            <li
                                class="nav-item mr-3"
                                :class="{
                                    active:
                                        $page.url ===
                                        '/admin/sentiments/trends',
                                }"
                            >
                                <a class="nav-a" href="/admin/sentiments/trends"
                                    >Trends</a
                                >
                            </li>
                            <!-- <li
                                class="nav-item mr-0"
                                :class="{
                                    active:
                                        $page.url ===
                                        '/admin/sentiments/others',
                                }"
                            >
                                <a class="nav-a" href="/admin/sentiments/others"
                                    >Others</a
                                >
                            </li>-->
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-7 my-2 my-lg-0 text-end pt-3 px-4 left-nav">
                <div class="row">
                    <div class="col-2 mr-0 px-1">
                        <SelectDroptownVue
                            id="sent-type"
                            :filters="options"
                            :options="sentimentType"
                            v-model="sentimentModel"
                        />
                    </div>
                    <div class="col-4 mx-0 px-0" id="daterange">
                        <el-date-picker
                            v-model="inputdate"
                            type="daterange"
                            range-separator="To"
                            start-placeholder="Start date"
                            end-placeholder="End date"
                        />
                    </div>

                    <div class="col-3 text-start px-1" id="keywords">
                        <el-input
                            v-model="keywords"
                            class="w-100 search-input"
                            placeholder="Serach by name"
                            :prefix-icon="Search"
                        />
                    </div>
                    <div class="col-1 mx-0 px-0 text-start" id="search-botton">
                        <button
                            style="border: none !important"
                            class="btn btn-sm btn-primary btn-search"
                            @click="changePropValue"
                        >
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <div class="col-2">
                        <el-button
                            id="reports"
                            v-if="!loading"
                            circle
                            plain
                            :icon="Download"
                            class="reportsLink pt-2 mr-3"
                            @click="centerDialogVisible = true"
                        />

                        <pulse-loader
                            :loading="loading"
                            :color="color"
                        ></pulse-loader>

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
                                    Select the type of document you wan to
                                    export
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
                                                color: isActive('pdf')
                                                    ? '#409eff'
                                                    : '#c0c4cc',
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
                                                color: isActive('csv')
                                                    ? '#409eff'
                                                    : '#c0c4cc',
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
                                    <el-button
                                        @click="centerDialogVisible = false"
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
                </div>
            </div>
        </div>
        <el-tour v-model="open" class="mx-5">
            <el-tour-step
                target="#nav"
                title="Navigation"
                description="Filter by navigating to diffrent pages."
            />
            <el-tour-step
                target="#sent-type"
                title="Sentiment type filter"
                description="Filter results by sentiment type"
            />
            <el-tour-step
                target="#daterange"
                title="Data range filter"
                description="Filter results by Date range"
            />

            <el-tour-step
                target="#keywords"
                title="Keywords filter"
                description="Filter results by keywords"
            />

            <el-tour-step
                target="#search-botton"
                title="Search button"
                description="Click button get filtered results"
            />

            <el-tour-step
                target="#reports"
                title="Export repots"
                description="Export reports PDF/CSV"
            />
        </el-tour>
    </div>

    <div
        class="col-12 d-xl-none d-xxl-none d-md-block d-lg-none py-2 px-3 mb-3 mobile-nav"
    >
        <vue-horizontal class="px-0 pt-4">
            <div
                class="col-4 text-center mr-4"
                :class="{
                    active: $page.url === '/admin/sentiments/all',
                }"
            >
                <a class="nav-a" href="/admin/sentiments/all">All</a>
            </div>
            <div
                class="col-4 text-center mr-4"
                :class="{
                    active: $page.url === '/admin/sentiments/overview',
                }"
            >
                <a class="nav-a" href="/admin/sentiments/overview">Overview</a>
            </div>

            <div
                class="col-4 text-center mr-4"
                :class="{
                    active: $page.url === '/admin/sentiments/timelines',
                }"
            >
                <a class="nav-a" href="/admin/sentiments/timelines"
                    >Time lines</a
                >
            </div>
            <div
                class="col-4 text-center mr-4"
                :class="{
                    active: $page.url === '/admin/sentiments/trends',
                }"
            >
                <a class="nav-a" href="/admin/sentiments/trends">Trends</a>
            </div>
            <div
                class="col-4 text-center mr-4"
                :class="{
                    active: $page.url === '/admin/sentiments/others',
                }"
            >
                <a class="nav-a text-end" href="/admin/sentiments/others"
                    >Others</a
                >
            </div>

            <!-- @click="printReport" -->
        </vue-horizontal>
        <div class="row pt-3">
            <div class="col-7 pr-0 pb-2">
                <VueDatePicker
                    v-model="inputdate"
                    :enable-time-picker="false"
                    dark
                    range
                ></VueDatePicker>
            </div>

            <div class="col-4 px-1">
                <el-input
                    v-model="keywords"
                    class="w-100 search-input"
                    placeholder="Serach by name"
                    :prefix-icon="Search"
                />
            </div>
            <div class="col-1 text-start px-0">
                <button
                    style="border: none !important; height: 32px"
                    class="btn btn-sm btn-primary btn-search"
                    @click="changePropValue"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

import { defineComponent, onMounted, ref } from "vue";

import {VueDatePicker} from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import { useFilterStore } from "../../stores/filter";
import VueHorizontal from "vue-horizontal";

import { Download, QuestionFilled } from "@element-plus/icons-vue";

import SelectDroptownVue from "../../Components/SelectDroptown.vue";

import PulseLoader from "vue-spinner/src/PulseLoader.vue";

export default defineComponent({
    name: "navigation",
    components: {
        Link,
        VueDatePicker,
        VueHorizontal,
        SelectDroptownVue,
        PulseLoader,
    },

    setup() {
        const open = ref(false);
        const filterStore = useFilterStore();

        const inputdate = ref(filterStore.date);
        const keywords = ref(filterStore.keywords);
        const sentimentModel = ref([]);
        //
        const options = ref(["POSITIVE", "NEUTRAL", "NEGATIVE"]);

        const sentimentType = ref(filterStore.sentimentTypes);
        const centerDialogVisible = ref(false);

        const activeType = ref(null);

        const isActive = (type) => {
            return activeType.value === type;
        };

        const setActive = (type) => {
            activeType.value = type;
        };
        const loading = ref(false);

        const printReport = async () => {
        centerDialogVisible.value = false;
        loading.value = true;

        try {
            const response = await axios.post(
                "/admin/reports/sentiments",
                {
                    searchFilter: {
                        date: inputdate.value,
                        keywords: keywords.value,
                        sentimentTypes: sentimentType.value,
                    },
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
            } else {
                console.error("Content-Disposition header is missing");
            }
        } catch (error) {
            loading.value = false;
            console.error("Error occurred while printing report:", error);

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

        const changePropValue = () => {
            filterStore.date = inputdate.value;
            filterStore.keywords = keywords.value;
            filterStore.sentimentTypes = sentimentModel.value;
        };

        return {
            keywords,
            inputdate,
            changePropValue,
            filterStore,
            options,
            sentimentType,
            sentimentModel,
            Download,
            centerDialogVisible,
            setActive,
            isActive,
            activeType,
            printReport,
            loading,
            open,
            QuestionFilled,
        };
    },
});
</script>

<style>
.el-date-editor.el-input,
.el-date-editor.el-input__wrapper {
    --el-date-editor-width: 100% !important;
}
</style>
<style scoped>
.keyword-input {
    height: 32.5px !important;
    border: 1px solid #dddddd !important;
    color: #606266 !important;
    font-weight: 100;
    border-radius: 4px;
}
.btn-primary {
    background-color: #ebedf0;
    color: #000;
    border: none;
    height: 36px;
    font-weight: bold;
    border: 1px solid #dddddd !important;
}

.nav-a {
    color: #a8abb2;
    font-size: 14px;
    padding: 0px !important;
    margin: 0px !important;
}

.active {
    border-bottom: 3px solid #144f9f !important;
    color: #144f9f !important;
}

.dp__theme_dark {
    --dp-background-color: #ebedf0;
    --dp-text-color: #000;

    --dp-border-color: #dddddd;
    --dp-menu-border-color: #dddddd;
    --dp-icon-color: #000;

    font-size: 5px !important;
}

.btn-search {
    background-color: #144f9f !important;
    color: #ffff;
    height: 32.5px;
}
</style>

<style>
.search-btn {
    background-color: #144f9f !important;
    border: none !important;
}
.dp__input {
    --dp-input-padding: 4px 0px 2px 1px !important;
}
</style>

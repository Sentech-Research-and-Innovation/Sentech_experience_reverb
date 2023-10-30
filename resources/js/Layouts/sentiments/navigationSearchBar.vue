<template>
    <nav
        class="navbar navbar-expand-lg navbar-light py-3 mb-0 d-none d-lg-block d-xl-block"
    >
        <div class="navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto pt-2">
                <li
                    class="nav-item mr-3 filter-items"
                    :class="{
                        active: $page.url === '/admin/sentiments/all',
                    }"
                >
                    <a class="nav-link py-0" href="/admin/sentiments/all"
                        >All</a
                    >
                </li>
                <li
                    class="nav-item mr-3"
                    :class="{
                        active: $page.url === '/admin/sentiments/overview',
                    }"
                >
                    <Link class="nav-link" href="/admin/sentiments/overview"
                        >Overview</Link
                    >
                </li>
                <li
                    class="nav-item mr-3"
                    :class="{
                        active: $page.url === '/admin/sentiments/timelines',
                    }"
                >
                    <Link class="nav-link" href="/admin/sentiments/timelines"
                        >Time lines</Link
                    >
                </li>
                <li
                    class="nav-item mr-3"
                    :class="{
                        active: $page.url === '/admin/sentiments/trends',
                    }"
                >
                    <a class="nav-link" href="/admin/sentiments/trends"
                        >Trends</a
                    >
                </li>
                <li
                    class="nav-item mr-0"
                    :class="{
                        active: $page.url === '/admin/sentiments/others',
                    }"
                >
                    <a class="nav-link" href="/admin/sentiments/others"
                        >Others</a
                    >
                </li>
            </ul>
            <div class="form-inline my-2 my-lg-0 justify-content-end text-end">
                <div class="col-3 mr-0 px-1">
                    <SelectDroptownVue
                        :filters="options"
                        :options="sentimentType"
                        v-model="sentimentModel"
                    />
                </div>
                <div class="col-4 mx-0 px-0">
                    <el-date-picker
                        v-model="inputdate"
                        type="daterange"
                        range-separator="To"
                        start-placeholder="Start date"
                        end-placeholder="End date"
                    />
                </div>

                <div class="col-3 text-end mx-1 px-0">
                    <input
                        type="text"
                        v-model="keywords"
                        class="form-control keyword-input"
                    />
                </div>
                <div class="col-1 mx-0">
                    <button
                        class="btn btn-sm btn-primary btn-search"
                        @click="changePropValue"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <div
        class="col-12 d-xl-none d-xxl-block d-lg-none py-0 px-3 mb-3 mobile-nav"
    >
        <vue-horizontal class="px-5 pt-3">
            <div
                class="col-2"
                :class="{
                    active: $page.url === '/admin/sentiments/all',
                }"
            >
                <a class="nav-link" href="/admin/sentiments/all">All</a>
            </div>
            <div
                class="col-4"
                :class="{
                    active: $page.url === '/admin/sentiments/overview',
                }"
            >
                <Link class="nav-link" href="/admin/sentiments/overview"
                    >Overview</Link
                >
            </div>

            <div
                class="col-5 text-start"
                :class="{
                    active: $page.url === '/admin/sentiments/timelines',
                }"
            >
                <Link class="nav-link" href="/admin/sentiments/timelines"
                    >Time lines</Link
                >
            </div>
            <div
                class="col-4 text-start"
                :class="{
                    active: $page.url === '/admin/sentiments/trends',
                }"
            >
                <a class="nav-link" href="/admin/sentiments/trends">Trends</a>
            </div>
            <div
                class="col-4 text-end"
                :class="{
                    active: $page.url === '/admin/sentiments/others',
                }"
            >
                <a class="nav-link text-end" href="/admin/sentiments/others"
                    >Others</a
                >
            </div>
            <div class="col-8 pr-0 mx-0 pb-2">
                <VueDatePicker
                    v-model="inputdate"
                    :enable-time-picker="false"
                    dark
                    range
                ></VueDatePicker>
            </div>

            <div class="col-10 d-flex justify-content-start text-start">
                <input
                    type="text"
                    v-model="keywords"
                    class="form-control keyword-input"
                />
            </div>
            <div class="col-2 mx-0 d-flex justify-content-end">
                <button
                    class="btn btn-sm btn-primary search-btn"
                    @click="changePropValue"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </vue-horizontal>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

import { defineComponent, onMounted, ref } from "vue";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import { useFilterStore } from "../../stores/filter";
import VueHorizontal from "vue-horizontal";

import SelectDroptownVue from "../../Components/SelectDroptown.vue";
export default defineComponent({
    name: "navigation",
    components: { Link, VueDatePicker, VueHorizontal, SelectDroptownVue },

    setup() {
        const filterStore = useFilterStore();

        const inputdate = ref(filterStore.date);
        const keywords = ref(filterStore.keywords);
        const sentimentModel = ref([]);
        //
        const options = ref(["neutral", "positive", "negative"]);

        const sentimentType = ref(filterStore.sentimentTypes);

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

.dp__theme_dark {
    --dp-background-color: #ebedf0;
    --dp-text-color: #000;

    --dp-border-color: #dddddd;
    --dp-menu-border-color: #dddddd;
    --dp-icon-color: #000;

    font-size: 5px !important;
}

.search-btn {
    background-color: #144f9f !important;
    color: #ffff;
    height: 32.5px;
}
</style>

<style>
.dp__input {
    --dp-input-padding: 4px 0px 2px 1px !important;
}
</style>

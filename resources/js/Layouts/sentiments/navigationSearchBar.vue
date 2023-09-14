<template>
    <nav
        class="navbar navbar-expand-lg navbar-light py-3 mb-4"
        style="border-bottom: 1px solid #c7cdd2; background-color: #ffffff"
    >
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarText"
            aria-controls="navbarText"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto pt-2">
                <li
                    class="nav-item mr-5"
                    :class="{
                        active: $page.url === '/admin/sentiments/all',
                    }"
                >
                    <a class="nav-link py-0" href="/admin/sentiments/all"
                        >All</a
                    >
                </li>
                <li
                    class="nav-item mr-5"
                    :class="{
                        active: $page.url === '/admin/sentiments/overview',
                    }"
                >
                    <Link class="nav-link" href="/admin/sentiments/overview"
                        >Overview</Link
                    >
                </li>
                <li
                    class="nav-item mr-5"
                    :class="{
                        active: $page.url === '/admin/sentiments/timelines',
                    }"
                >
                    <Link class="nav-link" href="/admin/sentiments/timelines"
                        >Time lines</Link
                    >
                </li>
                <li
                    class="nav-item mr-5"
                    :class="{
                        active: $page.url === '/admin/sentiments/trends',
                    }"
                >
                    <a class="nav-link" href="/admin/sentiments/trends"
                        >Trends</a
                    >
                </li>
                <li
                    class="nav-item mr-3"
                    :class="{
                        active: $page.url === '/admin/sentiments/others',
                    }"
                >
                    <a class="nav-link" href="/admin/sentiments/others"
                        >Others</a
                    >
                </li>
                <li class="nav-item mx-0"></li>
            </ul>
            <div class="form-inline my-2 my-lg-0">
                <div class="col-6 mx-0">
                    <VueDatePicker
                        v-model="inputdate"
                        :enable-time-picker="false"
                        dark
                    ></VueDatePicker>
                    <!-- :format="format" -->
                </div>
                <div class="col-5 mx-0 px-0 d-flex justify-content-left">
                    <input
                        type="text"
                        v-model="keywords"
                        class="form-control keyword-input"
                    />
                </div>
                <div class="col-1 mx-0 d-flex justify-content-end">
                    <button
                        class="btn btn-sm btn-primary"
                        @click="changePropValue"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/vue3";

import { defineComponent, onMounted, ref } from "vue";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import { useFilterStore } from "../../stores/filter";

export default defineComponent({
    name: "navigation",
    components: { Link, VueDatePicker },

    setup() {
        const filterStore = useFilterStore();

        const inputdate = ref(null);
        const keywords = ref(null);

        const changePropValue = () => {
            filterStore.date = inputdate.value;
            filterStore.keywords = keywords.value;
        };
        return {
            keywords,
            inputdate,
            changePropValue,
        };
    },
});
</script>

<style scoped>
.keyword-input {
    height: 36px !important;
    border: 1px solid #dddddd !important;
    color: black !important;
    background-color: #ebedf0;
    font-weight: 100;
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
    color: black !important;
    font-size: 16px;
    padding: 0px;
    margin: 0px;
}

.active {
    border-bottom: 3px solid #144f9f !important;
}
.dp__theme_dark {
    --dp-background-color: #ebedf0;
    --dp-text-color: #000;

    --dp-border-color: #dddddd;
    --dp-menu-border-color: #dddddd;
    --dp-icon-color: #000;

    font-size: 5px !important;
}
</style>

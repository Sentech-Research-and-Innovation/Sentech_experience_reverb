<template>
    <Head :title="'Others'"><title>Others</title></Head>

    <div class="col-12">
        <div class="row pb-5">
            <div class="col-5">
                <div><h2>Other</h2></div>
            </div>

            <div class="col-2 mx-0">
                <VueDatePicker
                    v-model="inputdate"
                    :enable-time-picker="false"
                ></VueDatePicker>
                <!-- :format="format" -->
            </div>
            <div class="col-3 mx-0">
                <input
                    type="text"
                    v-model="keywords"
                    class="form-control keyword-input"
                />
            </div>
            <div class="col-2 mx-0">
                <button class="btn btn-sm btn-primary" @click="changePropValue">
                    Search
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <Map :filter="searchFilter" />
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref } from "vue";

import Map from "./vectorMap.vue";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        Link,
        Head,
        Map,
        VueDatePicker,
    },
    setup() {
        const searchFilter = ref({
            date: null,
            keywords: "",
        });

        const inputdate = ref(null);
        const keywords = ref(null);

        const changePropValue = () => {
            searchFilter.value = {
                date: inputdate.value,
                keywords: keywords.value,
            };
        };

        return {
            inputdate,
            searchFilter,
            changePropValue,
            keywords,
        };
    },
});
</script>

<style scoped>
.keyword-input {
    height: 36px !important;
    border: 1px solid #dddddd !important;
}
.btn-primary {
    background-color: #144f9f;
    border: none;
    height: 36px;
}
</style>

<template>
    <div class="col-12">
        <div class="row mt-4">
            <div class="col-5">
                <div class="col-12"><h2>Analytics Overview</h2></div>
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
        <OverallSentiments :filter="searchFilter"></OverallSentiments>
        <div class="row mt-4">
            <div class="col-7">
                <timelineChart :filter="searchFilter" />
            </div>
            <div class="col-5 mx-0">
                <tweetsLocation :filter="searchFilter"></tweetsLocation>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import timelineChart from "./sentimentsTimeline.vue";
import tweetsLocation from "./tweetsByLocation.vue";
import OverallSentiments from "./overallSentiments.vue";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default defineComponent({
    name: "sentiment-analysis-over-index",
    layout: AdminLayout,

    components: {
        timelineChart,
        tweetsLocation,
        OverallSentiments,
        VueDatePicker,
    },
    setup() {
        const searchFilter = ref({
            date: null,
            keywords: "",
        });
        const inputdate = ref(null);
        const keywords = ref(null);
        // const format = (inputdate) => {
        //     const day = inputdate.getDate();
        //     const month = inputdate.getMonth() + 1;
        //     const year = inputdate.getFullYear();

        //     return `${year}-${month}-${day}`;
        // };

        const changePropValue = () => {
            searchFilter.value = {
                date: inputdate.value,
                keywords: keywords.value,
            };
        };
        // watch(inputdate, (newInputDate) => {
        //     date.value = newInputDate;
        // });
        return {
            inputdate,
            // format,
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

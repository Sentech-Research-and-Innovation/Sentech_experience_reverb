<template>
    <div class="col-12">
        <div class="row mt-4">
            <div class="col-7">
                <div class="col-12"><h2>Analytics Overview</h2></div>
            </div>
            <div class="col-5 mx-0"></div>
        </div>
        <OverallSentiments />
        <div class="row mt-4">
            <div class="col-7">
                <timelineChart />
            </div>
            <div class="col-5 mx-0">
                <tweetsLocation />
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import timelineChart from "./sentimentsTimeline.vue";
import tweetsLocation from "./tweetsByLocation.vue";
import OverallSentiments from "./overallSentiments.vue";

import { defineComponent, ref } from "vue";

export default {
    name: "sentiment-analysis-over-index",
    layout: AdminLayout,

    components: {
        timelineChart,
        tweetsLocation,
        OverallSentiments,
    },
};
</script>

<template>
    <Head :title="'Overview'"><title>Overview</title></Head>
    <div class="col-12 px-0"><navigationSearchBar /></div>
    <OverallSentiments></OverallSentiments>

    <div class="col-12">
        <div class="row mt-3">
            <div class="col-lg-6 col-12 pr-lg-0 pl-lg-2 px-0">
                <timelineChart />
            </div>
            <div class="col-lg-6 col-12 pt-lg-0 pt-3 pl-lg-2 pr-lg-3 px-0">
                <tweetsLocation></tweetsLocation>
            </div>
            <div class="col-12 pt-3 px-lg-3 px-0">
                <TweetsByHour />
            </div>
            <div class="col-12 px-lg-3 px-0">
                <tweetContent />
            </div>

            <!-- <div class="col-12 pt-3 px-lg-3 px-0">
                <vectorMap />
            </div>-->
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, watch, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import timelineChart from "./Overview/sentimentsTimeline.vue";
import tweetsLocation from "./Overview/tweetsByLocation.vue";
import OverallSentiments from "./Overview/overallSentiments.vue";
import TweetsByHour from "../Sentiments/Timelines/tweetsByHour.vue";
import tweetContent from "../Sentiments/Trends/tweetAnalysisTable.vue";

import vectorMap from "../Sentiments/Others/vectorMap.vue";

import { Head, Link } from "@inertiajs/inertia-vue3";
import navigationSearchBar from "../../../Layouts/sentiments/navigationSearchBar.vue";

export default defineComponent({
    name: "sentiment-analysis-over-index",
    layout: AdminLayout,

    components: {
        Link,
        Head,
        timelineChart,
        tweetsLocation,
        OverallSentiments,
        TweetsByHour,
        tweetContent,
        vectorMap,
        navigationSearchBar,
    },
    setup() {
        return {};
    },
});
</script>

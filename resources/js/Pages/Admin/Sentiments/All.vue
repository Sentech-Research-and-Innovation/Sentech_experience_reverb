<template>
    <Head :title="'Overview'"><title>Overview</title></Head>

    <div class="col-12 px-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>

        <OverallSentiments></OverallSentiments>
        <div class="row mt-4 mx-3">
            <div class="col-6">
                <timelineChart />
            </div>
            <div class="col-6">
                <tweetsLocation></tweetsLocation>
            </div>
            <div class="col-12 pt-4">
                <TweetsByHour />
            </div>
            <div class="col-12 pt-3">
                <tweetContent />
            </div>

            <div class="col-12 pt-3">
                <vectorMap />
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, watch, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

import timelineChart from "./Overview/sentimentsTimeline.vue"; //./sentimentsTimeline.vue
import tweetsLocation from "./Overview/tweetsByLocation.vue";
import OverallSentiments from "./Overview/overallSentiments.vue";
import TweetsByHour from "../Sentiments/Timelines/tweetsByHour.vue"; //"../../Sentiments/Timelines/tweetsByHour.vue";
import tweetContent from "../Sentiments/Trends/tweetAnalysisTable.vue";

import vectorMap from "../Sentiments/Others/vectorMap.vue";

import { Head, Link } from "@inertiajs/inertia-vue3";
import navigationSearchBar from "../../../Layouts/sentiments/navigationSearchBar.vue"; //"../../../../Layouts/sentiments/navigationSearchBar.vue";

//import { useFilterStore } from "../../../stores/filter";

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

<template>
    <Head :title="'Overview'"><title>Overview</title></Head>

    <div class="col-12 px-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>

        <OverallSentiments></OverallSentiments>
        <div class="row mx-lg-2 mt-3">
            <div class="col-lg-6 col-12 pr-lg-0 pl-lg-0">
                <timelineChart />
            </div>
            <div class="col-lg-6 col-12 px-lg-2 pt-lg-0 pt-3 px-4">
                <tweetsLocation></tweetsLocation>
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

import { Head, Link } from "@inertiajs/vue3";
import navigationSearchBar from "../../../../Layouts/sentiments/navigationSearchBar.vue";

export default defineComponent({
    name: "sentiment-analysis-over-index",
    layout: AdminLayout,

    components: {
        Link,
        Head,
        timelineChart,
        tweetsLocation,
        OverallSentiments,
        navigationSearchBar,
    },
    setup() {
        return {};
    },
});
</script>

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

    <!-- 🔔 Pop-up Notification at Bottom -->
    <div
        v-if="showPopup"
        class="fixed bottom-0 left-0 w-100 bg-dark text-white p-4 d-flex justify-content-between align-items-center shadow"
        style="z-index: 1050"
    >
        <div class="me-3">
            <strong>The site is under construction</strong>
        </div>
        <button class="btn btn-light" @click="showPopup = false">
            Continue
        </button>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
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
        // 🚨 This controls the pop-up visibility
        const showPopup = ref(true);

        return {
            showPopup,
        };
    },
});
</script>

<style scoped>
/* Optional: smooth fade in */
.fixed {
    animation: fadeIn 0.5s ease-in-out;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(100%);
    }
    to {
        opacity: 1;
        transform: translateY(0%);
    }
}
</style>

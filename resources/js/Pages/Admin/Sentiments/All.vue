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

    <!--Fixed Pop-up Notification at Bottom of Screen -->
    <div v-if="showPopup" class="popup-container">
        <div class="popup-banner">
            <div class="popup-message">
                <strong>The site is under construction</strong>
            </div>
            <button class="popup-button" @click="showPopup = false">
                Continue
            </button>
        </div>
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
        const showPopup = ref(true); // Controls the visibility of the popup
        return {
            showPopup,
        };
    },
});
</script>

<style scoped>
/* Container that positions the popup relative to main content */
.popup-container {
    position: fixed;
    bottom: 0;
    left: var(--sidebar-width, 250px); /* adjust if your sidebar width is fixed */
    width: calc(100% - var(--sidebar-width, 250px));
    padding: 0 1rem;
    z-index: 1050;
}

/* Banner styling */
.popup-banner {
    background-color: #222;
    color: white;
    padding: 1rem 2rem;
    border-radius: 5px 5px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 0.4s ease;
}

.popup-button {
    background-color: white;
    color: black;
    border: none;
    padding: 0.5rem 1rem;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

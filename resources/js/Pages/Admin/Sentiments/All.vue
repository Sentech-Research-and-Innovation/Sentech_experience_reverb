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
    <div v-if="showBanner" class="custom-banner">
        <div class="custom-banner-content">
            <div class="custom-banner-text">
                <h3 class="custom-banner-heading">Sentiment analysis is currently under construction</h3>
                <p class="custom-banner-subtext">
                    The R&amp;I team is working on it. Click "Continue" to keep browsing.
                </p>
            </div>
            <button class="custom-banner-button" @click="closeBanner">Continue</button>
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

import { Head, Link } from "@inertiajs/vue3";
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
        const showBanner = ref(true);

        const closeBanner = () => {
            showBanner.value = false;
        };

        return {
            showBanner,
            closeBanner,
        };
    },
});
</script>

<style scoped>
/* So main content doesn't hide behind the banner */
.content-wrapper {
  padding-bottom: 120px;
}

/* Sticky Banner Styling */
.custom-banner {
  position: fixed;
  bottom: 0;
  left: 250px; /* Adjust based on sidebar width */
  right: 0;
  background-color: #0E4C9D; /* Blue color */
  color: white;
  z-index: 9999;
  padding: 20px;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.15);
}

.custom-banner-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1000px;
  margin: 0 auto;
}

.custom-banner-text {
  text-align: left;
}

.custom-banner-heading {
  font-size: 20px;
  font-weight: bold;
  margin-bottom: 4px;
}

.custom-banner-subtext {
  font-size: 16px;
  margin: 0;
}

.custom-banner-button {
  background-color: white;
  color: #0E4C9D;
  border: 1px solid #0E4C9D;
  padding: 10px 20px;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
  margin-left: 20px;
}

.custom-banner-button:hover {
  background-color: #e6e6e6;
}
</style>

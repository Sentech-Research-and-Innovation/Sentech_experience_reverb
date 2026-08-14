<template>
    <Head :title="'Trends'"><title>Trends</title></Head>

    <div class="col-12 px-0 mx-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>

        <div class="row px-lg-4 px-3 mt-3">
            <div class="col-lg-6 col-12 pr-lg-0 px-2">
                <TweetsByDate />
            </div>
            <div class="col-lg-6 col-12 pt-lg-0 pt-3 pr-2">
                <WordCloud />
            </div>
        </div>

        <div class="col-12 px-lg-3 px-3">
            <TweetAnalysisTable />
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref, onMounted } from "vue";
import WordCloud from "./wordCloud.vue";

import TweetAnalysisTable from "./tweetAnalysisTable.vue";

import TweetsByDate from "./tweetsByDate.vue";

import navigationSearchBar from "../../../../Layouts/sentiments/navigationSearchBar.vue";

import { Head, Link } from "@inertiajs/vue3";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        Link,
        Head,
        navigationSearchBar,
        TweetAnalysisTable,
        TweetsByDate,
        WordCloud,
    },
    setup() {
        return {};
    },
});
</script>

<style scoped></style>

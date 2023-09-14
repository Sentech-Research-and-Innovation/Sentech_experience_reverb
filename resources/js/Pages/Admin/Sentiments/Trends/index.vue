<template>
    <Head :title="'Trends'"><title>Trends</title></Head>

    <div class="col-12 px-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>

        <div class="row px-4">
            <div class="col-6">
                <TweetsByDate />
            </div>
            <div class="col-6">
                <WordCloud />
            </div>
        </div>

        <div class="col-12 px-4">
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

import { Head, Link } from "@inertiajs/inertia-vue3";

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

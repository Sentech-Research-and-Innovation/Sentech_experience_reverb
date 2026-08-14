<template>
    <Head :title="'Time Lines'"><title>Time Lines</title></Head>

    <div class="col-12 px-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>

        <div class="row px-3 mt-3">
            <div class="col-lg-6 col-12">
                <TweetsHour />
            </div>
            <div class="col-lg-6 col-12 pl-lg-0 mx-0 pt-lg-0 pt-3">
                <TweetsByDate />
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref } from "vue";

import TweetsHour from "./tweetsByHour.vue";
import TweetsByDate from "./tweetsByDate.vue";

import navigationSearchBar from "../../../../Layouts/sentiments/navigationSearchBar.vue";

import { Head, Link } from "@inertiajs/vue3";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        Head,
        Link,
        TweetsHour,
        TweetsByDate,
        navigationSearchBar,
    },
    setup() {
        return {};
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

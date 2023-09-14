<template>
    <Head :title="'Time Lines'"><title>Time Lines</title></Head>

    <div class="col-12 px-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>

        <div class="row px-4">
            <div class="col-6">
                <TweetsHour />
            </div>
            <div class="col-6 px-0 mx-0">
                <TweetsAndLikes />
            </div>
            <div class="col-12 mt-4">
                <TweetsByDate />
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref } from "vue";

import TweetsHour from "./tweetsByHour.vue";
import TweetsAndLikes from "./tweetsAndLikes.vue";
import TweetsByDate from "./tweetsByDate.vue";

import navigationSearchBar from "../../../../Layouts/sentiments/navigationSearchBar.vue";

import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        Head,
        Link,
        TweetsHour,
        TweetsAndLikes,
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

<template>
    <div class="col-12">
        <div class="col-12 pb-5 mx-0 px-0"><h2>Time Lines</h2></div>

        <div class="row">
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

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        TweetsHour,
        TweetsAndLikes,
        TweetsByDate,
    },
});
</script>

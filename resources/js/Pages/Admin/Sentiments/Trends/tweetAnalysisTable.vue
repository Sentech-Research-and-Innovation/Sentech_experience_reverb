<template>
    <div class="row py-5">
        <div class="col-12">
            <div><h2>Tweet Content</h2></div>

            <div class="col-12 pt-4 shadow-sm pb-4 mt-4">
                <div class="row" v-if="!loading">
                    <div class="col-9">
                        <div class="row pb-3">
                            <div class="col-2">
                                <strong>Sentiments</strong>
                            </div>
                            <div class="col-1"><strong>Likes</strong></div>
                            <div class="col-9">
                                <strong>Tweet Content</strong>
                            </div>
                        </div>
                        <div v-if="tweets.tweetsContent.length !== 0">
                            <div
                                class="col-12 tweets-wrapper1"
                                v-for="(tweet, index) in tweets.tweetsContent"
                                :key="index"
                            >
                                <div class="row tweets-container">
                                    <div
                                        class="col-2 sentiment py-2"
                                        :class="{
                                            'tweets-container-negative':
                                                tweet.sentiment === 'negative',
                                            'tweets-container-neutral':
                                                tweet.sentiment === 'neutral',
                                            'tweets-container-positive':
                                                tweet.sentiment === 'positive',
                                        }"
                                    >
                                        {{ tweet.sentiment }}
                                    </div>
                                    <div class="col-1 py-2 likes text-center">
                                        1
                                    </div>

                                    <div
                                        class="col-9 py-2"
                                        v-html="highlightKeywords(tweet.tweet)"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div
                                class="col-12 tweets-wrapper1 text-center py-5"
                            >
                                No Results found
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="col-12 sentiments-counts">
                            <div class="sentiments-labels">
                                <strong> Postive </strong>
                            </div>
                            <div class="py-2">
                                <strong>
                                    {{ tweets.positiveTweets }}
                                </strong>
                            </div>
                            <div>Number Of Tweets</div>
                        </div>
                        <div class="col-12 sentiments-counts mt-4">
                            <div class="sentiments-labels">
                                <strong> Neutral </strong>
                            </div>
                            <div class="py-2">
                                <strong>
                                    {{ tweets.neutralTweets }}
                                </strong>
                            </div>
                            <div>Number Of Tweets</div>
                        </div>
                        <div class="col-12 sentiments-counts mt-4">
                            <div class="sentiments-labels">
                                <strong> Negative </strong>
                            </div>
                            <div class="py-2">
                                <strong>
                                    {{ tweets.negativeTweets }}
                                </strong>
                            </div>
                            <div>Number Of Tweets</div>
                        </div>
                    </div>
                </div>
                <div v-else class="row">
                    <div class="col-12 text-center">
                        <img :src="LoadingGif" width="50" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import LoadingGif from "../../../../assets/loading.gif";

export default defineComponent({
    name: "sentiment-analysis-tweets-analysis-table",

    components: {},
    props: ["filter"],
    setup(props) {
        const tweets = ref([]);
        const loading = ref(true);
        const searchFilter = ref({
            date: "",
            keywords: "",
        });
        const getData = async () => {
            loading.value = true;
            const res = await axios.post(
                `/admin/sentiments/trends/tweetsContent`,
                { searchFilter: searchFilter.value }
            );
            if (res.status === 200) {
                tweets.value = res.data;
                loading.value = false;
            }
        };

        const highlightKeywords = (tweetText) => {
            // Get the keywords from the filter
            const keywords = searchFilter.value.keywords
                .toLowerCase()
                .split(" ");

            // Create a regular expression to match the keywords
            const keywordRegex = new RegExp(keywords.join("|"), "gi");

            // Use replace with a custom function to highlight keywords
            return tweetText.replace(keywordRegex, (match) => {
                return `<span style="background-color:yellow!important">${match}</span>`;
            });
        };

        watch(
            () => props.filter,
            (first, second) => {
                searchFilter.value = first;

                tweets.value = [];
                getData();
            }
        );
        onMounted(async () => {
            getData();
        });
        return {
            tweets,
            searchFilter,
            highlightKeywords,
            LoadingGif,
            loading,
        };
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

.tweets-container {
    color: #000 !important;
    border-top: 1px solid #c0bcbc;
}

.tweets-container-negative {
    color: #ec1c24 !important;
}
.tweets-container-neutral {
    color: #118dff !important;
}

.tweets-container-positive {
    color: #00c83c !important;
}

.sentiment {
    border-right: 1px solid #dddddd;
}

.likes {
    border-right: 1px solid #dddddd;
}

.tweets-wrapper {
}

.sentiments-counts {
    border-left: 3px solid #dddddd;
    color: #737272;
}

.sentiments-labels {
    color: #144f9f !important;
}

.highlightedYellow {
    background-color: yellow !important;
    font-weight: bold;
}
</style>

<template>
    <div class="row">
        <div class="col-12">
            <div class="col-12 pt-4 shadow-border pb-4 mt-3">
                <div><h2>Tweet Content</h2></div>
                <div class="row pt-3" v-if="!loading">
                    <div class="col-12 mt-3 mb-5 px-4">
                        <div class="row">
                            <div class="col-4 sentiments-counts">
                                <div class="sentiments-labels">
                                    <strong> Postive </strong>
                                </div>
                                <div class="py-2">
                                    <strong>
                                        {{ tweets.positiveTweets }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-4 sentiments-counts">
                                <div class="sentiments-labels">
                                    <strong> Neutral </strong>
                                </div>
                                <div class="py-2">
                                    <strong>
                                        {{ tweets.neutralTweets }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-4 sentiments-counts">
                                <div class="sentiments-labels">
                                    <strong> Negative </strong>
                                </div>
                                <div class="py-2">
                                    <strong>
                                        {{ tweets.negativeTweets }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" style="height: 500px">
                        <vue-scroll>
                            <div class="row pb-3">
                                <div class="col-3">
                                    <strong>Sentiments</strong>
                                </div>
                                <div class="col-9">
                                    <strong>Tweet Content</strong>
                                </div>
                            </div>
                            <div v-if="tweets.tweetsContent.length !== 0">
                                <div
                                    class="col-12 tweets-wrapper1"
                                    v-for="(
                                        tweet, index
                                    ) in tweets.tweetsContent"
                                    :key="index"
                                >
                                    <div class="row tweets-container">
                                        <div
                                            class="col-3 sentiment py-2"
                                            :class="{
                                                'tweets-container-negative':
                                                    tweet.sentiment ===
                                                    'NEGATIVE',
                                                'tweets-container-neutral':
                                                    tweet.sentiment ===
                                                    'NEUTRAL',
                                                'tweets-container-positive':
                                                    tweet.sentiment ===
                                                    'POSITIVE',
                                            }"
                                        >
                                            {{ tweet.sentiment }}
                                        </div>

                                        <div
                                            v-if="search.keywords"
                                            class="col-9 py-2 tweetsColor"
                                        >
                                            <div
                                                v-html="
                                                    highlightKeywords(
                                                        tweet.text
                                                    )
                                                "
                                            ></div>
                                            <div>
                                                <br />
                                                {{ formattedDate(tweet.date) }}
                                            </div>
                                        </div>
                                        <div
                                            v-else
                                            class="col-9 py-2 tweetsColor"
                                        >
                                            {{ tweet.text }} <br />
                                            <br />
                                            {{ formattedDate(tweet.date) }}
                                        </div>
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
                        </vue-scroll>
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
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import LoadingGif from "../../../../assets/loading.gif";
import { useFilterStore } from "../../../../stores/filter";
import { DateTime } from "luxon";
export default defineComponent({
    name: "sentiment-analysis-tweets-analysis-table",

    components: {},
    setup() {
        const filterStore = useFilterStore();

        const loading = ref(true);
        const tweets = ref([]);
        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            date: "",
            keywords: "",
            sentimentTypes: "",
        });
        const getData = async () => {
            loading.value = true;
            const res = await axios.post(
                `/admin/sentiments/trends/tweetsContent`,
                { searchFilter: search.value }
            );
            if (res.status === 200) {
                tweets.value = res.data;
                loading.value = false;
            }
        };
        const highlightKeywords = (tweetText) => {
            const keywords = (search.value.keywords || "")
                .toLowerCase()
                .split(" ");

            const keywordRegex = new RegExp(keywords.join("|"), "gi");
            return tweetText.replace(keywordRegex, (match) => {
                return `<span style="background-color:yellow!important">${match}</span>`;
            });
        };
        const formattedDate = (date) => {
            const options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true,
            };

            // Parse the input date string and convert it to UTC+2
            const localDate = new Date(date);
            localDate.setUTCHours(localDate.getUTCHours() - 2);

            // Format the date and time
            return localDate.toLocaleDateString(undefined, options);
        };
        watch(searchFilter, (newFilter, oldFilter) => {
            const { date, keywords, sentimentTypes } = newFilter;
            search.value = {
                date: date,
                keywords: keywords,
                sentimentTypes: sentimentTypes,
            };

            tweets.value = [];
            getData();
        });
        onMounted(async () => {
            search.value = {
                date: searchFilter.value.date,
                keywords: searchFilter.value.keywords,
                sentimentTypes: searchFilter.value.sentimentTypes,
            };

            await getData();
        });
        return {
            tweets,
            searchFilter,
            highlightKeywords,
            LoadingGif,
            loading,
            search,
            formattedDate,
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
    color: rgba(255, 69, 96, 0.85) !important;
}
.tweets-container-neutral {
    color: rgba(119, 93, 208, 0.85) !important;
}

.tweets-container-positive {
    color: rgba(0, 227, 150, 0.85) !important;
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

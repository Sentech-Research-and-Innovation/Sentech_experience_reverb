<template>
    <div class="col-12 mx-lg-0 mt-3">
        <div class="row">
            <div v-if="loading" class="col-lg-2 col-6 mb-lg-0 mb-3 pr-0">
                <div class="col-12 shadow-border text-center py-5">
                    <img :src="LoadingGif" width="50" />
                </div>
            </div>
            <div v-else class="col-lg-2 col-6 pr-0 mb-lg-0 mb-3">
                <div class="col-12 shadow-border text-center py-5">
                    <h1 class="text-dark-bold">
                        {{ overallData.totalTweets }}
                    </h1>
                    <p class="text-total">Total Tweets</p>
                </div>
            </div>

            <div class="col-lg-2 col-6 pr-lg-0 mb-lg-0 mb-3" v-if="!loading">
                <div class="col-12 shadow-border text-center py-5">
                    <h1 class="text-dark-bold">
                        {{ overallData.positiveTweets }}
                    </h1>
                    <p class="text-positive">Positive Tweets</p>
                </div>
            </div>
            <div v-else class="col-lg-2 col-6 pr-0 mb-lg-0 mb-3">
                <div class="col-12 shadow-border text-center py-5">
                    <img :src="LoadingGif" width="50" />
                </div>
            </div>
            <div class="col-lg-2 col-6 pr-0 mb-lg-0 mb-3" v-if="!loading">
                <div class="col-12 shadow-border text-center py-5">
                    <h1 class="text-dark-bold">
                        {{ overallData.neutralTweets }}
                    </h1>
                    <p class="text-neutral">Neutral Tweets</p>
                </div>
            </div>
            <div v-else class="col-lg-2 col-6 pr-0 mb-lg-0 mb-3">
                <div class="col-12 shadow-border text-center py-5">
                    <img :src="LoadingGif" width="50" />
                </div>
            </div>
            <div class="col-lg-2 col-6 pr-lg-0 mb-lg-0 mb-3" v-if="!loading">
                <div class="col-12 px-0 mx-0 shadow-border text-center py-5">
                    <h1 class="text-dark-bold">
                        {{ overallData.negativeTweets }}
                    </h1>
                    <p class="text-negative">Negative Tweets</p>
                </div>
            </div>
            <div v-else class="col-lg-2 col-6 pr-lg-0 mb-lg-0 mb-3">
                <div class="col-12 shadow-border text-center py-5">
                    <img :src="LoadingGif" width="50" />
                </div>
            </div>
            <div class="col-lg-4 pr-lg-3">
                <div
                    class="col-12 py-1 px-2 mx-0 shadow-border"
                    v-if="!loading && overallData.totalTweets > 0"
                >
                    <div>
                        <apexchart
                            :options="chartOptions"
                            :series="series"
                            type="pie"
                            height="200"
                        />
                    </div>
                </div>
                <div v-if="loading" class="col-12 px-0 mx-0">
                    <div class="col-12 shadow-border text-center py-5">
                        <img :src="LoadingGif" width="50" />
                    </div>
                </div>
                <div
                    v-if="!loading && overallData.totalTweets == 0"
                    class="col-12 px-0 mx-0"
                >
                    <div class="col-12 shadow-border text-center py-5">
                        <div class="py-4 fs-5">No results Found</div>
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

export default defineComponent({
    name: "sentiment-analysis-overall-sentiments",

    components: {},
    setup() {
        const filterStore = useFilterStore();

        const loading = ref(true);

        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            date: "",
            keywords: "",
            sentimentTypes: "",
        });
        const overallData = ref([]);
        const chartOptions = ref({
            chart: {
                type: "pie",
            },

            stroke: {
                show: false,
            },
            colors: ["#00e396", "#775dd0", "#ff4560"],
            labels: ["POSITIVE", "NEUTRAL", "NEGATIVE"],
            legend: {
                position: "bottom",
            },
        });

        const series = ref([]);

        const getData = async () => {
            const res = await axios.post(
                `/admin/sentiments/overview/overall-sentiments`,
                { searchFilter: search.value }
            );

            if (res.status === 200) {
                overallData.value.totalTweets =
                    res.data.positiveTweets +
                    res.data.neutralTweets +
                    res.data.negativeTweets;
                overallData.value.positiveTweets = res.data.positiveTweets;
                overallData.value.neutralTweets = res.data.neutralTweets;
                overallData.value.negativeTweets = res.data.negativeTweets;
                series.value = [
                    overallData.value.positiveTweets,
                    overallData.value.neutralTweets,
                    overallData.value.negativeTweets,
                ];
                loading.value = false;
            }
        };

        onMounted(async () => {
            search.value = {
                date: searchFilter.value.date,
                keywords: searchFilter.value.keywords,
                sentimentTypes: searchFilter.value.sentimentTypes,
            };

            getData();
        });

        watch(
            searchFilter,
            (newFilter, oldFilter) => {
                const { date, keywords, sentimentTypes } = newFilter;
                search.value = {
                    date: date,
                    keywords: keywords,
                    sentimentTypes: sentimentTypes,
                };

                overallData.value.totalTweets = null;
                overallData.value.positiveTweets = null;
                overallData.value.neutralTweets = null;
                overallData.value.negativeTweets = null;
                series.value = [
                    overallData.value.positiveTweets,
                    overallData.value.neutralTweets,
                    overallData.value.negativeTweets,
                ];
                loading.value = true;
                getData();
            },
            { deep: true }
        );
        return {
            overallData,
            chartOptions,
            series,
            searchFilter,
            LoadingGif,
            search,
            loading,
        };
    },
});
</script>

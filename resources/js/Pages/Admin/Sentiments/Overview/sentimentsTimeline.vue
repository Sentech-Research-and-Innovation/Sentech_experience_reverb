<template>
    <div class="col-12 px-2">
        <div
            class="col-12 shadow-border mx-0"
            style="min-height: 500px"
            id="timeline-chart"
        >
            <h3 class="py-4 chart-heading">Sentiments Timeline (cumulative)</h3>
            <div
                class="col-12 text-center"
                v-if="loading"
                style="height: 300px; padding-top: 100px"
            >
                <img :src="LoadingGif" width="50" />
            </div>
            <div
                v-if="permissionError"
                class="col-12 text-center"
                style="height: 300px; padding-top: 100px; color: red"
            >
                {{ permissionError }}
            </div>
            <apexchart
                v-else
                height="400"
                type="line"
                :options="chartUtil.chartOptions"
                :series="seriesData"
            ></apexchart>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import chartUtil from "../../../../utils/sentimentsTimelineChart";
import LoadingGif from "../../../../assets/loading.gif";

import { useFilterStore } from "../../../../stores/filter";

export default defineComponent({
    name: "sentiment-analysis-over-sentimentsTimelineChart",

    components: {},

    setup() {
        const filterStore = useFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);

        const loading = ref(true);
        const search = ref({
            date: "",
            keywords: "",
            sentimentTypes: "",
        });

        const seriesData = ref([
            {
                type: "area",
                name: "Negative",
                data: [],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100, 100],
                    },
                },
            },
            {
                type: "area",
                name: "Positive",
                chart: {
                    dropShadow: {
                        enabled: true,
                        enabledOnSeries: undefined,
                        top: 5,
                        left: 0,
                        blur: 3,
                        color: "#000",
                        opacity: 0.9,
                    },
                },
                data: [],
            },
            {
                type: "area",
                name: "Neutral",
                chart: {
                    dropShadow: {
                        enabled: true,
                        enabledOnSeries: undefined,
                        top: 5,
                        left: 0,
                        blur: 3,
                        color: "#000",
                        opacity: 0.2,
                    },
                },
                data: [],
            },
        ]);

        const permissionError = ref("");

        const getData = async () => {
            try {
                const res = await axios.post(
                    `/admin/sentiments/overview/sentimentsTimeline`,
                    { searchFilter: search.value }
                );
                if (res.status === 200) {
                    for (const key in res.data) {
                        const monthData = res.data[key];

                        seriesData.value[0].data.push({
                            x: monthData.month + " " + monthData.year,
                            y: monthData.sentiments.NEGATIVE,
                        });

                        seriesData.value[1].data.push({
                            x: monthData.month + " " + monthData.year,
                            y: monthData.sentiments.POSITIVE,
                        });
                        seriesData.value[2].data.push({
                            x: monthData.month + " " + monthData.year,
                            y: monthData.sentiments.NEUTRAL,
                        });
                    }
                    loading.value = false;
                }
            } catch (error) {
                if (error.response && error.response.status === 403) {
                    permissionError.value =
                        "Access forbidden: You do not have the necessary permissions. to view Overview Data";
                }
                loading.value = false;
            }
        };

        onMounted(async () => {
            search.value = {
                date: searchFilter.value.date,
                keywords: searchFilter.value.keywords,
                sentimentTypes: searchFilter.value.sentimentTypes,
            };

            await getData();
        });

        watch(searchFilter, (newFilter, oldFilter) => {
            const { date, keywords, sentimentTypes } = newFilter;
            search.value = {
                date: date,
                keywords: keywords,
                sentimentTypes: sentimentTypes,
            };

            seriesData.value[0].data = [];
            seriesData.value[0].type = "bar";

            seriesData.value[1].data = [];
            seriesData.value[1].type = "bar";

            seriesData.value[2].data = [];
            seriesData.value[2].type = "bar";
            loading.value = true;
            getData();
        });

        // onMounted(async () => {
        //     getData();
        // });

        return {
            getData,
            seriesData,
            chartUtil,
            loading,
            LoadingGif,
            searchFilter,
            search,
            permissionError,
        };
    },
});
</script>

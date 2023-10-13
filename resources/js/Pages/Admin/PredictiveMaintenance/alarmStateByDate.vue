<template>
    <div class="col-12 shadow-border my-3 px-0">
        <div class="col-12 pt-4">
            <h4>Count of Sensors in Alarm State by Date</h4>
            <p style="color: #86abd1">Showing top 10 by date</p>
        </div>
        <div v-if="chartData">
            <apexchart
                v-if="dataLoaded"
                type="bar"
                :options="chartOptions"
                :series="chartData.series"
                width:="100%"
                height="315"
            ></apexchart>

        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import axios from "axios";
import { predictionsFilterStore } from "../../../stores/predictionsFilter";

export default defineComponent({
    setup() {
        const dataLoaded = ref(false);

        const predictions = ref([]);
        const categoryLabels = ref([]);
        const filterStore = predictionsFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            searchFilter,
        });

        const getPredictions = async () => {
            const res = await axios.post(
                "/admin/predictive-maintenance/predictions",
                {
                    searchData: search.value,
                }
            );

            if (res.status === 200) {
                predictions.value = res.data;
                dataLoaded.value = true;
            } else {
                predictions.value = [];
            }
        };

        onMounted(async () => {
            getPredictions();
        });

        // Compute the chart data and options
        const chartData = ref(null);
        const chartOptions = ref({
            chart: {
                animations: {
                    speed: 2000,
                },
                offsetX: 0,
                toolbar: {
                    show: false,
                    tools: {
                        download: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        reset: false,
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
            grid: {
                border: false,
                strokeDashArray: 1,
                show: true,

                xaxis: {
                    lines: {
                        show: false,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                row: {
                    colors: undefined,
                    opacity: 0.5,
                },
                column: {
                    colors: undefined,
                    opacity: 0.5,
                },
            },

            xaxis: {
                categories: [],
                labels: {
                    show: true,

                    rotate: -45,
                    rotateAlways: true,
                    style: {
                        colors: [
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                        ],
                    },
                },
            },

            yaxis: {
                categories: [],
                labels: {
                    show: true,

                    style: {
                        colors: [
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                            "#144f9f",
                        ],
                    },
                },
            },
            colors: ["#144f9f"],
        });

        watch(searchFilter, (newFilter, oldFilter) => {
            const { siteNames, date } = newFilter;
            search.value = {
                searchFilter: {
                    siteNames: siteNames,
                    date: date,
                },
            };
            chartData.value = [];
            dataLoaded.value = false;

            getPredictions();
        });

        // Watch for changes in predictions and update chart data and options

        const convertDate = (date) => {
            const optionsDate = {
                year: "numeric",
                month: "short",
                day: "numeric",
            };
            return date.toLocaleDateString("en-US", optionsDate);
        };
        watch(predictions, (newPredictions) => {
            if (newPredictions) {
                const uniqueItemCountByDate = newPredictions.reduce(
                    (counts, prediction) => {
                        const date = prediction.date;
                        const item_id = prediction.item_id;
                        counts[date] = counts[date] || new Set();
                        counts[date].add(item_id);
                        return counts;
                    },
                    {}
                );
                const sortedDates = Object.keys(uniqueItemCountByDate).sort(
                    (a, b) =>
                        uniqueItemCountByDate[b].size -
                        uniqueItemCountByDate[a].size
                );

                const top10Dates = sortedDates.slice(0, 10);
                console.log(top10Dates);

                const formattedDates = top10Dates.map((date) =>
                    convertDate(new Date(date))
                );
                chartOptions.value = {
                    ...chartOptions.value, // Keep other properties unchanged
                    xaxis: {
                        ...chartOptions.value.xaxis, // Keep other xaxis properties unchanged
                        categories: formattedDates,
                    },
                };
                chartData.value = {
                    series: [
                        {
                            name: "Count Alarm State",
                            data: top10Dates.map(
                                (date) => uniqueItemCountByDate[date].size
                            ),
                        },
                    ],
                };
            }
        });

        return {
            predictions,
            chartOptions,
            chartData,
            searchFilter,
            dataLoaded,
        };
    },
});
</script>

<style></style>

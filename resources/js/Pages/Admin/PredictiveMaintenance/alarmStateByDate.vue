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

        // onMounted(async () => {
        //     getPredictions();
        // });

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
                // Calculate unique item counts for each date where alarm value is 1
                const uniqueItemCountByDate = newPredictions.reduce(
                    (counts, prediction) => {
                        const date = prediction.date;
                        const item_id = prediction.item_id;
                        const alarm = prediction.alarm;

                        // Check if alarm value is 1
                        if (alarm === 0) {
                            counts[date] = counts[date] || new Set();
                            counts[date].add(item_id);
                        }

                        return counts;
                    },
                    {}
                );

                // Sort dates in descending order
                const sortedDates = Object.keys(uniqueItemCountByDate).sort(
                    (a, b) => {
                        const dateA = new Date(a);
                        const dateB = new Date(b);
                        return dateB - dateA;
                    }
                );

                // Get total of 10 dates from the max date
                const top10Dates = sortedDates.slice(0, 10);

                // Sort top 10 dates in ascending order
                const sortedTop10Dates = top10Dates.sort((a, b) => {
                    const dateA = new Date(a);
                    const dateB = new Date(b);
                    return dateA - dateB;
                });

                // Format dates using the convertDate function (assuming it's defined)
                const formattedDates = sortedTop10Dates.map((date) =>
                    convertDate(new Date(date))
                );

                // Update chart options
                chartOptions.value = {
                    ...chartOptions.value,
                    xaxis: {
                        ...chartOptions.value.xaxis,
                        categories: formattedDates,
                    },
                };

                // Update chart data
                chartData.value = {
                    series: [
                        {
                            name: "Count Alarm State",
                            data: sortedTop10Dates.map(
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

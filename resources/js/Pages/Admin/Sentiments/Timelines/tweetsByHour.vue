<template>
    <div class="col-12 shadow-border">
        <div class="col-12" id="tweets-by-hour">
            <h3 class="py-4 chart-heading" style="color: #000">
                Number of Tweets By Hour and Sentiments
            </h3>
        </div>
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
            :options="chartOptions"
            :series="series"
            type="bar"
            height="350"
        />
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import LoadingGif from "../../../../assets/loading.gif";
import { useFilterStore } from "../../../../stores/filter";

export default defineComponent({
    components: {},

    setup() {
        const loading = ref(true);

        const permissionError = ref("");

        const hours = ref([]);
        const filterStore = useFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);

        const search = ref({
            date: "",
            keywords: "",
            sentimentTypes: "",
        });
        const series = ref([
            {
                name: "Positive",
                data: [],
            },
            {
                name: "Neutral",
                data: [],
            },
            {
                name: "Negative",
                data: [],
            },
        ]);

        const chartOptions = ref({
            chart: {
                legend: {
                    show: true,
                    position: "bottom",
                },
                type: "bar",
                height: 350,
                stacked: true,

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
            colors: ["#00e396", "#775dd0", "#ff4560"],
            dataLabels: {
                enabled: false,
                name: {
                    show: false,
                },
                value: {
                    show: false,
                },
            },

            grid: {
                border: true,
                strokeDashArray: 3,
                show: false,
                padding: {
                    left: 0,
                    right: 0,
                },
                xaxis: {
                    lines: {
                        show: true,
                    },
                    min: 0,
                    max: 23,
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
                row: {
                    colors: undefined,
                    opacity: 0.1,
                },
                column: {
                    colors: undefined,
                    opacity: 0.1,
                },
            },
            responsive: [
                {
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: "bottom",
                        },
                    },
                },
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 1,
                    dataLabels: {
                        total: {
                            enabled: false,
                            style: {
                                fontSize: "13px",
                                fontWeight: 500,
                            },
                        },
                    },
                },
            },
            xaxis: {
                type: "numeric",
                tickAmount: 10,
                labels: {
                    formatter: function (val) {
                        if (hours.value[val - 1] !== undefined) {
                            return (
                                ("0" + hours.value[val - 1]).slice(-2) + ":00"
                            );
                        } else {
                            //  return val.toFixed(0) - 1 + ":00";
                            let convert = 0;
                            convert = val.toFixed(0) - 1;
                            return ("0" + convert).slice(-2) + ":00";
                        }
                    },
                    show: true,

                    rotate: -45,
                    rotateAlways: true,
                },
            },
            categories: hours.value,

            legend: {
                position: "top",
                offsetY: 40,
            },
        });
        const getData = async () => {
            try {
                const res = await axios.post(
                    `/admin/sentiments/timelines/tweets-by-hour`,
                    { searchFilter: search.value }
                );

                if (res.status === 200) {
                    const responseData = await res.data;
                    hours.value = await responseData.hours;
                    for await (const sentiment of responseData.data) {
                        series.value[0].data.push(sentiment.POSITIVE);
                        series.value[1].data.push(sentiment.NEUTRAL);
                        series.value[2].data.push(sentiment.NEGATIVE);
                    }
                    loading.value = false;
                }
            } catch (error) {
                if (error.response && error.response.status === 403) {
                    permissionError.value =
                        "Access forbidden: You do not have the necessary permissions. to view Timelines";
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
            hours.value = [];
            series.value[0].data = [];

            series.value[1].data = [];

            series.value[2].data = [];
            loading.value = true;

            getData();
        });

        return {
            series,
            chartOptions,
            getData,
            searchFilter,
            loading,
            LoadingGif,
            permissionError,
        };
    },
});
</script>

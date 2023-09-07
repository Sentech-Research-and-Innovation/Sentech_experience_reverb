<template>
    <div class="col-12 shadow">
        <div class="col-12">
            <h2 class="py-4">Number of Tweets By Hour and Sentiments</h2>
        </div>
        <apexchart
            :options="chartOptions"
            :series="series"
            type="bar"
            height="350"
        />
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";

export default defineComponent({
    components: {},
    props: ["filter"],

    setup(props) {
        const hours = ref([]);

        const searchFilter = ref({
            date: "",
            keywords: "",
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
                    show: true,
                    tools: {
                        download: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        reset: false,
                    },
                },
            },
            colors: ["#118dff", "#00c83c", "#ec1c24"],
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
                    left: -16,
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

                tickAmount: hours.value.length - 1,
                labels: {
                    formatter: function (val) {
                        return hours.value[val - 1];
                    },
                },
            },
            categories: hours.value,

            legend: {
                position: "top",
                offsetY: 40,
            },
            fill: {
                opacity: 1,
            },
        });

        const getData = async () => {
            const res = await axios.post(
                `/admin/sentiments/timelines/tweets-by-hour`,
                { searchFilter: searchFilter.value }
            );
            if (res.status === 200) {
                const responseData = await res.data;
                hours.value = await responseData.hours;
                console.log(hours.value);
                for await (const sentiment of responseData.data) {
                    series.value[0].data.push(sentiment.positive);
                    series.value[1].data.push(sentiment.neutral);
                    series.value[2].data.push(sentiment.negative);
                }
            }
        };

        watch(
            () => props.filter,
            (first, second) => {
                searchFilter.value = first;
                hours.value = [];
                series.value[0].data = [];

                series.value[1].data = [];

                series.value[2].data = [];

                getData();
            }
        );
        onMounted(async () => {
            getData();
        });

        return {
            series,
            chartOptions,
            getData,
            searchFilter,
        };
    },
});
</script>

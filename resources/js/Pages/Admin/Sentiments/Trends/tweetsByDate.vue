<template>
    <div class="col-12 shadow-sm mx-0 py-2">
        <div class="col-12">
            <h2 class="py-4">Number Of Tweets By Date And Sentiments</h2>
        </div>
        <div
            class="col-12 text-center"
            v-if="loading"
            style="height: 300px; padding-top: 100px"
        >
            <img :src="LoadingGif" width="50" />
        </div>

        <apexchart
            v-else
            height="100%"
            type="line"
            :options="chartUtil.chartOptions"
            :series="seriesData"
        ></apexchart>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import chartUtil from "../../../../utils/sentimentsTimelineChart";
import LoadingGif from "../../../../assets/loading.gif";

export default defineComponent({
    name: "sentiment-analysis-over-sentimentsTimelineChart",

    components: {},

    setup() {
        const loading = ref(true);
        const chartData = ref({
            negative: [],
            positive: [],
            neutral: [],
        });
        const seriesData = [
            {
                type: "line",
                name: "Negative",
                data: chartData.value.negative,
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
                type: "line",
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
                data: chartData.value.positive,
            },
            {
                type: "line",
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
                data: chartData.value.neutral,
            },
        ];

        const getData = async () => {
            const res = await axios.post(
                `/admin/sentiments/overview/sentimentsTimeline`,
                { searchFilter: { date: null, keywords: null } }
            );
            if (res.status === 200) {
                for (const key in res.data) {
                    const monthData = res.data[key];
                    chartData.value["negative"].push({
                        x: monthData.month + " " + monthData.year,
                        y: monthData.sentiments.negative,
                    });
                    chartData.value["positive"].push({
                        x: monthData.month + " " + monthData.year,
                        y: monthData.sentiments.positive,
                    });
                    chartData.value["neutral"].push({
                        x: monthData.month + " " + monthData.year,
                        y: monthData.sentiments.neutral,
                    });
                }
                loading.value = false;
            }
        };

        onMounted(async () => {
            getData();
        });

        return {
            chartData,
            getData,
            seriesData,
            chartUtil,
            loading,
            LoadingGif,
        };
    },
});
</script>

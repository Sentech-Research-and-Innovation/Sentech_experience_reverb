<template>
    <div class="col-12 shadow mx-0">
        <div class="col-12">
            <h2 class="text-primary py-4">Sentiments Timeline (cumulative)</h2>
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
            style="height: 200px !important"
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
                type: "area",
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
                data: chartData.value.positive,
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
                data: chartData.value.neutral,
            },
        ];

        const getData = async () => {
            const res = await axios.get(
                `/admin/sentiments/overview/sentimentsTimeline`
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

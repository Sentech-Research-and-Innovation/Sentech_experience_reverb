<template>
    <div class="row mx-0">
        <div class="col-12"><h2>Analytics Overview</h2></div>
    </div>

    <div class="d-flex justify-content-around">
        <div class="col-9 pt-4 mx-0 px-0">
            <div class="d-flex justify-content-between">
                <div class="col-4 shadow text-center py-4">
                    <h1 class="text-secondary">
                        {{ overallData.totalTweets }}
                    </h1>
                    <p class="text-grey">Total Tweets</p>
                </div>
                <div class="col-4 shadow text-center py-4">
                    <h1 class="text-success">
                        {{ overallData.positiveTweets }}
                    </h1>
                    <p class="text-grey">Positive Tweets</p>
                </div>
                <div class="col-4 shadow text-center py-4">
                    <h1 class="text-danger">
                        {{ overallData.negativeTweets }}
                    </h1>
                    <p class="text-grey">Negavite Tweets</p>
                </div>
            </div>
        </div>
        <div class="col-3 mx-0 px-0">
            <apexchart :options="chartOptions" :series="series" type="pie" />
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    name: "sentiment-analysis-overall-sentiments",

    components: {},

    setup() {
        const overallData = ref([]);
        const chartOptions = ref({
            chart: {
                type: "pie",
            },
            colors: ["#00c83c", "#118dff", "#ec1c24"],
            labels: ["Positive", "Neutral", "Negative"],
            legend: {
                position: "bottom",
            },
        });

        const series = ref([]);

        const getData = async () => {
            const res = await axios.get(
                `/admin/sentiments/overview/overall-sentiments`
            );

            if (res.status === 200) {
                overallData.value = res.data;
                series.value = [
                    overallData.value.positiveTweets,
                    overallData.value.neutralTweets,
                    overallData.value.negativeTweets,
                ];
            }
        };

        onMounted(async () => {
            getData();
        });
        return {
            overallData,
            chartOptions,
            series,
        };
    },
});
</script>

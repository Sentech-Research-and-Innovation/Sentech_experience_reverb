<template>
    <div class="d-flex justify-content-around">
        <div class="col-9 pt-4 mx-0 px-0">
            <div v-if="overallData" class="d-flex justify-content-between">
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
                    <p class="text-grey">Negative Tweets</p>
                </div>
            </div>
            <div v-else><img :src="LoadingGif" width="50" /></div>
        </div>
        <div class="col-3 mx-0 px-0">
            <apexchart :options="chartOptions" :series="series" type="pie" />
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import LoadingGif from "../../../../assets/loading.gif";

export default defineComponent({
    name: "sentiment-analysis-overall-sentiments",

    components: {},
    props: ["filter"],
    setup(props) {
        const searchFilter = ref({
            date: "",
            keywords: "",
        });
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
            const res = await axios.post(
                `/admin/sentiments/overview/overall-sentiments`,
                { searchFilter: searchFilter.value }
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
            }
        };

        onMounted(async () => {
            getData();
        });

        watch(
            () => props.filter,
            (first, second) => {
                // console.log(
                //     "Watch props.selected function called with args:",
                //     first,
                //     second
                // )

                searchFilter.value = first;
                overallData.value.totalTweets = null;
                overallData.value.positiveTweets = null;
                overallData.value.neutralTweets = null;
                overallData.value.negativeTweets = null;
                series.value = [
                    overallData.value.positiveTweets,
                    overallData.value.neutralTweets,
                    overallData.value.negativeTweets,
                ];

                getData();
            }
        );
        return {
            overallData,
            chartOptions,
            series,
            searchFilter,
            LoadingGif,
        };
    },
});
</script>

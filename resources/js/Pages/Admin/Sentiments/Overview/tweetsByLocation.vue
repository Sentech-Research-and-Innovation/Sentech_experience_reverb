<template>
    <div class="col-12 shadow py-4 px-0 mx-0">
        <div class="col-12">
            <h2 class="text-primary">Number Of Tweets By Location</h2>
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
            class="py-5"
            type="donut"
            :options="chartOptions"
            :series="values"
        ></apexchart>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import LoadingGif from "../../../../assets/loading.gif";

export default defineComponent({
    name: "sentiment-analysis-over-tweets-by-location",

    components: {},

    setup() {
        const loading = ref(true);

        const places = ref([]);
        const values = ref([]);

        const chartOptions = ref({
            chart: {
                type: "donut",
                width: 300,
            },
            tooltip: {
                enabled: true,
            },
            labels: places.value,
            legend: {
                position: "right",
            },
        });

        const getData = async () => {
            const res = await axios.get(
                `/admin/sentiments/overview/tweets-by-location`
            );

            if (res.status === 200) {
                let isFirstValue = true;
                for (const place in res.data) {
                    if (res.data.hasOwnProperty(place)) {
                        if (isFirstValue) {
                            isFirstValue = false;
                            continue;
                        }
                        places.value.push(place);
                        values.value.push(res.data[place]);
                    }
                }
            }
            loading.value = false;
        };

        onMounted(async () => {
            getData();
        });
        return {
            chartOptions,
            values,
            loading,
            LoadingGif,
        };
    },
});
</script>

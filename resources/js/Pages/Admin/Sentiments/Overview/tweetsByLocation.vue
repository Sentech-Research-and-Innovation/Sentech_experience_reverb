<template>
    <div class="col-12 shadow py-4 px-0 mx-0">
        <div class="col-12">
            <h2 class="">Number Of Tweets By Location</h2>
        </div>
        <div
            class="col-12 text-center"
            v-if="loading"
            style="height: 300px; padding-top: 100px"
        >
            <img :src="LoadingGif" width="50" />
        </div>
        <apexchart
            class="py-5"
            type="donut"
            :options="chartOptions"
            :series="values"
        ></apexchart>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch } from "vue";
import LoadingGif from "../../../../assets/loading.gif";

export default defineComponent({
    name: "sentiment-analysis-over-tweets-by-location",
    props: ["filter"],
    components: {},
    setup(props) {
        const loading = ref(true);
        const searchFilter = ref({
            date: "",
            keywords: "",
        });

        const labels = ref([]);
        const values = ref([]);

        const chartOptions = ref({
            chart: {
                type: "donut",
                width: 300,
            },
            tooltip: {
                enabled: true,
            },
            labels: [],
            legend: {
                position: "right",
            },
        });

        const getData = async () => {
            const res = await axios.post(
                `/admin/sentiments/overview/tweets-by-location`,
                { searchFilter: searchFilter.value }
            );

            if (res.status === 200) {
                //  let isFirstValue = true;

                for (const place in res.data) {
                    if (res.data.hasOwnProperty(place)) {
                        // if (isFirstValue) {
                        //     isFirstValue = false;
                        //     continue;
                        // }
                        // places.value.push(place);

                        chartOptions.value.labels.push(place);
                        values.value.push(res.data[place]);
                    }
                }
            }
            loading.value = false;
        };

        onMounted(async () => {
            getData();
        });

        watch(
            () => props.filter,
            (first, second) => {
                searchFilter.value = first;
                chartOptions.value.labels.length = 0;
                //  location.reload();
                values.value = [];
                getData();
            }
        );
        return {
            chartOptions,
            values,
            loading,
            LoadingGif,
            searchFilter,
        };
    },
});
</script>

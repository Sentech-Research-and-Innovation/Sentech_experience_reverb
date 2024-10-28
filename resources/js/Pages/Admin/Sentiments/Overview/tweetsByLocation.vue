<template>
    <div
        v-if="permissionError"
        class="col-12 text-center shadow-border"
        style="height: 500px; padding-top: 170px; color: red"
    >
        {{ permissionError }}
    </div>
    <div
        v-else
        class="col-12 shadow-border"
        id="tweets-location"
        style="min-height: 500px"
    >
        <h3 class="py-4 chart-heading">Number Of Tweets By Location</h3>

        <div
            class="col-12 text-center"
            v-if="loading"
            style="padding-top: 10px"
        >
            <img :src="LoadingGif" width="50" />
        </div>

        <apexchart
            height="400"
            type="donut"
            :options="chartOptions"
            :series="values"
        ></apexchart>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import LoadingGif from "../../../../assets/loading.gif";
import { useFilterStore } from "../../../../stores/filter";

export default defineComponent({
    name: "sentiment-analysis-over-tweets-by-location",
    components: {},
    setup() {
        const filterStore = useFilterStore();

        const loading = ref(true);

        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            date: "",
            keywords: "",
            sentimentTypes: "",
        });

        const labels = ref([]);
        const values = ref([]);

        const chartOptions = ref({
            chart: {
                type: "donut",
                width: 350,
                height: 350,
            },

            stroke: {
                show: false,
            },
            tooltip: {
                enabled: true,
            },
            labels: [],

            legend: {
                position: "right",
            },
        });
        const permissionError = ref("");
        const getData = async () => {
            try {
                const res = await axios.post(
                    `/admin/sentiments/overview/tweets-by-location`,
                    { searchFilter: search.value }
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
                            const label = place === "" ? "Unknown" : place;

                            chartOptions.value.labels.push(label);
                            //   chartOptions.value.labels.push(place);
                            values.value.push(res.data[place]);
                        }
                    }
                }
                loading.value = false;
            } catch (error) {
                if (error.response && error.response.status === 403) {
                    permissionError.value =
                        "Access forbidden: You do not have the necessary permissions. to view Overview Data";
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
            chartOptions.value.labels.length = 0;
            //  location.reload();
            values.value = [];
            loading.value = true;

            getData();
        });
        return {
            chartOptions,
            values,
            loading,
            LoadingGif,
            searchFilter,
            search,
            permissionError,
        };
    },
});
</script>

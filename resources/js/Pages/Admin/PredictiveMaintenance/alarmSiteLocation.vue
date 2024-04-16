<template>
    <div class="mx-3 py-4 shadow-border my-3">
        <div class="col-12 mx-0">
            <h4>Sensors in alarms by Site Location</h4>
            <p style="color: #86abd1">
                Showing top 3 in Longitude (#), Latitude (#) and top 3 in
                Sitename
            </p>
        </div>
        <div class="col-12 mt-4 mx-0 px-0" v-if="markers.length > 0">
            <vuevectormap
                v-if="dataLoaded"
                width="100%"
                height="290"
                :options="{
                    markers,
                    markerStyle,
                    labels,
                }"
            >
            </vuevectormap>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import { predictionsFilterStore } from "../../../stores/predictionsFilter";

export default defineComponent({
    components: {},

    setup() {
        const dataLoaded = ref(false);

        const markers = ref([]);

        const labels = ref({
            markers: {
                render(marker, index) {
                    return marker.labelName;
                },
            },
        });

        const filterStore = predictionsFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            searchFilter,
        });

        const predictions = ref([]);

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

        onMounted(async () => {
            getPredictions();
        });

        const markerStyle = ref({
            initial: {
                fontFamily: "'Inter', sans-serif",
                fontSize: 2,
                border: "none",
                fontWeight: 300,
                fill: "#144f9f",
                strokeWidth: 1,
                r: 8,
            },
            hover: {
                fill: "black",
            },
            selected: {
                fill: "blue",
            },
            selectedMarkers: [0],
        });

        watch(searchFilter, (newFilter, oldFilter) => {
            const { siteNames, date } = newFilter;
            search.value = {
                searchFilter: {
                    siteNames: siteNames,
                    date: date,
                },
            };
            //   chartData.value = [];
            //  chartOptions.value = [];
            dataLoaded.value = false;

            getPredictions();
        });

        // Function to calculate target values by SiteName for the last 7 days
        watch(predictions, (newPredictions) => {
            if (newPredictions) {
                const currentDate = new Date(); // Current date and time
                const maxDate = new Date(
                    Math.max(
                        ...newPredictions.map(
                            (prediction) => new Date(prediction.date)
                        )
                    )
                );

                // Calculate the start date for the 7-day range
                const startDate = new Date(maxDate);
                startDate.setDate(startDate.getDate() - 300);

                const siteTotals = {}; // Object to store totals by siteName

                // Iterate through newPredictions and calculate totals for the last 7 days where alarm is 0
                newPredictions.forEach((prediction) => {
                    const predictionDate = new Date(prediction.date);
                    const siteName = prediction.SiteName;
                    const itemId = prediction.item_id;
                    const alarm = prediction.alarm;

                    // Check if siteName exists, date is within the 7-day range, itemId is unique, and alarm is 0
                    if (
                        siteName &&
                        predictionDate >= startDate &&
                        predictionDate <= maxDate &&
                        alarm === 0
                    ) {
                        siteTotals[siteName] =
                            siteTotals[siteName] || new Set();
                        siteTotals[siteName].add(itemId);
                    }
                });

                // Convert siteTotals object to markers array
                markers.value = Object.keys(siteTotals).map((siteName) => {
                    const totalItems = siteTotals[siteName].size;
                    const predictionWithCoordinates = newPredictions.find(
                        (prediction) => prediction.SiteName === siteName
                    );
                    const latitude = parseFloat(
                        predictionWithCoordinates.Latitude
                    );
                    const longitude = parseFloat(
                        predictionWithCoordinates.Longitude
                    );

                    return {
                        name: `7 days Sensor alarm : ${totalItems}`,
                        coords: [latitude, longitude],
                        labelName: siteName,
                    };
                });
            }
        });

        return {
            predictions,
            markerStyle,
            markers,
            labels,
            dataLoaded,
        };
    },
});
</script>

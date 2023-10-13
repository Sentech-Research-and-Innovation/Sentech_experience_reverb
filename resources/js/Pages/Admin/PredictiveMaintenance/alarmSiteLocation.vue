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
                backgroundColor="#0000"
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
                fill: "",
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
                const siteTargets = {};

                // Find the maximum date from predictions data
                const maxDate = new Date(
                    Math.max(
                        ...predictions.value.map(
                            (prediction) => new Date(prediction.date)
                        )
                    )
                );

                // Subtract 7 days from the maximum date to get the start date for the 7-day range
                const startDate = new Date(maxDate);
                startDate.setDate(startDate.getDate() - 7);

                // Iterate through predictions and calculate target values for the last 7 days
                predictions.value.forEach((prediction) => {
                    const siteName = prediction.SiteName;
                    const targetValue = parseFloat(prediction.target_value);
                    const latitude = parseFloat(prediction.Latitude_);
                    const longitude = parseFloat(prediction.Longitude_);
                    const predictionDate = new Date(prediction.date);

                    if (
                        siteName &&
                        !isNaN(targetValue) &&
                        !isNaN(latitude) &&
                        !isNaN(longitude) &&
                        predictionDate >= startDate &&
                        predictionDate <= maxDate
                    ) {
                        if (siteTargets[siteName]) {
                            siteTargets[siteName].targetValue += targetValue;
                        } else {
                            siteTargets[siteName] = {
                                targetValue,
                                coords: [latitude, longitude],
                                labelName: siteName,
                            };
                        }
                    }
                });

                // Convert siteTargets object to markers array
                markers.value = Object.keys(siteTargets).map((siteName) => ({
                    name: `7 days Sensor alarm : ${siteTargets[
                        siteName
                    ].targetValue.toFixed(2)}`,
                    coords: siteTargets[siteName].coords, // Use actual latitude and longitude
                    labelName: siteName,
                }));
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

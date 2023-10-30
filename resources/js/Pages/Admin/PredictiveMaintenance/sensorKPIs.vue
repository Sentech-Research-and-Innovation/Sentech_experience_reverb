<template>
    <div class="row mt-3">
        <div class="col-lg-4 col-6 pr-0">
            <div class="col-12 shadow-border text-center py-3">
                <p class="pt-lg-4 fs-5">Last Refresh Date</p>
                <div class="py-lg-2 value_label">
                    {{ formattedDate }}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6 pr-lg-0">
            <div class="col-12 shadow-border text-center py-3">
                <p class="pt-lg-4 fs-5">Monitored Sensors</p>
                <div class="py-lg-2 value_label">
                    {{ monitoredSensorsCount }}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mt-lg-0 mt-3">
            <div class="col-12 shadow-border text-center py-1">
                <p class="pt-2 fs-5">7 day In-Alarm sensor count</p>
                <div class="pt-2">
                    <apexchart
                        type="radialBar"
                        height="190"
                        :options="chartOptions"
                        :series="series"
                    ></apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, watch, computed } from "vue";
import { predictionsFilterStore } from "../../../stores/predictionsFilter";

export default defineComponent({
    components: {},

    setup() {
        const filterStore = predictionsFilterStore();
        const searchFilter = computed(() => filterStore.searchFilter);
        const search = ref({
            searchFilter,
        });

        const monitoredSensorsCount = ref(0);

        const inAlarmMonitoredSensorsCount = ref(0);

        const formattedDate = ref("");
        const series = ref([]);

        const chartOptions = ref({
            colors: ["#144f9f"],

            chart: {
                type: "radialBar",
                offsetY: -20,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                show: false,
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#e7e7e7",
                        strokeWidth: "100%",
                        margin: 0, // margin is in pixels
                    },
                    dataLabels: {
                        show: true,
                        name: {
                            show: false,
                        },
                        colors: ["#144f9f"],
                        value: {
                            offsetY: -2,
                            fontSize: "22px",

                            color: "#144f9f",
                            show: true,
                        },
                    },
                },
            },

            grid: {
                padding: {
                    top: -10,
                },
            },
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

                const predictionsData = res.data;

                // Use a Set to store unique item IDs
                const uniqueItemIds = new Set();
                const inAlarmuniqueItemIds = new Set();

                // Iterate through predictionsData and add unique item IDs to the Set
                predictionsData.forEach((prediction) => {
                    uniqueItemIds.add(prediction.item_id);
                    if (prediction.alarm == 0) {
                        inAlarmuniqueItemIds.add(prediction.item_id);
                    }
                });

                predictions.value = predictionsData;
                monitoredSensorsCount.value = uniqueItemIds.size;
                inAlarmMonitoredSensorsCount.value = inAlarmuniqueItemIds.size;

                let percentage =
                    (inAlarmMonitoredSensorsCount.value /
                        monitoredSensorsCount.value) *
                    100;
                series.value.push(percentage.toFixed(0));
            } else {
                predictions.value = [];
                monitoredSensorsCount.value = 0;
            }
        };

        onMounted(async () => {
            const optionsDate = {
                year: "numeric",
                month: "short",
                day: "numeric",
            };
            const currentDate = new Date();
            formattedDate.value = currentDate.toLocaleDateString(
                "en-US",
                optionsDate
            );

            getPredictions();
        });

        watch(searchFilter, (newFilter, oldFilter) => {
            const { siteNames, date } = newFilter;
            search.value = {
                searchFilter: {
                    siteNames: siteNames,
                    date: date,
                },
            };
            series.value = [];
            getPredictions();
        });
        return {
            series,
            chartOptions,
            formattedDate,
            monitoredSensorsCount,
            inAlarmMonitoredSensorsCount,
        };
    },
});
</script>

<style>
.value_label {
    font-size: 40px;
    font-weight: bold;
}
@media only screen and (max-width: 600px) {
    .value_label {
        font-size: 15px;
        font-weight: bold;
    }
}
</style>

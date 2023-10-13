<template>
    <div class="row mt-3">
        <div class="col-lg-4 col-6 pr-0">
            <div class="col-12 shadow-border text-center py-3">
                <p class="pt-lg-4">Last Refresh Date</p>
                <div class="py-lg-2 value_label">
                    {{ formattedDate }}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6 pr-lg-0">
            <div class="col-12 shadow-border text-center py-3">
                <p class="pt-lg-4">Monitored Sensors</p>
                <div class="py-lg-2 value_label">
                    {{ monitoredSensorsCount }}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mt-lg-0 mt-3">
            <div class="col-12 shadow-border text-center py-1">
                <p class="pt-2">7 day In-Alarm sensor count</p>
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

        const formattedDate = ref("");
        const series = ref([68]);

        const chartOptions = ref({
            colors: ["#144f9f"],

            chart: {
                type: "radialBar",
                offsetY: -20,
                sparkline: {
                    enabled: true,
                },
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
                monitoredSensorsCount.value = predictions.value.length;
            } else {
                predictions.value = [];
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

            getPredictions();
        });

        return { series, chartOptions, formattedDate, monitoredSensorsCount };
    },
});
</script>

<style>
.value_label {
    font-size: 30px;
}
@media only screen and (max-width: 600px) {
    .value_label {
        font-size: 15px;
        font-weight: bold;
    }
}
</style>

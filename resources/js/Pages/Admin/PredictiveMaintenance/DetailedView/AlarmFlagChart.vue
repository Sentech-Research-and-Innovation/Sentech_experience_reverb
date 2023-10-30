<template>
    <div class="col-12 shadow-border text-center py-1">
        <div class="pt-4">
            <apexchart
                type="donut"
                height="178px"
                :options="chartOptions"
                :series="series"
            ></apexchart>
        </div>
    </div>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";

export default defineComponent({
    components: {},
    setup() {
        const series = ref([]);

        const chartOptions = ref({
            colors: ["#41e809", "#e80909", "#ffc107"],
            labels: ["Normal", "Alarm", "Pre-Alarm"],

            chart: {
                type: "donut",
            },
            stroke: {
                show: false,
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            name: {
                                show: false,
                            },
                            colors: ["#144f9f"],
                            value: {
                                offsetY: 9,
                                fontSize: "22px",

                                color: "#144f9f",
                                show: true,
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: "Total",
                            },
                        },
                    },
                },
            },
        });

        const getAlarmData = async () => {
            try {
                const response = await axios.post(
                    "/admin/predictive-maintenance/predictions/alarm-flag"
                );

                if (response.status === 200) {
                    series.value = [
                        response.data.normal_count,
                        response.data.alarm_count,
                        response.data.preAlarm_count,
                    ];
                }
            } catch (error) {
                console.error(error);
            }
        };

        onMounted(() => {
            getAlarmData();
        });
        return {
            series,
            chartOptions,
        };
    },
});
</script>

<template>
    <div class="col-12 shadow-border mt-4">
        <div class="col-12 pt-4">
            <h4>Count of sensors In Alarm state by Date</h4>
            <p>Showing top 10 in date</p>
        </div>
        <div v-if="chartData">
            <apexchart
                type="bar"
                :options="chartOptions"
                :series="chartData.series"
            ></apexchart>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
export default defineComponent({
    components: {},
    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { predictions } = props;

        // Process the data to calculate counts of unique item_id values for each date
        const uniqueItemCountByDate = predictions.reduce(
            (counts, prediction) => {
                const date = prediction.date;
                const item_id = prediction.item_id;
                counts[date] = counts[date] || new Set();
                counts[date].add(item_id);
                return counts;
            },
            {}
        );

        // Sort the dates by the count of unique item_id values in descending order
        const sortedDates = Object.keys(uniqueItemCountByDate).sort(
            (a, b) =>
                uniqueItemCountByDate[b].size - uniqueItemCountByDate[a].size
        );

        // Select the top 10 dates
        const top10Dates = sortedDates.slice(0, 10);
        const chartOptions = ref({
            chart: {
                toolbar: {
                    show: false,
                    tools: {
                        download: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        reset: false,
                    },
                },
            },
            dataLabels: {
                enabled: false,
                name: {
                    show: false,
                },
                value: {
                    show: false,
                },
            },
            xaxis: {
                categories: top10Dates,
            },
            colors: ["#144f9f"],
        });
        // Create the chart data
        const chartData = {
            series: [
                {
                    name: "Count Alarm State",
                    data: top10Dates.map(
                        (date) => uniqueItemCountByDate[date].size
                    ),
                },
            ],
        };

        return {
            predictions,
            chartOptions,
            chartData,
        };
    },
});
</script>

<style></style>

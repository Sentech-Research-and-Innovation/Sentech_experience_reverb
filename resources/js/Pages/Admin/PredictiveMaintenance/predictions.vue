<template>
    <div class="col-12">
        <h1>National Site Data</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item ID</th>
                    <th>Date</th>
                    <th>Target Value</th>
                    <th>Alarm</th>
                    <th>Site Name</th>
                    <th>Province</th>
                    <th>Device Name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="prediction in predictions" :key="prediction.id">
                    <td>{{ prediction.id }}</td>
                    <td>{{ prediction.item_id }}</td>
                    <td>{{ prediction.date }}</td>
                    <td>{{ prediction.target_value }}</td>
                    <td>{{ prediction.alarm }}</td>
                    <td>{{ prediction.SiteName }}</td>
                    <td>{{ prediction.Province }}</td>
                    <td>{{ prediction.DeviceName }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    layout: AdminLayout,
    components: {},
    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { predictions } = props;
        return {
            predictions,
        };
    },
});
</script>

<style>
/* Add your styling here */
</style>

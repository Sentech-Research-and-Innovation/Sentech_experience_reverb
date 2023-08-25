<template>
    <div class="col-12">
        <h1>Device device Data</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hostname</th>
                    <th>OID</th>
                    <th>OID Index</th>
                    <th>Device Measure Name</th>
                    <th>Lower Pre-Alarm Threshold</th>
                    <th>Upper Pre-Alarm Threshold</th>
                    <th>Lower Alarm Threshold</th>
                    <th>Upper Alarm Threshold</th>
                    <th>Discrete Values</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="device in devices" :key="device.id">
                    <td>{{ device.id }}</td>
                    <td>{{ device.hostname }}</td>
                    <td>{{ device.oid }}</td>
                    <td>{{ device.oidIndex }}</td>
                    <td>{{ device.DeviceMeasureName }}</td>
                    <td>{{ device.lowerPreAlarmTsh }}</td>
                    <td>{{ device.upperPreAlarmTsh }}</td>
                    <td>{{ device.lowerAlarmTsh }}</td>
                    <td>{{ device.upperAlarmTsh }}</td>
                    <td>{{ device.discreteValues }}</td>
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
        devices: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { devices } = props;
        return {
            devices,
        };
    },
});
</script>

<style>
/* Add your styling here */
</style>

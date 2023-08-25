<template>
    <div>
        <h1>Alarm Data</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Site Name</th>
                    <th>Site Code</th>
                    <th>Classification</th>
                    <th>Operations Center</th>
                    <th>Region</th>
                    <th>Province</th>
                    <th>Device Name</th>
                    <th>Device IP</th>
                    <th>Event In DateTime</th>
                    <th>Event Out DateTime</th>
                    <th>Alarm Description</th>
                    <th>Raw Value</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="alarm in alarms" :key="alarm.id">
                    <td>{{ alarm.id }}</td>
                    <td>{{ alarm.SiteName }}</td>
                    <td>{{ alarm.SiteCode }}</td>
                    <td>{{ alarm.Classification }}</td>
                    <td>{{ alarm.OC }}</td>
                    <td>{{ alarm.Region }}</td>
                    <td>{{ alarm.Province }}</td>
                    <td>{{ alarm.DeviceName }}</td>
                    <td>{{ alarm.DeviceIP }}</td>
                    <td>{{ alarm.EventInDateTime }}</td>
                    <td>{{ alarm.EventOutDateTime }}</td>
                    <td>{{ alarm.AlarmDescription }}</td>
                    <td>{{ alarm.RawValue }}</td>
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
        alarms: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { alarms } = props;
        return {
            alarms,
        };
    },
});
</script>

<style>
/* Add your styling here */
</style>

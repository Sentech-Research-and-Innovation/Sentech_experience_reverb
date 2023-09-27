<template>
    <div class="row mx-0 px-0">
        <div class="col-6 mx-0 px-0">
            <alarmSiteLocation :predictions="predictions" />
        </div>
        <div class="col-6 px-0 mx-0">
            <alarmState :predictions="predictions" />
        </div>
    </div>
    <predictionsVue :predictions="predictions" />
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import predictionsVue from "./predictions.vue";

import alarmState from "./alarmStateByDate.vue";
import alarmSiteLocation from "./alarmSiteLocation.vue";
export default defineComponent({
    layout: AdminLayout,
    components: {
        predictionsVue,
        alarmState,
        alarmSiteLocation,
    },
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

<style></style>

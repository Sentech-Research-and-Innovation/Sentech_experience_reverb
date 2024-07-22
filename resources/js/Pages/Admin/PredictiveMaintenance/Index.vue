<template>
    <navigationTabsVue />
    <div class="col-12 mt-3">
        <filtersVue />
    </div>
    <div class="col-12 mt-3">
        <sensorKPIsVue :lastRefresh="lastRefresh" />
    </div>
    <div class="row">
        <div class="col-lg-6 col-12 pr-lg-0">
            <alarmSiteLocation :predictions="predictions" />
        </div>
        <div class="col-lg-6 col-12 pr-lg-4 pl-lg-0 px-4">
            <alarmState :predictions="predictions" />
        </div>
    </div>

    <div class="col-12">
        <predictionsVue :predictions="predictions" />
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import navigationTabsVue from "../../../Layouts/predictions/navigationTabs.vue";

import predictionsVue from "./predictions.vue";

import alarmState from "./alarmStateByDate.vue";
import alarmSiteLocation from "./alarmSiteLocation.vue";
import filtersVue from "../../../Layouts/predictions/filter.vue";
import sensorKPIsVue from "./sensorKPIs.vue";
export default defineComponent({
    layout: AdminLayout,
    components: {
        predictionsVue,
        alarmState,
        alarmSiteLocation,
        filtersVue,
        sensorKPIsVue,
        navigationTabsVue,
    },
    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { predictions } = props;

        const lastRefresh = predictions[0].date;

        return {
            predictions,
            lastRefresh,
        };
    },
});
</script>

<style></style>

<template>
    <div class="row mt-3">
        <div class="col-8 mx-0">
            <div class="row">
                <div class="col-6 mb-3 pr-0">
                    <div
                        class="col-12 shadow-border py-2"
                        style="min-height: 100px"
                    >
                        Please use the filters to narrow down to relevant
                        devices and sensors. The table below contains predicted
                        sensor values along with the thresholds for a 7 day
                        period.
                    </div>
                </div>

                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Site Name</div>

                        <el-cascader
                            :options="siteNameOptions"
                            :props="propsSiteNames"
                            v-model="siteNameModel"
                            collapse-tags
                            collapse-tags-tooltip
                            :max-collapse-tags="0"
                            clearable
                        />
                    </div>
                </div>
                <div class="col-3 mb-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Measure Description</div>
                        <el-cascader
                            :options="measuresOptions"
                            :props="props2"
                            v-model="measuresModel"
                            collapse-tags
                            collapse-tags-tooltip
                            :max-collapse-tags="0"
                            clearable
                        />
                    </div>
                </div>

                <div class="col-3 mb-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Device Name</div>

                        <el-cascader
                            :options="deviceNameOptions"
                            :props="props2"
                            v-model="deviceNameModel"
                            collapse-tags
                            collapse-tags-tooltip
                            :max-collapse-tags="0"
                            clearable
                        />
                    </div>
                </div>

                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Classification</div>

                        <el-cascader
                            :options="classificationOptions"
                            :props="props2"
                            v-model="classificationModel"
                            collapse-tags
                            collapse-tags-tooltip
                            :max-collapse-tags="0"
                            clearable
                        />
                    </div>
                </div>

                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Alarm Flag</div>
                        <el-cascader
                            :options="alarmFlagOptions"
                            :props="props2"
                            v-model="alarmFlagModel"
                            collapse-tags
                            collapse-tags-tooltip
                            :max-collapse-tags="0"
                            clearable
                        />
                    </div>
                </div>

                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="height: 100px"
                    >
                        <div class="pb-2">Date between</div>
                        <el-date-picker
                            v-model="inputdate"
                            type="daterange"
                            range-separator="To"
                            start-placeholder="Start date"
                            end-placeholder="End date"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <AlarmFlagChartVue />
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, watch, onMounted, nextTick } from "vue";
import {VueDatePicker} from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import { predictionsFilterStore } from "../../stores/predictionFiltersDetailed";

import AlarmFlagChartVue from "../../Pages/Admin/PredictiveMaintenance/DetailedView/AlarmFlagChart.vue";

export default defineComponent({
    components: {
        VueDatePicker,
        AlarmFlagChartVue,
    },

    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const props2 = { multiple: true };
        const propsSiteNames = { multiple: true };

        const { predictions } = props;

        const filterStore = predictionsFilterStore();

        const measuresOptions = ref([]);
        const measuresModel = ref(filterStore.measureDecription);

        const inputdate = ref(filterStore.date);

        const siteNameModel = ref(filterStore.siteNames);
        const siteNameOptions = ref([]);

        const deviceNameModel = ref(filterStore.deviceName);
        const deviceNameOptions = ref([]);

        const classificationModel = ref(filterStore.classification);
        const classificationOptions = ref([]);

        const alarmFlagModel = ref(filterStore.alarmFlag);
        const alarmFlagOptions = ref([]);

        watch(siteNameModel, (newFilter, oldFilter) => {
            filterStore.siteNames = newFilter;
        });

        watch(measuresModel, (newFilter, oldFilter) => {
            filterStore.measureDecription = newFilter;
        });

        watch(deviceNameModel, (newFilter, oldFilter) => {
            filterStore.deviceName = newFilter;
        });

        watch(inputdate, (newDate, oldDate) => {
            filterStore.date = oldDate;
        });

        watch(classificationModel, (newClassification, oldClassification) => {
            filterStore.classification = newClassification;
        });
        watch(alarmFlagModel, (newAlarmFlag, oldAlarmFlag) => {
            filterStore.alarmFlag = newAlarmFlag;
        });

        // watch(
        //     [
        //         siteNameModel,
        //         measuresModel,
        //         deviceNameModel,
        //         inputdate,
        //         classificationModel,
        //         alarmFlagModel,
        //     ],
        //     () => {
        //         filterStore.siteNames = siteNameModel.value;
        //         filterStore.measureDecription = measuresModel.value;
        //         filterStore.deviceName = deviceNameModel.value;
        //         filterStore.date = inputdate.value;
        //         filterStore.classification = classificationModel.value;
        //         filterStore.alarmFlag = alarmFlagModel.value;
        //     }
        // );

        const setFiltersOptions = async () => {
            await nextTick();
            const seenSiteNames = {};
            const seenMeasures = {};
            const seenDeviceNames = {};
            const seenClassification = {};
            const seenAlarmFlag = {};
            predictions.forEach((item) => {
                const siteName = item.SiteName;
                const measure = item.MeasureDescription;
                const deviceName = item.DeviceName;
                const classification = item.Classification_x;
                const alarmFlag = item.alarm;

                if (!seenSiteNames[siteName]) {
                    seenSiteNames[siteName] = true;
                    siteNameOptions.value.push({
                        value: siteName,
                        label: siteName,
                    });
                }

                if (!seenMeasures[measure]) {
                    seenMeasures[measure] = true;
                    measuresOptions.value.push({
                        value: measure,
                        label: measure,
                    });
                }

                if (!seenDeviceNames[deviceName]) {
                    seenDeviceNames[deviceName] = true;
                    deviceNameOptions.value.push({
                        value: deviceName,
                        label: deviceName,
                    });
                }

                if (!seenClassification[classification]) {
                    seenClassification[classification] = true;
                    classificationOptions.value.push({
                        value: classification,
                        label: classification,
                    });
                }

                if (!seenAlarmFlag[alarmFlag]) {
                    seenAlarmFlag[alarmFlag] = true;
                    let label = "";
                    if (alarmFlag === 0) {
                        label = "Alarm";
                    } else if (alarmFlag === 1) {
                        label = "Normal";
                    } else {
                        label = "Pre-Alarm";
                    }
                    alarmFlagOptions.value.push({
                        value: alarmFlag,
                        label: label,
                    });
                }
            });

            filterStore.siteNames = siteNameOptions.value.map(
                (option) => option.value
            );
            filterStore.measureDecription = measuresOptions.value.map(
                (option) => option.value
            );
            filterStore.deviceName = deviceNameOptions.value.map(
                (option) => option.value
            );
            filterStore.classification = classificationOptions.value.map(
                (option) => option.value
            );
            filterStore.alarmFlag = alarmFlagOptions.value.map(
                (option) => option.value
            );
        };

        onMounted(() => {
            setFiltersOptions();
        });

        return {
            siteNameOptions,
            siteNameModel,

            inputdate,
            predictions,
            measuresOptions,
            measuresModel,
            props2,
            propsSiteNames,

            deviceNameOptions,
            deviceNameModel,

            classificationOptions,
            classificationModel,

            alarmFlagOptions,
            alarmFlagModel,
        };
    },
});
</script>

<style>
.dp__theme_dark {
    --dp-background-color: #ebedf0;
    --dp-text-color: #144f9f;

    --dp-border-color: #dddddd;
    --dp-menu-border-color: #dddddd;
    --dp-icon-color: #144f9f;

    font-size: 5px !important;
}

.el-date-editor.el-input,
.el-date-editor.el-input__wrapper {
    --el-date-editor-width: 100% !important;
}
</style>

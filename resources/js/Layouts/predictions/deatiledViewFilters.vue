<template>
    <div class="row mt-3">
        <!-- <div class="col-4 pr-0">
            <div class="col-12 shadow-border" style="height: 135px">
                <p class="py-2">
                    The dashboard displays Machine Learning Powered sensor
                    predictions made for a 7 day period. A table with more
                    detailed information about the predicted sensor value and
                    corresponding thresholds can be viewed from the "Detailed
                    View" Tab on the top bar.
                </p>
            </div>
        </div> -->

        <div class="col-8 mx-0">
            <div class="row">
                <div class="col-3 mb-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Measure Description</div>

                        <SelectDroptownVue
                            :filters="options"
                            :options="siteNames"
                            v-model="siteNameModel"
                        />
                    </div>
                </div>
                <div class="col-3 mb-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Device Name</div>

                        <SelectDroptownVue
                            :filters="options"
                            :options="siteNames"
                            v-model="siteNameModel"
                        />
                    </div>
                </div>

                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Site Name</div>

                        <SelectDroptownVue
                            :filters="options"
                            :options="siteNames"
                            v-model="siteNameModel"
                        />
                    </div>
                </div>
                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Classification</div>

                        <SelectDroptownVue
                            :filters="options"
                            :options="siteNames"
                            v-model="siteNameModel"
                        />
                    </div>
                </div>

                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">Alarm Flag</div>

                        <SelectDroptownVue
                            :filters="options"
                            :options="siteNames"
                            v-model="siteNameModel"
                        />
                    </div>
                </div>
                <div class="col-3 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="min-height: 100px"
                    >
                        <div class="pb-2">OC</div>

                        <SelectDroptownVue
                            :filters="options"
                            :options="siteNames"
                            v-model="siteNameModel"
                        />
                    </div>
                </div>
                <div class="col-6 pr-0">
                    <div
                        class="col-12 shadow-border py-3"
                        style="height: 100px"
                    >
                        <div class="pb-2">Date between</div>
                        <VueDatePicker
                            v-model="inputdate"
                            :enable-time-picker="false"
                            dark
                            range
                        ></VueDatePicker>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
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
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, watch, onMounted } from "vue";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import { predictionsFilterStore } from "../../stores/predictionsFilter";
import SelectDroptownVue from "../../Components/SelectDroptown.vue";

export default defineComponent({
    components: {
        VueDatePicker,
        SelectDroptownVue,
    },

    setup() {
        const filterStore = predictionsFilterStore();
        const siteNameModel = ref([]);
        const siteNames = ref(filterStore.siteNames);
        const inputdate = ref(filterStore.date);
        const options = ref([
            "PORT ELIZABETH",
            "CONSTANTIABERG",
            "JOHANNESBURG",
        ]);

        watch(siteNameModel, (newFilter, oldFilter) => {
            filterStore.siteNames = newFilter;
            console.log(filterStore.siteNames);
        });

        watch(inputdate, (newDate, oldDate) => {
            filterStore.date = oldDate;
        });

        const series = ref([70, 20]);

        const chartOptions = ref({
            colors: ["#41e809", "#e80909"],
            labels: ["Normal", "Alarm"],

            chart: {
                type: "donut",
            },
        });

        return {
            options,
            siteNames,
            inputdate,
            siteNameModel,
            chartOptions,
            series,
        };
    },
});
</script>

<style lang="scss" scoped>
.dp__theme_dark {
    --dp-background-color: #ebedf0;
    --dp-text-color: #144f9f;

    --dp-border-color: #dddddd;
    --dp-menu-border-color: #dddddd;
    --dp-icon-color: #144f9f;

    font-size: 5px !important;
}
</style>

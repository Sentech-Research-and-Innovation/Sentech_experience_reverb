<template>
    <div class="row mt-3">
        <div class="col-lg-4 col-12 pr-lg-0 mb-lg-0 mb-3">
            <div class="col-12 shadow-border" style="height: 135px">
                <p class="py-lg-2 py-3">
                    The dashboard displays Machine Learning Powered sensor
                    predictions made for a 7 day period. A table with more
                    detailed information about the predicted sensor value and
                    corresponding thresholds can be viewed from the "Detailed
                    View" Tab on the top bar.
                </p>
            </div>
        </div>
        <div class="col-lg-4 col-6 pr-0">
            <div class="col-12 shadow-border py-lg-4 py-2 kpi_height">
                <div class="pb-2">Site Name</div>
                <!-- <Multiselect
                    v-model="siteNames"
                    :options="options"
                    mode="tags"
                /> -->
                <SelectDroptownVue
                    :filters="options"
                    :options="siteNames"
                    v-model="siteNameModel"
                />
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="col-12 shadow-border py-lg-4 py-2 kpi_height">
                <div class="pb-2">Date between</div>
                <el-date-picker
                    v-model="inputdate"
                    type="daterange"
                    range-separator="To"
                    start-placeholder="Start date"
                    end-placeholder="End date"
                    size="medium"
                />
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
        });

        watch(inputdate, (newDate, oldDate) => {
            filterStore.date = newDate;
        });

        // onMounted(async () => {
        //     siteNameModel.value = filterStore.siteNames;
        // });

        return { options, siteNames, inputdate, siteNameModel };
    },
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

<style>
.multiselect-tag {
    background: #144f9f !important;
    font-size: 10px !important;
}

.multiselect-option.is-pointed {
    color: #144f9f !important;
    font-size: 12px !important;
}

.dp__theme_dark {
    --dp-background-color: #ffff !important;
    --dp-text-color: #005cb2 !important;
    --dp-hover-color: #b4b7bc !important;
    --dp-hover-text-color: #fff;
    --dp-hover-icon-color: #484848;
    --dp-primary-color: #005cb2;
    --dp-primary-disabled-color: #61a8ea;
    --dp-primary-text-color: #fff;
    --dp-secondary-color: #a9a9a9;
    --dp-border-color: #ebedf0;
    --dp-menu-border-color: #c7cdd2 !important;
    --dp-border-color-hover: #aaaeb7;
    --dp-disabled-color: #737373;
    --dp-disabled-color-text: #d0d0d0;
    --dp-scroll-bar-background: #212121;
    --dp-scroll-bar-color: #484848;
    --dp-success-color: #00701a;
    --dp-success-color-disabled: #428f59;
    --dp-icon-color: #959595;
    --dp-danger-color: #e53935;
    --dp-marker-color: #e53935;
    --dp-tooltip-color: #3e3e3e;
    --dp-highlight-color: rgb(0 92 178 / 20%);
}
.kpi_height {
    min-height: 135px;
}

@media only screen and (max-width: 600px) {
    .kpi_height {
        min-height: 0px;
    }
}
.el-date-editor {
    --el-input-width: 100% !important;
    --el-date-editor-daterange-width: 100% !important;
}
</style>

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

<template>
    <div class="col-12">
        <div class="col-12 shadow-border my-3 px-0 report px-4 pt-5">
            <div class="reports">
                <div class="col-12 pb-2">
                    <h2>Engage 360 Platform</h2>

                    <h4 class="pt-2">Predictive maintenance report</h4>
                </div>
                <div class="line-breaker"></div>
                <div class="col-12 py-4 overview rounded my-4">
                    <h2>Overview KPIs and metrics</h2>
                    <div class="line-breaker2"></div>
                    <div class="col-12 pt-4 px-0 mx-0">
                        <div class="row">
                            <div class="col-4">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <h5 class="kpi-name">SITE NAMES</h5>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <el-avatar
                                                :icon="SwitchFilled"
                                                style="
                                                    color: #000000;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "
                                                :size="65"
                                            />
                                        </div>
                                        <div class="col-6 kpi-value-sites">
                                            JOHANNESBURG ( 20 ) <br />
                                            CAPE TOWN ( 20 ) <br />
                                            EAST LONDON ( 20 )
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <h5 class="kpi-name">MONITORED SENSORS</h5>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <el-avatar
                                                :icon="MagicStick"
                                                style="
                                                    color: #000000;
                                                    font-size: 60px;
                                                    background-color: #fff;
                                                "
                                                :size="60"
                                            />
                                        </div>
                                        <div class="col-6 kpi-value">38</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <h5 class="kpi-name">DATE BETWEEN</h5>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <el-avatar
                                                :icon="Calendar"
                                                style="
                                                    color: #000000;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "
                                                :size="65"
                                            />
                                        </div>
                                        <div class="col-6 kpi-value-date">
                                            2023-02-01 <br />
                                            2023-02-10
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- new line -->
                    <div class="col-12 pt-4 px-0 mx-0">
                        <div class="row">
                            <div class="col-4">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <h5 class="kpi-name">ALARM FLAGS</h5>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <el-avatar
                                                :icon="Flag"
                                                style="
                                                    color: #000000;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "
                                                :size="65"
                                            />
                                        </div>
                                        <div class="col-6 kpi-value-sites">
                                            ALARM (30 )<br />
                                            NORMAL ( 40 ) <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <h5 class="kpi-name">CLASSIFICATION</h5>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <el-avatar
                                                :icon="Trophy"
                                                style="
                                                    color: #000000;
                                                    font-size: 60px;
                                                    background-color: #fff;
                                                "
                                                :size="60"
                                            />
                                        </div>
                                        <div class="col-6 kpi-value-sites">
                                            PLATINUM ( 5 ) <br />
                                            GOLD ( 20 ) <br />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <h5 class="kpi-name">DEVICE NAMES</h5>
                                    <div class="row pt-1">
                                        <div class="col-6">
                                            <el-avatar
                                                :icon="Monitor"
                                                style="
                                                    color: #000000;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "
                                                :size="65"
                                            />
                                        </div>
                                        <div class="col-6 kpi-value">96</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 py-4 rounded my-4">
                    <h2>Count of Sensors in Alarm State by Date</h2>
                </div>
            </div>
        </div>
        <div class="col-12 pr-lg-4 pl-lg-0 px-4">
            <D3BarChart :config="config" :datum="data"></D3BarChart>
        </div>
    </div>
</template>

<script>
import filtersVue from "../../../Layouts/predictions/filter.vue";
import { defineComponent, ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import {
    MagicStick,
    Calendar,
    SwitchFilled,
    Flag,
    Trophy,
    Monitor,
} from "@element-plus/icons-vue";

export default defineComponent({
    //  layout: AdminLayout,
    name: "PredictiveMaintenance",
    components: { Link, Head },
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
            MagicStick,
            Calendar,
            SwitchFilled,
            Flag,
            Trophy,
            Monitor,
        };
    },
});
</script>

<style>
html {
    -webkit-print-color-adjust: exact;
}
.dd {
    background-color: #f2c744 !important;
}
.reports {
    color: black !important;
}

.line-breaker {
    background-color: #f2c744 !important;
    height: 4px;
}
.line-breaker2 {
    background-color: #f2c744 !important;
    height: 2px;
    margin-top: 10px;
}
.overview {
    background-color: #e3f6f5 !important;
}
.kpi-bg {
    background-color: #ffff !important;
}
.kpi-name {
    color: #2b6360;
}
.kpi-value {
    font-weight: 700;
    font-size: 50px;
}
.kpi-value-date {
    font-weight: 700;
    font-size: 25px;
}

.kpi-value-sites {
    font-weight: 700;
    font-size: 15px;
}
</style>

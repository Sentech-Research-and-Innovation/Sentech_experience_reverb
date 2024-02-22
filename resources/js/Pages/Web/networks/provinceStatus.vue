<template>
    <Head :title="'News'"><title>News</title></Head>
    <WebLayout> </WebLayout>
    <div class="container">
        <div class="sentech-index-page" style="background-color: #ffff">
            <div class="hearder-text pt-5 col-12 px-0 mx-0">Network status</div>
            <div class="col-12 pt-4 px-0">
                <div class="row">
                    <div class="col-3 shadow-sm shadow-border py-4 px-2">
                        <div
                            class="col-12 pb-4 px-2 fs-6"
                            style="font-weight: 500"
                        >
                            Chosse a province
                        </div>
                        <el-radio-group v-model="radio1">
                            <div class="col-12 px-0">
                                <el-radio label="1" size="large" border
                                    ><div class="d-flex justify-content-end">
                                        <div class="ml-4">Eastern Cape</div>
                                        <div class="ml-4 pl-5 text-end">
                                            <i
                                                class="fas fa-exclamation-circle text-warning"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="2" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Gauteng</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="far fa-check-circle text-success"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="3" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Western Cape</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="fas fa-exclamation-circle text-warning"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="4" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Mpumalanga</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="far fa-check-circle text-success"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="5" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Kwazulu Natal</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="fas fa-exclamation-circle text-warning"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="6" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Free State</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="far fa-check-circle text-success"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="7" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Limpopo</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="fas fa-exclamation-circle text-warning"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="8" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">Northern Cape</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="fas fa-exclamation-circle text-warning"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                            <div class="col-12 px-0 pt-2">
                                <el-radio label="9" size="large" border
                                    ><div class="d-flex justify-content">
                                        <div class="ml-4">North West</div>
                                        <div class="ml-5 pl-5 text-right">
                                            <i
                                                class="fas fa-exclamation-circle text-warning"
                                            ></i>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                        </el-radio-group>
                    </div>
                    <div class="col-9 text-start pr-0">
                        <div
                            class="col-12 px-0 mx-0 shadow-sm shadow-border py-4 px-4"
                        >
                            <div class="row px-4">
                                <div
                                    class="col-4 px-0 fs-6 pt-1"
                                    style="font-weight: 500"
                                >
                                    Filter
                                </div>
                                <div class="col-8 px-0 fs-6 text-end">
                                    <div class="row">
                                        <div class="col-6 pt-1">
                                            Select City
                                        </div>
                                        <div class="col-6">
                                            <el-select
                                                v-model="value"
                                                placeholder="Select"
                                                style="width: 240px"
                                            >
                                                <el-option
                                                    v-for="item in options"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value"
                                                    :disabled="item.disabled"
                                                />
                                            </el-select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-0 mx-0 shadow-sm py-4 px-4 mt-4">
                            fddfdfdf
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import { defineComponent, ref } from "vue";

import { WarningFilled } from "@element-plus/icons-vue";

//
export default defineComponent({
    components: {
        WebLayout,
        Head,
        Link,
    },
    setup() {
        const radio1 = ref("1");

        const value = ref("");
        const options = [
            {
                value: "Mthatha",
                label: "Mthatha",
            },
            {
                value: "East London",
                label: "East London",
            },
            {
                value: "Qumbu",
                label: "Qumbu",
            },
            {
                value: "Port Elizabeth",
                label: "Port Elizabeth",
            },
        ];
        return {
            radio1,
            WarningFilled,
            value,
            options,
        };
    },
});
</script>

<style scoped>
.sentech-index-page {
    padding-top: 100px;
    min-height: 100vh;
    color: #144f9f;
    --el-font-size-base: 17px;
    --el-font-weight-primary: 400;
}

.hearder-text {
    font-size: 20px;
    font-weight: 700;
}
</style>

<style>
.el-radio {
    width: 100%;
    border: none !important;
}
/* .el-radio .is-bordered .is-checked {
    background-color: red !important;
} */

.el-radio.is-bordered.is-checked {
    border: none !important;
    background-color: #144f9f !important;
    color: #ffff !important;
}

.el-radio__input.is-checked .el-radio__inner {
    border-color: #ffff !important;
    background: var(--el-color-primary);
}
#app {
    background-color: #fff;
}
</style>

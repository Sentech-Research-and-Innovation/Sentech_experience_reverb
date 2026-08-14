<template>
    <Head :title="'News'"><title>News</title></Head>
    <WebLayout>
        <div class="container">
            <div class="sentech-index-page" style="background-color: #ffff">
                <div class="hearder-text pt-5 col-12 px-0 mx-0">
                    Frequency finder
                </div>
                <div class="col-12 pt-4 px-0">
                    <div class="row">
                        <div
                            class="col-lg-3 col-12 shadow-sm shadow-border py-4 px-2 d-none d-lg-block d-xl-block"
                        >
                            <!-- <div
                                class="col-12 pb-4 px-2 fs-6"
                                style="font-weight: 500"
                            >
                                Chosse a province
                            </div> -->

                            <div
                                class="col-12 py-1 px-0"
                                v-for="(province, index) in provinces"
                                :key="index"
                            >
                                <div
                                    class="d-flex justify-content-end pt-3 pb-2"
                                    :class="{
                                        'selected-province':
                                            radio1 === province.value,
                                    }"
                                >
                                    <div class="col-8 province text-start pt-1">
                                        <label class="radio-container"
                                            >{{ province.label }}
                                            <input
                                                type="radio"
                                                v-model="radio1"
                                                :value="province.value"
                                            />

                                            <span class="checkmark">
                                                <i
                                                    v-if="
                                                        radio1 ===
                                                        province.label
                                                    "
                                                    class="far fa-dot-circle"
                                                ></i>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-3 text-start pt-1">
                                        <i
                                            class="fa-solid fa-circle-check text-success fa-lg"
                                        ></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="col-lg-3 col-12 shadow-border22 py-2 px-2 d-xl-none d-xxl-block d-lg-none px-4 mb-4"
                        >
                            <vue-horizontal>
                                <div
                                    class="col-12 py-1 px-0"
                                    v-for="(province, index) in provinces"
                                    :key="index"
                                >
                                    <div
                                        class="d-flex justify-content-end pt-3 pb-2"
                                        style="cursor: pointer"
                                        :class="{
                                            'selected-province':
                                                radio1 === province.value,
                                        }"
                                    >
                                        <div
                                            class="col-8 province text-start pt-1"
                                        >
                                            <label class="radio-container"
                                                >{{ province.label }}
                                                <input
                                                    type="radio"
                                                    v-model="radio1"
                                                    :value="province.value"
                                                />

                                                <span class="checkmark">
                                                    <i
                                                        v-if="
                                                            radio1 ===
                                                            province.label
                                                        "
                                                        class="far fa-dot-circle"
                                                    ></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-3 text-start pt-1">
                                            <i
                                                class="fa-solid fa-circle-check text-success fa-lg"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </vue-horizontal>
                        </div>

                        <div class="col-lg-9 col-12">
                            <div
                                v-for="(stations, station_name) in alarms"
                                :key="station_name"
                                class="mb-4 shadow-sm alert alert-default py-4 px-4"
                                style="border: none !important"
                            >
                                <!-- Accordion Header -->
                                <div
                                    class="accordion-header"
                                    @click="toggleAccordion(station_name)"
                                >
                                    <h5 class="fs-6">{{ station_name }}</h5>
                                    <i
                                        :class="
                                            accordionState[station_name]
                                                ? 'fa-solid fa-chevron-up'
                                                : 'fa-solid fa-chevron-down'
                                        "
                                    ></i>
                                </div>

                                <!-- Accordion Content -->
                                <div
                                    v-show="accordionState[station_name]"
                                    class="accordion-body"
                                >
                                    <div class="row">
                                        <div
                                            class="col-lg-4 py-2"
                                            v-for="station in stations"
                                            :key="station.id"
                                        >
                                            <div
                                                class="col-12 network-container-good px-0 shadow-sm py-3"
                                            >
                                                <div
                                                    class="col-12 text-start pb-1"
                                                >
                                                    Name:
                                                </div>
                                                <div
                                                    class="col-12 text-start description"
                                                >
                                                    {{ station.serv_name }}
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div
                                                            class="col-12 text-start pb-1 pt-2"
                                                        >
                                                            Frequency:
                                                        </div>
                                                        <div
                                                            class="col-12 description text-start"
                                                        >
                                                            {{
                                                                station.tx_freq
                                                            }}
                                                        </div>
                                                    </div>

                                                    <div class="col-6">
                                                        <div
                                                            class="col-12 text-start pb-1 pt-2"
                                                        >
                                                            Channel:
                                                        </div>
                                                        <div
                                                            class="col-12 description text-start"
                                                        >
                                                            {{
                                                                station.tx_channel
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import { defineComponent, ref, onMounted, watch } from "vue";

import { WarningFilled } from "@element-plus/icons-vue";
import { useFilterProvince } from "../../../stores/networks";
import VueHorizontal from "vue-horizontal";
//
export default defineComponent({
    components: {
        WebLayout,
        Head,
        Link,
        VueHorizontal,
    },
    setup() {
        const provinces = ref([
            {
                label: "Eastern Cape",
                value: "EC",
            },
            {
                label: "Western Cape",
                value: "WC",
            },
            {
                label: "Gauteng",
                value: "GP",
            },
            {
                label: "Kwazulu Natal",
                value: "KN",
            },
            {
                label: "Mpumalanga",
                value: "MP",
            },
            {
                label: "Free State",
                value: "FS",
            },
            {
                label: "Northern Cape",
                value: "NC",
            },
            {
                label: "North West",
                value: "NW",
            },
            {
                label: "Limpopo",
                value: "LP",
            },
        ]);

        const filterStore = useFilterProvince();
        const radio1 = ref(filterStore.province);

        const province = ref(filterStore.province);
        const alarms = ref();

        const accordionState = ref({});

        const getAlarmsData = async () => {
            try {
                const res = await axios.get(
                    `/web/network/alarms/${province.value}`
                );
                if (res.status === 200) {
                    alarms.value = res.data;
                    res.data.forEach(
                        (station) => (accordionState.value[station] = false)
                    );
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        const setNetWorkProvince = async (provinceNew) => {
            filterStore.province = await provinceNew;
        };

        const toggleAccordion = (station_name) => {
            accordionState.value[station_name] =
                !accordionState.value[station_name];
        };

        watch(radio1, (newFilter, oldFilter) => {
            filterStore.province = newFilter;

            alarms.value = [];
            province.value = newFilter;

            getAlarmsData();
        });

        onMounted(async () => {
            await getAlarmsData();
        });

        return {
            radio1,
            WarningFilled,
            toggleAccordion,
            setNetWorkProvince,
            provinces,
            alarms,
            accordionState,
        };
    },
});
</script>

<style scoped>
.accordion-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #f5f5f5;
    cursor: pointer;
    border-radius: 5px;
}

.accordion-body {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 10px;
    background-color: #fff;
}

.accordion-header h5 {
    margin: 0;
}

.network-container-good {
    border-left: 7px solid #17c964;

    font-size: 15px;
    text-align: right;
    border-radius: 5px;
    cursor: pointer;
    color: #000;
    border-bottom: 1px solid #144f9f;
    border-right: 1px solid #144f9f;
    border-top: 1px solid #144f9f;
}
.sentech-index-page {
    padding-top: 100px;
    min-height: 100vh;
    color: #144f9f;
    --el-font-size-base: 17px;
    --el-font-weight-primary: 400;
    background-color: #fff !important;
}

.hearder-text {
    font-size: 20px;
    font-weight: 700;
}

.selected-province {
    color: #fff !important;
    border-radius: 25px;
    background: #144f9f !important;
}

.default-province {
    color: #fff !important;
    border-radius: 25px;
    border: 1px solid #c2bebe;
}

@media only screen and (max-width: 1199px) {
    .sentech-index-page {
        padding-top: 30px;
        min-height: 100vh;
        color: #144f9f;
        --el-font-size-base: 17px;
        --el-font-weight-primary: 400;
        background-color: #fff !important;
    }
}
</style>

<style>
#app {
    background-color: #fff;
}

.province {
    font-weight: 400;
    color: #c2bebe;
}

.alert-default {
    background-color: #f5f5f5;
    color: #000;
    border-left: 4px solid red;
}
.time {
    font-size: 13px;
}
.description {
    font-size: 14px;
    font-weight: 500;
}
</style>

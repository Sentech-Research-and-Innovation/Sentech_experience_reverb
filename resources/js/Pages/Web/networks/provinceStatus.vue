<template>
    <Head :title="'News'"><title>News</title></Head>
    <WebLayout>
        <div class="container">
            <div class="sentech-index-page" style="background-color: #ffff">
                <div class="hearder-text pt-5 col-12 px-0 mx-0">
                    Network status
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
                                    style="cursor: pointer"
                                    :class="{
                                        'selected-province':
                                            radio1 === province,
                                    }"
                                >
                                    <div class="col-8 province text-start pt-1">
                                        <label class="radio-container"
                                            >{{ province }}
                                            <input
                                                type="radio"
                                                v-model="radio1"
                                                :value="province"
                                            />

                                            <span class="checkmark">
                                                <i
                                                    v-if="radio1 === province"
                                                    class="far fa-dot-circle"
                                                ></i>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-3 text-start pt-1">
                                        <i
                                            v-if="
                                                province == 'Gauteng' ||
                                                province == 'Western Cape' ||
                                                province == 'Eastern Cape'
                                            "
                                            class="fas fa-exclamation-circle text-danger fa-lg"
                                        ></i>

                                        <i
                                            v-else
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
                                        class="d-flex justify-content-end pt-3 pb-2 default-province"
                                        style="cursor: pointer"
                                        :class="{
                                            'selected-province':
                                                radio1 === province,
                                        }"
                                    >
                                        <div
                                            class="col-8 province text-start pt-1"
                                        >
                                            <label class="radio-container"
                                                >{{ province }}
                                                <input
                                                    type="radio"
                                                    v-model="radio1"
                                                    :value="province"
                                                />

                                                <span class="checkmark">
                                                    <i
                                                        v-if="
                                                            radio1 === province
                                                        "
                                                        class="far fa-dot-circle"
                                                    ></i>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="col-3 text-start pt-1">
                                            <i
                                                v-if="
                                                    province == 'Gauteng' ||
                                                    province ==
                                                        'Western Cape' ||
                                                    province == 'Eastern Cape'
                                                "
                                                class="fas fa-exclamation-circle text-danger fa-lg"
                                            ></i>

                                            <i
                                                v-else
                                                class="fa-solid fa-circle-check text-success fa-lg"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </vue-horizontal>
                        </div>

                        <div class="col-lg-9 col-12 text-start pr-0">
                            <!-- <div
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
                                                        :disabled="
                                                            item.disabled
                                                        "
                                                    />
                                                </el-select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div
                                v-if="alarms"
                                class="col-12 px-0 mx-0 pb-4 px-lg-4 mt-0"
                            >
                                <div v-if="isObjectEmpty(alarms)">
                                    <div
                                        class="row"
                                        v-for="(records, siteName) in alarms"
                                        :key="siteName"
                                    >
                                        <div class="col-12 pb-2">
                                            <h5>{{ siteName }}</h5>
                                        </div>

                                        <div
                                            class="col-lg-3 col-6"
                                            v-for="(record, index) in records"
                                            :key="index"
                                        >
                                            <div
                                                class="col-12 alert alert-default px-0 shadow-sm"
                                            >
                                                <div class="col-12 description">
                                                    {{
                                                        formatDeviceName(
                                                            record.DeviceName
                                                        )
                                                    }}
                                                </div>
                                                <div class="col-12 time pt-2">
                                                    <i
                                                        class="fa-regular fa-clock"
                                                    ></i>
                                                    {{
                                                        formatDateTime(
                                                            record.FormattedDateTimeEvent
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="text-center col-12 pt-lg-5">
                                    <svg
                                        viewBox="0 0 24 24"
                                        width="100"
                                        fill="#17c964"
                                    >
                                        <path
                                            d="m15.09 2 1.72 3.048 3.28.78-.308 3.515L22 12l-2.218 2.657.309 3.514-3.282.781-1.718 3.038L12 20.6 8.91 22l-1.72-3.038-3.28-.781.308-3.524L2 12l2.218-2.667L3.91 5.81l3.282-.772L8.909 2 12 3.39zm1.559 6.293a.954.954 0 0 0-1.284-.084l-.091.084-4.988 5.16-1.56-1.62a.954.954 0 0 0-1.284-.084l-.09.083-.069.07a1.02 1.02 0 0 0-.08 1.316l.08.095 2.305 2.4a.974.974 0 0 0 1.3.083l.095-.083 5.734-5.94a1.02 1.02 0 0 0 .08-1.316l-.08-.095-.068-.07"
                                        ></path>
                                    </svg>

                                    <div>
                                        <p>
                                            There are no network alerts at the
                                            moment
                                        </p>
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
import { Head, Link } from "@inertiajs/inertia-vue3";
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
            "Eastern Cape",
            "Western Cape",
            "Gauteng",
            "Kwazulu Natal",
            "Mpumalanga",
            "Free State",
            "Northern Cape",
            "North West",
            "Limpopo",
        ]);

        const filterStore = useFilterProvince();
        const radio1 = ref(filterStore.province);

        const province = ref(filterStore.province);
        const value = ref("");
        const options = ref([]);
        const alarms = ref({});
        const getCitiesByProvince = async () => {
            try {
                const res = await axios.get(
                    `/web/network/province/cities/${province.value}`
                );
                if (res.status === 200) {
                    const cities = res.data;

                    cities.forEach((city) => {
                        options.value.push({
                            value: city.SiteName,
                            label: city.SiteName,
                        });
                    });
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };
        const getAlarmsData = async () => {
            try {
                const res = await axios.get(
                    `/web/network/alarms/${province.value}`
                );
                if (res.status === 200) {
                    const data = res.data;

                    alarms.value = data.reduce((acc, item) => {
                        const siteName = item.SiteName;
                        if (!acc[siteName]) {
                            acc[siteName] = [];
                        }
                        acc[siteName].push(item);
                        return acc;
                    }, {});
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        const setNetWorkProvince = async (provinceNew) => {
            filterStore.province = await provinceNew;
            options.value = [];
            await getCitiesByProvince();
        };

        const formatDeviceName = (deviceName) => {
            const parts = deviceName.split("-");
            if (parts.length > 1) {
                const partsTrim = parts[1].replace(/Tx\s*/, "").trim();
                if (partsTrim == "Mux1" || partsTrim == "Mux2") {
                    return "Multichoice";
                } else {
                    return partsTrim;
                }
            } else {
                // If no hyphen, return the original string
                return deviceName.trim();
            }
        };

        const formatDateTime = (dateTimeString) => {
            const options = {
                hour: "numeric",
                minute: "numeric",
                day: "numeric",
                month: "short",
                year: "numeric",
            };

            const dateTime = new Date(dateTimeString);
            return dateTime.toLocaleString("en-ZA", options);
        };
        const isObjectEmpty = (obj) => {
            return Object.keys(obj).length !== 0;
        };

        watch(radio1, (newFilter, oldFilter) => {
            filterStore.province = newFilter;
            options.value = [];
            alarms.value = [];
            province.value = newFilter;
            getCitiesByProvince();
            getAlarmsData();
        });

        onMounted(async () => {
            await getCitiesByProvince();
            await getAlarmsData();
        });

        return {
            isObjectEmpty,
            radio1,
            WarningFilled,
            value,
            options,
            setNetWorkProvince,
            provinces,
            alarms,
            formatDateTime,
            formatDeviceName,
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

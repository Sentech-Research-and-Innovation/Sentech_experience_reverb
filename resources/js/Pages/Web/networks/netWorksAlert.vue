<template>
    <div class="col-12 px-lg-0 py-lg-0 px-4 py-4 mx-0">
        <div class="networkHeading pb-lg-3 pb-2 pt-1">
            Broadcast Frequency Finder
        </div>
        <p class="networkHeadingText">
            Use our Broadcast Frequency Finder to explore radio and TV station
            frequencies in your area and ensure you’re always connected to your
            favorite shows
        </p>
        <div class="row pt-lg-4 pt-2">
            <div class="col-lg-4 col-6 px-2">
                <div
                    class="col-12 network-container-good py-1 mx-0"
                    @click="setNetWorkProvince('EC')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Eastern Cape
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('WC')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Western Cape
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 px-2 pt-lg-0 pt-3">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('KN')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Kwazulu Natal
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('GP')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Gauteng
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>

            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('MP')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Mpumalanga
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('FS')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Free State
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('LP')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Limpopo
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('NC')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    Northern Cape
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('NW')"
                >
                    <el-tooltip
                        class="box-item"
                        effect="light"
                        content="Click province to see more details"
                        placement="top"
                    >
                        <div class="d-flex justify-content-between py-lg-2">
                            <div class="py-1 text-start">
                                <div style="font-size: 17px; margin-top: 6px">
                                    North West
                                </div>
                            </div>

                            <div
                                class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                            >
                                <i class="fa-solid fa-check"></i>
                            </div>
                        </div>
                    </el-tooltip>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

import { defineComponent, ref, onMounted } from "vue";
import { useFilterProvince } from "../../../stores/networks";

export default defineComponent({
    components: { Link },

    setup() {
        const networks = ref([]);

        const filterStore = useFilterProvince();

        const setNetWorkProvince = async (province) => {
            filterStore.province = await province;

            window.location.href = "/web/network/status";
        };

        const getData = async () => {
            try {
                const res = await axios.get(`/web/network/index`);
                if (res.status === 200) {
                    networks.value = res.data;
                }
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        onMounted(async () => {
            await getData();
        });

        return {
            networks,
            setNetWorkProvince,
        };
    },
});
</script>

<style scoped>
.nav-link-status {
    text-decoration: none !important;
    color: #ffff;
}
.networkHeading {
    font-size: 30px;
    font-weight: 500;
}
.networkHeadingText {
    font-size: 16px;
    font-weight: 500;
}

.network-container {
    background-color: #0c2f5f !important;

    font-size: 15px;
    text-align: right;
    border-radius: 5px;
}

.network-container-good {
    border-left: 7px solid #93ad24;

    background-color: #0c2f5f !important;

    font-size: 15px;
    text-align: right;
    border-radius: 5px;
    cursor: pointer;
}
.network-container-error {
    border-left: 7px solid #c51616;

    background-color: #0c2f5f !important;

    font-size: 15px;
    text-align: right;
    border-radius: 5px;
    cursor: pointer;
}

.network-container-warning {
    border-left: 7px solid #c51616;

    background-color: #0c2f5f !important;

    font-size: 15px;
    text-align: right;
    border-radius: 5px;
    cursor: pointer;
}

.fs-6 {
    font-size: 13px !important;
}
@media only screen and (max-width: 1199px) {
    .networkHeading {
        font-size: 16px;
        font-weight: 500;
    }

    .networkHeadingText {
        font-size: 14px;
        font-weight: 300;
    }

    .network-container-warning {
        border-left: 7px solid #c51616;

        background-color: #0c2f5f !important;

        font-size: 12px;
        text-align: right;
        border-radius: 5px;
        cursor: pointer;
    }

    .network-container-good {
        border-left: 7px solid #93ad24;

        background-color: #0c2f5f !important;

        font-size: 12px;
        text-align: right;
        border-radius: 5px;
        cursor: pointer;
    }
}
</style>

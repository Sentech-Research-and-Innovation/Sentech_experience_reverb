<template>
    <div class="col-12 px-lg-0 py-lg-0 px-4 py-4 mx-0">
        <div class="networkHeading pb-lg-3 pb-2 pt-1">Network Status</div>
        <p class="networkHeadingText">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna.
        </p>
        <div class="row pt-lg-4 pt-2">
            <div class="col-lg-4 col-6 px-2">
                <div
                    class="col-12 network-container-warning py-1 mx-0"
                    @click="setNetWorkProvince('Eastern Cape')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Eastern Cape</strong> <br />
                            <span> OFF Line (4) </span>
                        </div>

                        <div
                            class="status-warning pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 px-2">
                <div
                    class="col-12 network-container-warning py-1"
                    @click="setNetWorkProvince('Western Cape')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Western Cape</strong> <br />
                            <span> OFF Line (4) </span>
                        </div>
                        <div
                            class="status-warning pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 px-2 pt-lg-0 pt-3">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Kwazulu Natal')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Kwazulu Natal</strong> <br />
                            <span>All Good </span>
                        </div>
                        <div
                            class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-warning py-1"
                    @click="setNetWorkProvince('Gauteng')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Gauteng</strong> <br />
                            <span> OFF Line (4) </span>
                        </div>
                        <div
                            class="status-warning pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Mpumalanga')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Mpumalanga</strong> <br />
                            <span> All Good </span>
                        </div>
                        <div
                            class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Free State')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Free State</strong> <br />
                            <span> All Good </span>
                        </div>
                        <div
                            class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Limpopo')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Limpopo</strong> <br />
                            <span> All Good </span>
                        </div>
                        <div
                            class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Northern Cape')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>Northern Cape</strong> <br />
                            <span> All Good </span>
                        </div>
                        <div
                            class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('North West')"
                >
                    <div class="d-flex justify-content-between py-lg-2">
                        <div class="py-1 text-start">
                            <strong>North West</strong> <br />
                            <span> All Good </span>
                        </div>
                        <div
                            class="status-good pt-lg-2 pb-3 px-lg-2 pt-1 text-center mt-lg-1 mt-2"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
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

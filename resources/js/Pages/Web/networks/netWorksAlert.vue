<template>
    <div class="col-12 px-0 py-0 mx-0">
        <div class="networkHeading pb-3">Network Status</div>
        <p style="font-size: 19px; font-weight: 500">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            eiusmod tempor incididunt ut labore et dolore magna.
        </p>
        <div class="row pt-4">
            <div class="col-4 px-2">
                <div
                    class="col-12 network-container-good py-1 mx-0"
                    @click="setNetWorkProvince('Eastern Cape')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Eastern Cape</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                All Good
                            </span>
                        </div>

                        <div
                            class="status-good pt-2 pb-3 px-2 text-center mt-1"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 px-2">
                <div
                    class="col-12 network-container-warning py-1"
                    @click="setNetWorkProvince('Western Cape')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Western Cape</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                Medium Impact
                            </span>
                        </div>
                        <div class="status-warning pt-2 pb-3 text-center mt-1">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 px-2">
                <div
                    class="col-12 network-container-error py-1"
                    @click="setNetWorkProvince('Kwazulu Natal')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Kwazulu Natal</strong> <br />
                            <span class="fs-6" style="font-weight: 500"
                                >High Impact
                            </span>
                        </div>
                        <div class="status-error pt-2 pb-3 text-center mt-1">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Gauteng')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Gauteng</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                All Good
                            </span>
                        </div>
                        <div class="status-good pt-2 pb-3 px-2 text-center m1">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4 pt-3 px-2">
                <div
                    class="col-12 network-container-warning py-1"
                    @click="setNetWorkProvince('Mpumalanga')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Mpumalanga</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                Medium Impact
                            </span>
                        </div>
                        <div class="status-warning pt-2 pb-3 text-center mt-1">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 pt-3 px-2">
                <div
                    class="col-12 network-container-error py-1"
                    @click="setNetWorkProvince('Free State')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Free State</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                High Impact
                            </span>
                        </div>
                        <div
                            class="status-error pt-2 pb-3 px-2 text-center mt-1"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 pt-3 px-2">
                <div
                    class="col-12 network-container-good py-1"
                    @click="setNetWorkProvince('Limpopo')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Limpopo</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                All Good
                            </span>
                        </div>
                        <div
                            class="status-good pt-2 pb-3 px-2 text-center mt-1"
                        >
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 pt-3 px-2">
                <div
                    class="col-12 network-container-warning py-1"
                    @click="setNetWorkProvince('Northern Cape')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>Northern Cape</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                Medium Impact
                            </span>
                        </div>
                        <div class="status-warning pt-2 pb-3 text-center mt-1">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 pt-3 px-2">
                <div
                    class="col-12 network-container-error py-1"
                    @click="setNetWorkProvince('North West')"
                >
                    <div class="d-flex justify-content-between py-2">
                        <div class="py-1 text-start">
                            <strong>North West</strong> <br />
                            <span class="fs-6" style="font-weight: 500">
                                High Impact
                            </span>
                        </div>
                        <div
                            class="status-error pt-2 pb-3 px-2 text-center mt-1"
                        >
                            <i class="fa-solid fa-xmark"></i>
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
    border-left: 7px solid #f7a623;

    background-color: #0c2f5f !important;

    font-size: 15px;
    text-align: right;
    border-radius: 5px;
    cursor: pointer;
}

.status-good {
    /* background-color: #556d8f; */
    background: rgba(255, 255, 255, 0.09);
    color: #93ad24;
    font-size: 20px;
    height: 35px;
    width: 35px;
    border-radius: 50%;
    padding-bottom: 19px !important;
}
.status-warning {
    background: rgba(255, 255, 255, 0.09);

    color: #f7a623;
    font-size: 20px;
    height: 35px;
    width: 35px;
    border-radius: 50%;
    padding-bottom: 19px !important;
}

.status-error {
    background: rgba(255, 255, 255, 0.09);

    color: #c51616;
    font-size: 20px;
    height: 35px;
    width: 35px;
    border-radius: 50%;
    padding-bottom: 19px !important;
}
.fs-6 {
    font-size: 13px !important;
}
</style>
>

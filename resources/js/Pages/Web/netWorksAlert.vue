<template>
    <div class="col-12 px-0 py-4">
        <span class="networkHeading">Network Status</span>
        <div class="row pt-3">
            <div class="col-3">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>Eastern Cape</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-warning py-3 px-4 col-3">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                        <div class="py-1">
                            <strong>Western Cape</strong> <br />
                            <span class="fs-6"> Medium Impact </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-warning py-3 px-4 col-3">
                            <i class="fa-solid fa-exclamation"></i>
                        </div>
                        <div class="py-1">
                            <strong>Kwazulu Natal</strong> <br />
                            <span class="fs-6"> Medium Impact </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 mr-0 pr-0">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <!-- <i class="fa-solid fa-exclamation"></i> -->
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>Gauteng</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 mr-0 pt-4">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <!-- <i class="fa-solid fa-exclamation"></i> -->
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>Mpumalanga</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 mr-0 pt-4">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <!-- <i class="fa-solid fa-exclamation"></i> -->
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>Free Sate</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 mr-0 pt-4">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <!-- <i class="fa-solid fa-exclamation"></i> -->
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>Limpopo</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 mr-0 pt-4 pr-0">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <!-- <i class="fa-solid fa-exclamation"></i> -->
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>Nothern Cape</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3 mr-0 pt-4">
                <div class="col-12 network-container">
                    <div class="d-flex justify-content-between py-2">
                        <div class="status-good py-3 px-4 col-3">
                            <!-- <i class="fa-solid fa-exclamation"></i> -->
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="py-1">
                            <strong>North West</strong> <br />
                            <span class="fs-6"> All Good </span>
                        </div>
                        <div class="pt-3 fs-6">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
export default defineComponent({
    components: {},

    setup() {},
});
</script>

<style scoped>
.networkHeading {
    font-size: 20px;
}
.network-container {
    background-color: #4372b2 !important;

    font-size: 15px;
    text-align: right;
    border-radius: 15px;
}

.network-container:hover {
    color: #144f9f;
    cursor: pointer;
    background-color: #fff;
    -webkit-transition: background-color 1000ms ease;
    transition: background-color 1000ms ease;
}
.status-good {
    background-color: #67c23a;
    color: #ffff;
    border-radius: 15px;
    font-size: 13px;
}
.status-warning {
    background-color: #e6a23c;
    color: #ffff;
    border-radius: 15px;
    font-size: 13px !important;
}
.fs-6 {
    font-size: 13px !important;
}
</style>
>

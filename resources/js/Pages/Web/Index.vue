<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import SideModal from "../../Layouts/SideModal.vue";
import WeatherWidgetVue from "./WeatherWidget.vue";

export default {
    props: ["x"],
    setup() {
        return {};
    },
    components: {
        SideModal,
        WebLayout,
        Head,
        Link,
        WeatherWidgetVue,
    },
    data: function () {
        return {
            step: 1,
            form: {},
        };
    },
    computed: {},
    headers: {},
    mounted() {},
    methods: {},
};
</script>

<template>
    <Head :title="'Home'"><title>Home</title></Head>
    <WebLayout>
        <div class="sentech-index-page">
            <div>
                <div class="col-md-12 px-0">
                    <div class="welcome-text">
                        Lorem ipsum dolor sit amet consectetur adipiscing elit
                    </div>
                </div>
                <div class="col-md-12 pt-5">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-12">
                            <div
                                class="col-lg-11 col-12 mt-2 advert-section-page px-4"
                            >
                                <div
                                    class="advert-section-div pt-lg-5 mx-4 pt-3"
                                >
                                    Introducing our <br />
                                    NEW Streaming App Sentech Plus
                                </div>

                                <div
                                    class="d-flex justify-content-end mt-5 col-12 py-2"
                                >
                                    <div
                                        class="btn advert-section-div-button my-4"
                                    >
                                        <span class="pr-2">Get Started</span>
                                        <img src="arrow-right-grey.png" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12 pt-lg-2 pt-4">
                                    <div class="advert-section-text">
                                        Lorem ipsum dolor sit amet, consectetur
                                        adipiscing elit, sed do eiusmod tempor
                                        incididunt ut labore et dolore magna
                                        aliqua.
                                    </div>
                                </div>
                                <div class="col-12 mx-0 px-lg-2 px-md-2 px-0">
                                    <div
                                        class="d-lg-flex justify-content-between"
                                    >
                                        <div
                                            class="col-lg-7 col-md-12 col-12 weather-widget"
                                        >
                                            <WeatherWidgetVue />
                                        </div>
                                        <div
                                            class="col-md-12 col-lg-5 col-12 mx-lg-3 px-0 py-4 py-lg-0"
                                        >
                                            <div
                                                class="col-md-12 small-info-block py-3 text-center"
                                            >
                                                <span
                                                    class="advert-section-span"
                                                    >Feedback</span
                                                >
                                            </div>
                                            <div
                                                class="col-md-12 py-3 small-info-block my-3 text-center"
                                            >
                                                <span
                                                    class="advert-section-span"
                                                >
                                                    Public Stats
                                                </span>
                                            </div>
                                            <div
                                                class="col-md-12 pb-2 small-info-block py-3 text-center"
                                            >
                                                <span
                                                    class="advert-section-span"
                                                >
                                                    Promotions
                                                </span>
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

<style scoped lang="scss">
.sentech-index-page {
    margin-top: 50px;
}

.advert-section-text {
    font-size: 25px;
    font-weight: normal;
    margin-bottom: 50px;
}

.welcome-text {
    font-size: 58px;
    font-weight: bolder;
    max-width: 75%;
}

.advert-section-page {
    background-color: #fff;
    height: auto;
    border-radius: 20px;
}

.advert-section-div-button {
    color: #9f9e9e;
    font-size: 15px;
    border: solid 2px #9f9e9e;
    border-radius: 10px;
    font-weight: bold;
    background: #fff;
}

.advert-section-div {
    color: #9f9e9e;

    font-size: 30px;
    width: 100%;
    font-weight: normal;
}

.small-info-block {
    background: #4372b2;
    border-radius: 10px;
    width: 100%;
}

.advert-section-span {
    font-size: 16px;
    font-weight: 600;
}

.weather-widget {
    background-color: #0c368b;
    border-radius: 10px;
    color: #fff !important;
}

.btn:hover {
    background-color: #144f9f;
    color: #fff;
    border: solid 1px #4372b2;
}

.small-info-block.d-flex.justify-content-center.align-items-center:hover {
    color: #144f9f;
    cursor: pointer;
    background-color: #fff;
    -webkit-transition: background-color 1000ms ease;
    transition: background-color 1000ms ease;
}

@media only screen and (max-width: 1440px) {
    .welcome-text {
        font-size: 56px;
        font-weight: bolder;
        max-width: 75%;
    }
}

@media only screen and (max-width: 1400px) {
    .welcome-text {
        font-size: 50px;
    }
}

@media only screen and (max-width: 1199px) {
    .welcome-text {
        font-size: 42px;
    }
}

@media only screen and (max-width: 991px) {
    .welcome-text {
        font-size: 32px;
    }
}
</style>

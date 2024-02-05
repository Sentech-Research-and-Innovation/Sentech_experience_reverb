<template>
    <Head :title="'Home'"><title>Home</title></Head>
    <WebLayout>
        <div class="sentech-index-page">
            <div>
                <div class="col-md-12 px-0">
                    <div class="welcome-text">
                        Welcome to the Sentech Engage 360 Platform
                    </div>
                </div>
                <div class="col-12 pt-4 mx-0 px-0">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-12" style="">
                            <div
                                class="col-lg-11 col-12 mt-2 advert-section-page px-4"
                            >
                                <div class="pt-lg-4 pt-2">
                                    <img :src="freeVisionLogo" width="300" />
                                </div>
                                <div
                                    class="advert-section-div pt-lg-3 mx-2 pt-3"
                                >
                                    Introducing our <br />
                                    NEW Streaming App
                                </div>

                                <div
                                    class="d-flex justify-content-end mt-1 col-12 pt-2"
                                >
                                    <a
                                        href="https://play.google.com/store/apps/details?id=za.co.freevisiontv"
                                        target="_blank"
                                        rel="external"
                                        class="pt-4 pr-4 f-vision-icon"
                                    >
                                        <svg
                                            width="40"
                                            height="40"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 512 512"
                                        >
                                            <path
                                                d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"
                                                fill=" #ffffff"
                                            ></path>
                                        </svg>
                                    </a>
                                    <a
                                        href="https://apps.apple.com/th/app/freevision-play/id6446925337"
                                        target="_blank"
                                        rel="external"
                                        class="pt-4 pr-4 f-vision-icon"
                                    >
                                        <svg
                                            width="40"
                                            height="40"
                                            viewBox="0 0 15 18"
                                            fill=" #ffffff"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M11.9773 9.28833C11.956 7.07541 13.7972 6.01213 13.8801 5.96073C12.8448 4.45794 11.2336 4.252 10.6595 4.22717C9.28747 4.08941 7.98294 5.02892 7.28749 5.02892C6.59203 5.02892 5.51792 4.24815 4.38121 4.26948C2.88541 4.29081 1.5078 5.13206 0.736826 6.46003C-0.815612 9.13588 0.339975 13.0988 1.8536 15.2691C2.59346 16.3299 3.47492 17.5243 4.634 17.4809C5.74973 17.4379 6.17141 16.7656 7.5193 16.7656C8.8672 16.7656 9.24691 17.4809 10.4263 17.4589C11.6266 17.4379 12.3871 16.3771 13.1217 15.3121C13.971 14.0799 14.3203 12.8869 14.3416 12.8254C14.3154 12.8135 12.0014 11.9331 11.9773 9.28798"
                                                fill=" #ffffff"
                                            ></path>
                                            <path
                                                d="M9.76073 2.79159C10.3761 2.05209 10.7901 1.02412 10.6765 0C9.7908 0.0360138 8.71808 0.58601 8.08207 1.32482C7.51249 1.97936 7.01354 3.02516 7.14816 4.029C8.13556 4.10487 9.145 3.5304 9.76038 2.79159"
                                                fill="#ffffff"
                                            ></path>
                                        </svg>
                                    </a>
                                    <!-- <a
                                        href="https://devstech.dotcomlb.com"
                                        target="__blank"
                                    > -->
                                    <a
                                        href="https://freevisionplay.co.za"
                                        target="_blank"
                                        rel="external"
                                    >
                                        <div
                                            class="btn advert-section-div-button my-4 py-3 px-2"
                                        >
                                            <span class="pr-4"
                                                >Get Started</span
                                            >

                                            <img :src="arrow" width="40" />
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12 pt-lg-2 pt-4">
                                    <div class="advert-section-text pt-4">
                                        We'd love your feedback on how to make
                                        our new platform work for you!
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
                                                    Login
                                                </span>
                                            </div>
                                            <div
                                                class="col-md-12 pb-2 small-info-block py-3 text-center"
                                            >
                                                <span
                                                    class="advert-section-span"
                                                >
                                                    Learn More
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
            <NetWorksAlertVue />
        </div>
    </WebLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import SideModal from "../../Layouts/SideModal.vue";
import WeatherWidgetVue from "./WeatherWidget.vue";

import freeVisionLogo from "../../assets/Freevision.svg";

import arrow from "../../assets/arrow-right-grey.png";

import NetWorksAlertVue from "./netWorksAlert.vue";
import { UserFilled, Edit } from "@element-plus/icons-vue";

export default {
    components: {
        SideModal,
        WebLayout,
        Head,
        Link,
        WeatherWidgetVue,
        NetWorksAlertVue,
    },
    data: function () {
        return {
            step: 1,
            form: {},
            freeVisionLogo,
            arrow,
            Edit,
        };
    },
};
</script>

<style scoped lang="scss">
.sentech-index-page {
    padding-top: 160px;
    min-height: 100vh;
}

.advert-section-text {
    font-size: 25px;
    font-weight: normal;
    margin-bottom: 50px;
}

.welcome-text {
    font-size: 45px;
    font-weight: bolder;
    max-width: 60%;
}

.advert-section-page {
    height: 321px;
    border-radius: 20px;
    background: url("../../assets/movie_bg.jpg");
    background-repeat: no-repeat;
    background-size: 600px 350px;
}

.advert-section-div-button {
    color: #ffffff;
    font-size: 15px;
    border-radius: 10px;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.397);
}

.advert-section-div {
    color: #fff;

    font-size: 25px;
    width: 100%;
    font-weight: 100;
}

.small-info-block {
    background: #4372b2;
    border-radius: 10px;
    width: 100%;
}

.advert-section-span {
    font-size: 16px;
    font-weight: 500;
}

.weather-widget {
    background-color: #0c368b;
    border-radius: 10px;
    color: #fff !important;
    transition-timing-function: ease-in;
}
.weather-widget:hover {
    background-color: #4372b2;
    opacity: 1;
}

.btn:hover {
    background-color: #144f9f;
    color: #fff;
    border: solid 1px #4372b2;
}

.small-info-block:hover {
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
    consectetur
        adipiscing
        elit
        Introducing
        our
        NEW
        Streaming
        App
        Lorem
        ipsum
        dolor
        sit
        amet,
    consectetur .welcome-text {
        font-size: 32px;
    }
}
.f-vision-icon a {
    position: relative;
    display: inline-block;
}
.f-vision-icon:hover svg path {
    fill: #4b84d3 !important;
}
</style>

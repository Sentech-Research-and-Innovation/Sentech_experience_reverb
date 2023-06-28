<script>
    import {Head, Link} from '@inertiajs/inertia-vue3';
    import WebLayout from '@/Layouts/WebLayout.vue';
    import SideModal from "../../Layouts/SideModal.vue";
    import Login from "../Auth/Login.vue";

    export default {
        props: ['x'],
        setup() {
            return {}

        },
        components: {
            Login,
            SideModal,
            WebLayout,
            Head,
            Link,


        },
        data: function () {
            return {
                step: 1,
                form: {},
            }
        },
        computed: {},
        headers: {},
        mounted() {
        },
        methods: {},
    }
</script>


<template>
    <Head :title="'Home'"/>
    <WebLayout>
        <div class="sentech-index-page">
            <div class="row">
                <div class="col-md-12">
                    <div class="welcome-text">
                        Lorem ipsum dolor sit amet
                        consectetur adipiscing elit
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="home-pills">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="advert-section">

                                <div class="advert-section-div">
                                    Introducing our
                                    NEW Streaming App
                                    Sentech Plus

                                </div>

                                    <div class="pull-r">
                                        <div class="btn btn-primary advert-section-div-button d-flex justify-content-between align-items-center">
                                            <span>Get Started</span>
                                            <img src="arrow-right-grey.png">
                                        </div>
                                    </div>



                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="advert-section-text">
                                            Lorem ipsum dolor sit amet, consectetur adipiscing
                                            elit, sed do eiusmod tempor incididunt ut labore et
                                            dolore magna aliqua.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small-info-block d-flex justify-content-center align-items-center">
                                            <span class="advert-section-span ">Feedback</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small-info-block d-flex justify-content-center align-items-center">
                                            <span class="advert-section-span"> Public Stats </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="small-info-block d-flex justify-content-center align-items-center">
                                            <span class="advert-section-span"> Promotions </span>
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

    .home-pills {
        margin-top: 50px;
    }
    .advert-section-text{
        font-size: 25px;
        font-weight: normal;
        margin-bottom: 50px;
    }
    .welcome-text {
        font-size: 58px;
        font-weight: bolder;
        max-width: 75%;
    }

    .advert-section {
        height: 365px;
        background: #fff;
        border-radius: 50px;
        margin-right: 50px;
    }
    .advert-section-div-button{
        color: #706F6F;
        font-size: 25px;
        border: solid 1px #706F6F;
        font-weight: normal;
        width: 100%;
        border-right:8px ;
        background: #fff;
        height: 70px;
        border-radius: 8px;




    }
    .advert-section-div{
        color: #706F6F;
        padding: 50px;
        font-size: 35px;
        width: 75%;
        font-weight: normal;
    }

    .small-info-block {
        background: #4372B2;
        border-radius: 25px;
        width: 100%;
        height: 200px;

    }
    .advert-section-span{
        font-size: 20px ;
        font-weight: normal;
    }


    .btn:hover {
        background-color: #144f9f;
        color:#fff;
    }
    @media only screen and (max-width: 1440px) {

        .advert-section-text {
            font-size: 25px;
            font-weight: normal;
            margin-bottom: 50px;
        }
        .welcome-text {
            font-size: 56px;
            font-weight: bolder;
            max-width: 75%;
        }

        .advert-section {
            height: 330px;
            background: #fff;
            border-radius: 50px;
            margin-right: 50px;
        }

        .small-info-block {
            background: #4372B2;
            border-radius: 25px;
            width: 100%;
            height: 180px;

        }
        .advert-section-span {
            font-size: 20px;
            font-weight: normal;
        }
    }

    @media only screen and (max-width: 1400px) {
        .advert-section-text {
            font-size: 21px;
        }
        .welcome-text {
            font-size: 50px;
        }

    }
    @media only screen and (max-width: 1199px) {
        .advert-section-text {
            font-size: 18px;
        }
        .welcome-text {
            font-size: 42px;
        }
        .small-info-block{
            height: 150px;
        }
        .advert-section {
            height: 280px;
        }


    }
    @media only screen and (max-width: 991px) {
        .advert-section-text {
            font-size: 13px;
        }
        .welcome-text {
            font-size: 32px;
        }
        .small-info-block{
            height: 120px;
        }
        .advert-section {
            height: 230px;
        }
        .advert-section-span {
            font-size: 10px;

        }

    }
</style>

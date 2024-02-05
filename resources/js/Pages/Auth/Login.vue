<template>
    <Head :title="'Login'"><title>Login</title></Head>
    <!-- <button type="button" class="button button-dark" @click="showing = true">
        Create {{ showing }}
    </button> -->
    <a
        class="nav-link"
        @click="showing = true"
        style="cursor: pointer !important"
    >
        <i class="fa fa-user"></i>Login
    </a>
    <SideModal
        :content="content"
        :showing="showing"
        @hideModal="showing = false"
    >
        <div class="col-12">
            <div class="d-flex justify-content-center">
                <div class="col-lg-6 col-md-8 col-12 mt-5 pt-5">
                    <div class="h1-login">Login</div>
                    <form @submit.prevent="submit">
                        <div class="row pt-4">
                            <div class="mb-3">
                                <label for="email" class="form-label"
                                    >Email address</label
                                >
                                <input
                                    type="email"
                                    class="form-control login-form-inputs"
                                    v-model="form.email"
                                    id="email"
                                />

                                <div class="text-danger pt-2">
                                    {{ errors.email }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"
                                    >Password</label
                                >
                                <input
                                    class="form-control login-form-inputs"
                                    type="password"
                                    v-model="form.password"
                                />

                                <div class="text-danger pt-2">
                                    {{ errors.password }}
                                </div>
                            </div>
                            <div class="mb-3 mt-0">
                                <div
                                    @click="login"
                                    class="btn advert-section-div-button mt-2 py-4 px-3 btn-block d-flex justify-content-between"
                                >
                                    <span class="pr-4">Login</span>

                                    <img src="arrow-right.png" width="40" />
                                </div>

                                <Link
                                    href="/forgot-password"
                                    class="m-2 mt-3 text-primary float-end forgot-password-text"
                                >
                                    Forgot your password?
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </SideModal>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import ForgotPasswordComponent from "./ForgotPassword.vue";
import SideModal from "@/Layouts/SideModal.vue";

import { useFilterStore } from "../../stores/filter";

import { predictionsFilterStore } from "../../stores/predictionsFilter";
//import { predictionsFilterDetailedStore } from "../../stores/predictionFiltersDetailed";

export default {
    layout: null,
    components: {
        Link,
        Head,
        ForgotPasswordComponent,
        SideModal,
    },
    data() {
        return {
            showing: false,
            content: {
                create: {
                    title: "",
                },
            },
            form: {},

            errors: {
                email: "",
                password: "",
            },
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post("/login", this.form);

                if (response.data.success) {
                    this.$inertia.visit("/dashboard", {
                        method: "get",
                    });
                }
            } catch (err) {
                const res = err.response.data.errors;
                this.errors = {
                    email: res?.email || "",
                    password: res?.password || "",
                };
            }
        },
    },

    created() {
        const filterStore = useFilterStore();
        filterStore.date = [
            "2023-01-01T08:54:00.000Z",
            "2023-08-26T08:54:00.000Z",
        ];
        filterStore.sentimentTypes = ["neutral", "positive", "negative"];

        //prediction filters
        const filterPredictions = predictionsFilterStore();
        filterPredictions.siteNames = [
            "PORT ELIZABETH",
            "CONSTANTIABERG",
            "JOHANNESBURG",
        ];
        filterPredictions.date = [
            "2023-01-01T08:54:00.000Z",
            "2023-12-26T08:54:00.000Z",
        ];

        // //prediction detailevue filters
        // const filterPredictionsDetailed = predictionsFilterDetailedStore();
        // filterPredictionsDetailed.siteNames = [
        //     "PORT ELIZABETH",
        //     "CONSTANTIABERG",
        //     "JOHANNESBURG",
        // ];
        // filterPredictionsDetailed.alarmFlag = ["Normal", "Alarm", "Pre Alarm"];
        // filterPredictionsDetailed.classification = ["Platinum", "Gold"];
    },
};
</script>

<style lang="scss" scoped>
label {
    color: #706f6f;
    font-size: 20px;
    font-weight: 300;
}

.h1-login {
    font-weight: 700;
    font-size: 50px;
}

.advert-section-div-button {
    color: #ffffff;
    font-size: 20px;
    border-radius: 15px;
    height: 68px;
    font-weight: 300;
    background: #144f9f;
}

.login-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100vh;
    background: #144f9f;
    // NEST
    .login-container {
        width: 650px;
        max-width: 100%;
        background-color: #fff;
        padding: 40px;
    }

    h2 {
        margin-top: 0;
        margin-bottom: 25px;
        text-align: center;
    }

    .cm-logo {
        text-align: center;
        // NEST
        img {
            width: 350px;
        }
    }
}
.nav-link {
    color: #fff !important;
    .fa.fa-user {
        padding-right: 10px !important;
    }
}
.form-label {
    font-size: 18px !important;
}

.login-form-inputs {
    border: 1px solid #d1cdcd;
    border-radius: 15px;
    height: 65px;
}
.forgot-password-text {
    font-size: 18px !important;
    font-weight: 300;
}

.text-danger {
    font-weight: 300;
}
</style>

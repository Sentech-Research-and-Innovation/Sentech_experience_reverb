<template>
    <Head :title="'Login'"><title>Forgot Password</title></Head>
    <div class="login-wrapper">
        <div class="inner-wrapper">
            <div class="cm-logo">
                <img src="white-logo.png" alt="CreditMate Logo" />
            </div>
            <div class="login-container shadow">
                <h5 class="h1-login">Forgot Password</h5>
                <div
                    v-show="successAlert"
                    class="alert alert-success"
                    role="alert"
                >
                    A password reset link has been sent to your email
                </div>
                <div
                    v-show="errorAlert"
                    class="alert alert-danger"
                    role="alert"
                >
                    Error, User not registered
                </div>
                <form @submit.prevent="submit">
                    <div class="row" v-if="!loading">
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                >Email address</label
                            >
                            <input
                                type="email"
                                class="form-control login-form-inputs"
                                v-model="email"
                                id="email"
                            />
                            <div v-show="emailError" class="text-danger">
                                Invalid Eamil Address
                            </div>
                        </div>

                        <div class="mb-3">
                            <div
                                class="btn btn-primary sentech-login-button d-flex justify-content-between align-items-center"
                                @click="suibmit"
                            >
                                <span>Submit</span>
                                <img src="arrow-right.png" />
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="d-flex justify-content-center">
                            <div class="col-1">
                                <img :src="LoadingGif" height="50" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import axios from "axios";

import LoadingGif from "../../assets/loading.gif";

export default {
    layout: null,
    components: {
        Link,
        Head,
    },
    data() {
        return {
            LoadingGif,
            email: "",
            showPassword: false,
            form: {
                processing: false,
                password: false,
            },
            response: {
                errorBag: {
                    email: false,
                },
            },

            successAlert: false,
            errorAlert: false,
            emailError: false,
            loading: false,
        };
    },
    methods: {
        suibmit() {
            (this.loading = true),
                axios
                    .post("/forgot-password", { email: this.email })
                    .then((res) => {
                        if (res.status == 200) {
                            this.successAlert = true;
                            this.errorAlert = false;
                            this.emailError = false;
                            this.loading = false;
                        }
                    })
                    .catch((err) => {
                        let error = err.response.status;
                        console.log(error);
                        if (error == 404) {
                            this.successAlert = false;
                            this.errorAlert = true;
                            this.emailError = false;
                            this.loading = false;
                        }
                        if (error == 422) {
                            this.emailError = true;
                            this.successAlert = false;
                            this.errorAlert = false;
                            this.loading = false;
                        }
                    });
        },
    },
};
</script>

<style lang="scss" scoped>
span {
    font-size: 30px;
    margin: 20px;
}

label {
    color: #706f6f;
    font-size: 20px;
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
</style>

<template>
    <Head :title="'Login'"><title>Login</title></Head>
    <div class="login-wrapper">
        <div class="inner-wrapper">
            <div class="cm-logo">
                <img src="white-logo.png" alt="CreditMate Logo" />
            </div>
            <div class="login-container shadow">
                <h1 class="h1-login">Login</h1>
                <form @submit.prevent="submit">
                    <div class="row">
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
                            <div class="text-danger">{{ errors.email }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                >Password</label
                            >
                            <input
                                class="form-control"
                                type="password"
                                v-model="form.password"
                            />

                            <div class="text-danger">{{ errors.password }}</div>
                        </div>
                        <div class="mb-3">
                            <div
                                class="btn btn-primary sentech-login-button d-flex justify-content-between align-items-center"
                                @click="login"
                            >
                                <span>Login</span>
                                <img src="arrow-right.png" />
                            </div>

                            <Link
                                href="/forgot-password"
                                class="m-2 text-primary float-end forgot-password-text"
                            >
                                Forgot your password?
                            </Link>
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
import ForgotPasswordComponent from "./ForgotPassword.vue";

export default {
    layout: null,
    components: {
        Link,
        Head,
        ForgotPasswordComponent,
    },
    data() {
        return {
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

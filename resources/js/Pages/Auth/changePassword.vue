<template>
    <Head :title="'Password Reset'"><title>Password Reset</title></Head>
    <WebLayout>
        <div class="sentech-index-page">
            <div class="container">
                <div class="sentech-index-page">
                    <div class="d-flex justify-content-center">
                        <div class="alert alert-success col-6" v-if="success">
                            You have successfully changed your password, you can
                            now login
                        </div>
                    </div>
                    <div class="d-flex justify-content-center" v-if="!success">
                        <div class="col-lg-8 py-5 white-container">
                            <div class="d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div
                                        class="alert alert-danger"
                                        v-if="errors.expiredTokenMessage"
                                    >
                                        {{ errors.expiredTokenMessage }}
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="emailAdress"
                                            class="form-label"
                                            >Email address</label
                                        >
                                        <input
                                            type="email"
                                            v-model="form.email"
                                            class="form-control login-form-inputs"
                                        />
                                        <div class="error">
                                            {{ errors.email }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label"
                                            >Password</label
                                        >
                                        <input
                                            type="password"
                                            class="form-control login-form-inputs"
                                            v-model="form.password"
                                        />
                                        <div class="error">
                                            {{ errors.password }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="confirm_password"
                                            class="form-label"
                                            >Confirm Password</label
                                        >
                                        <input
                                            type="password"
                                            class="form-control login-form-inputs"
                                            v-model="form.password_confirmation"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <button
                                            class="btn btn-block btn-primary fs-4"
                                            @click="submit()"
                                        >
                                            Submit
                                        </button>
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

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import { defineComponent, onMounted, ref } from "vue";

export default defineComponent({
    components: {
        WebLayout,
        Head,
        Link,
    },

    setup() {
        const token = ref("");

        const form = ref({});
        const errors = ref({});
        const success = ref(false);

        const submit = async () => {
            form.value.token = token.value;

            try {
                await axios.post(`/reset-password`, form.value);
                success.value = true;
            } catch (err) {
                const res = err.response.data.errors;

                errors.value = {
                    email: res?.email?.[0] || "",
                    password: res?.password?.[0] || "",
                };

                if (err.response.status == 404) {
                    errors.value = {
                        expiredTokenMessage: err.response.data.message || "",
                    };
                }
            }
        };
        onMounted(async () => {
            // Get the current URL
            const urlString = window.location.href;

            const tokenRegex = /token\?=(.*)/;

            const match = tokenRegex.exec(urlString);

            token.value = match && match[1];
        });

        return { token, form, submit, errors, success };
    },
});
</script>
<style scoped>
.sentech-index-page {
    padding-top: 100px;
    min-height: 100vh;
}
.error {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
.white-container {
    background-color: white;
    border-radius: 20px;
}

.form-label {
    font-size: 15px !important;
    font-weight: 500 !important;
}
.login-form-inputs {
    border: 1px solid #d1cdcd;
    border-radius: 8px;
}
label {
    color: #706f6f;
    font-size: 20px;
}
</style>

<template>
    <Head title="Contact Us" />
    <WebLayout>
        <div class="sentech-index-page">
            <div class="container">
                <div class="sentech-index-page">
                    <div class="d-flex justify-content-center">
                        <div
                            class="alert alert-success col-6 text-center"
                            v-if="success"
                        >
                            Thank you for your comment
                        </div>
                    </div>

                    <div
                        class="d-flex justify-content-center sd"
                        v-if="!success"
                    >
                        <div class="col-lg-8 py-5 white-container">
                            <div class="d-flex justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label
                                            for="confirm_password"
                                            class="form-label"
                                            >Name</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control login-form-inputs"
                                            v-model="form.name"
                                        />
                                        <div class="error">
                                            {{ errors.name }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="confirm_password"
                                            class="form-label"
                                            >Email Address</label
                                        >

                                        <input
                                            type="email"
                                            class="form-control login-form-inputs"
                                            v-model="form.email"
                                        />
                                        <div class="error">
                                            {{ errors.email }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="form-label"
                                            >Comment</label
                                        >

                                        <textarea
                                            class="form-control login-form-inputs"
                                            rows="4"
                                            v-model="form.comment"
                                        ></textarea>

                                        <div class="error">
                                            {{ errors.comment }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button
                                            class="btn btn-block btn-primary fs-4"
                                            @click="submit()"
                                        >
                                            Send
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
import axios from "axios";

export default defineComponent({
    components: {
        WebLayout,
        Head,
        Link,
    },

    setup() {
        const email = ref("");

        const name = ref("");
        const comment = ref("");
        const form = ref({
            email: "",
            name: "",
            comment: "",
        });
        const errors = ref({});
        const success = ref(false);

        const submit = async () => {
            try {
                await axios.post(`/feedback`, form.value);
                success.value = true;
            } catch (err) {
                if (err.response) {
                    const res = err.response.data.errors;

                    errors.value = {
                        name: res?.name?.[0] || "",
                        comment: res?.comment?.[0] || "",
                        email: res?.email?.[0] || "",
                    };
                } else {
                    errors.value.networkError =
                        "Network error occurred. Please try again.";
                }
            }
        };

        return { comment, name, email, form, submit, errors, success };
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
Uncaught SyntaxError: Unexpected token '}'Uncaught SyntaxError: Unexpected token '}'

<template>
    <Head :title="'Sign Up'"><title>Home</title></Head>
    <!-- <button type="button" class="button button-dark" @click="showing = true">
        Create {{ showing }}
    </button> -->
    <a @click="showing = true" style="cursor: pointer !important">
        <div class="request-an-account-button py-3 px-2">
            Request an Account
        </div>
    </a>
    <SideModal
        :content="content"
        :showing="showing"
        @hideModal="showing = false"
    >
        <div class="col-12 px-lg-5 px-3" v-if="!registered">
            <div class="h1-login pb-lg-5 pb-3">Request An Account</div>
            <div class="row register-form">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="firstName" class="form-label"
                            >First Name</label
                        >
                        <input
                            type="text"
                            class="form-control login-form-inputs"
                            v-model="form.firstName"
                        />
                        <div class="text-danger">
                            {{ errors.firstName[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastName" class="form-label"
                            >Last Name</label
                        >
                        <input
                            type="text"
                            class="form-control login-form-inputs"
                            v-model="form.lastName"
                        />
                        <div class="text-danger">
                            {{ errors.lastName[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="text"
                            class="form-control login-form-inputs"
                            v-model="form.email"
                        />
                        <div class="text-danger">
                            {{ errors.email[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="companyName" class="form-label"
                            >Company Name</label
                        >
                        <input
                            type="text"
                            class="form-control login-form-inputs"
                            v-model="form.companyName"
                        />
                        <div class="text-danger">
                            {{ errors.companyName[0] }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="position" class="form-label"
                            >Position</label
                        >
                        <input
                            type="text"
                            class="form-control login-form-inputs"
                            v-model="form.position"
                        />
                        <div class="text-danger">
                            {{ errors.position[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label"
                            >Phone Number</label
                        >
                        <input
                            type="text"
                            class="form-control login-form-inputs"
                            v-model="form.phoneNumber"
                        />
                        <div class="text-danger">
                            {{ errors.phoneNumber[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-12 pt-3">
                    <div
                        class="btn btn-primary sentech-login-button d-flex justify-content-between align-items-center"
                        @click="register"
                    >
                        Submit
                        <img src="arrow-right.png" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" v-else>
            <el-alert
                title="Success"
                description="Thank you for requesting  account, we will review and get back to you"
                type="success"
                show-icon
                @click="registered = false"
            />
        </div>
    </SideModal>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import SideModal from "@/Layouts/SideModal.vue";

export default {
    layout: null,
    components: {
        Link,
        Head,
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
                firstName: "",
                lastName: "",
                position: "",
                phoneNumber: "",
                companyName: "",
            },

            registered: false,
        };
    },
    methods: {
        async register() {
            try {
                const response = await axios.post("/register", this.form);

                if (response.data.status === true) {
                    this.registered = true;
                    this.form = {};
                }
            } catch (err) {
                const res = err.response.data.errors;
                this.errors = {
                    email: res?.email || "",
                    firstName: res?.firstName || "",
                    lastName: res?.lastName || "",
                    position: res?.position || "",
                    phoneNumber: res?.phoneNumber || "",
                    companyName: res?.companyName || "",
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

.h1-login {
    font-weight: 700;
    font-size: 50px;
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
.nav-link {
    color: #fff !important;
    .fa.fa-user {
        padding-right: 10px !important;
    }
}
.form-label {
    font-size: 15px;
    font-weight: 500 !important;
}

.request-an-account-button {
    border: 2px solid #fff;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 300 !important;
}

.login-form-inputs {
    border: 1px solid #d1cdcd;
    border-radius: 8px;
}

@media only screen and (max-width: 1199px) {
    .h1-login {
        font-weight: 700;
        font-size: 20px;
    }
    .form-label {
        font-size: 11px;
    }
    .text-danger {
        font-size: 10px !important;
        padding-top: 8px !important;
    }
    .form-group {
        padding: 0px !important;
        margin-bottom: 10px !important;
    }
}
</style>

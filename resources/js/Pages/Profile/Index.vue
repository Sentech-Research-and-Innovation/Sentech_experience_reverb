<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="col-12 px-3 mx-0 pt-2">
        <div class="row px-0 pb-3">
            <div class="col-12 px-4">
                <h2>Profile</h2>
            </div>
        </div>

        <div class="row px-4">
            <div class="col-3 rounded py-4 shadow-border px-0 mx-0">
                <div class="col-12 text-center mb-4">
                    <el-avatar
                        :icon="UserFilled"
                        style="
                            color: #144f9f;
                            font-size: 30px;
                            background-color: #ebebeb;
                        "
                        :size="80"
                    />
                    <h3 class="pt-4" style="color: #000000 !important">
                        {{ user.first_name }} {{ user.last_name }}
                    </h3>
                    <span class="fs-6" style="color: #000000 !important">{{
                        user.company.company_name
                    }}</span
                    ><br />
                    <span style="color: #000000 !important; font-size: 15px">{{
                        user.roles[0].name
                    }}</span>
                </div>
                <div class="col-12 text-start border-profile mx-0 px-4 py-4">
                    {{ user.email }}
                </div>
                <div class="col-12 text-start border-profile mx-0 px-4 py-4">
                    {{ user.phoneNumber }}
                </div>
            </div>
            <div class="col-8 rounded shadow-border px-4 ml-5">
                <div class="profile-nav-header col-12 px-0">
                    <nav
                        class="nav nav-pills flex-column flex-sm-row py-3 mb-4"
                    >
                        <a
                            class="flex-sm-fill text-sm-center nav-link py-3"
                            :class="{
                                active: page === 'profile',
                            }"
                            @click="page = 'profile'"
                            ><strong>Profile Details</strong>
                        </a>

                        <a
                            class="flex-sm-fill text-sm-center nav-link py-3"
                            :class="{
                                active: page === 'password',
                            }"
                            @click="page = 'password'"
                            ><strong>Change Password</strong>
                        </a>
                    </nav>
                </div>
                <div class="row register-form" v-if="page == 'profile'">
                    <div class="col-md-12" v-if="success">
                        <div class="alert alert-success">
                            You have successfully updated your details
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="firstName" class="form-label"
                                >First Name</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="user.first_name"
                            />
                            <div class="text-danger pt-2">
                                {{ errors.first_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="lastName" class="form-label"
                                >Last Name</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="user.last_name"
                            />
                            <div class="text-danger pt-2">
                                {{ errors.last_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phoneNumber" class="form-label"
                                >Phone Number</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="user.phoneNumber"
                            />
                            <div class="text-danger pt-2">
                                {{ errors.phoneNumber }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div
                                @click="updateDetails"
                                class="btn btn-primary sentech-login-button d-flex justify-content-center align-items-center"
                            >
                                Submit
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row register-form" v-if="page == 'password'">
                    <div class="col-md-12" v-if="successPassword">
                        <div class="alert alert-success">
                            You have successfully updated your password
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Password" class="form-label"
                                >Password</label
                            >
                            <input
                                type="password"
                                class="form-control login-form-inputs"
                                v-model="formPassword.password"
                            />
                            <div class="text-danger pt-2">
                                {{ errorPassword.password }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label"
                                >Confirm Password</label
                            >
                            <input
                                type="password"
                                class="form-control login-form-inputs"
                                v-model="formPassword.password_confirmation"
                            />
                            <div class="text-danger"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div
                                @click="changePassword"
                                class="btn btn-primary sentech-login-button d-flex justify-content-center align-items-center"
                            >
                                Submit
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { UserFilled } from "@element-plus/icons-vue";

export default defineComponent({
    layout: AdminLayout,
    name: "index",

    components: {
        Head,
        Link,
    },

    props: {
        user: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { user } = props;
        const form = ref({});
        const errors = ref({});
        const page = ref("profile");
        const success = ref(false);
        const successPassword = ref(false);
        const errorPassword = ref({});
        const formPassword = ref({});

        const updateDetails = async () => {
            errors.value = {};
            form.value = {
                first_name: user.first_name,
                last_name: user.last_name,
                phoneNumber: user.phoneNumber,
            };

            try {
                await axios.post(`/profile/update`, form.value);
                errors.value = {};
                success.value = true;
            } catch (err) {
                const res = err.response.data.errors;
                success.value = false;
                errors.value = {
                    first_name: res?.first_name?.[0] || "",
                    last_name: res?.last_name?.[0] || "",
                    phoneNumber: res?.phoneNumber?.[0] || "",
                };
            }
        };

        const changePassword = async () => {
            console.log(formPassword.value);

            try {
                await axios.post(
                    `/profile/update/password`,
                    formPassword.value
                );
                errorPassword.value = {};
                successPassword.value = true;
            } catch (err) {
                const res = err.response.data.errors;
                successPassword.value = false;
                errorPassword.value = {
                    password: res?.password?.[0] || "",
                };
            }
        };
        return {
            UserFilled,
            page,
            user,
            updateDetails,
            errors,
            success,
            changePassword,
            formPassword,
            errorPassword,
            successPassword,
        };
    },
});
</script>
<style scoped>
.border-profile {
    border-top: 1px solid #c7cdd2;
    color: #000000 !important;
}
.profile-nav-header {
    background-color: #ffff;
    cursor: pointer;
}
.profile-nav-header .nav-link {
    color: #c5c2c2;
    background: none !important;
    border: none !important;
    border-bottom: 2px solid #ebe8e8 !important;
    border-radius: 0px;
}

.profile-nav-header .active {
    color: #144f9f;
    background: none !important;
    border: none !important;
    border-bottom: 2px solid #144f9f !important;
    border-radius: 0px;
}
</style>

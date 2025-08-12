<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="page-wrapper">
        <div class="col-12 px-0 mx-0 profile-container">
            <!-- Background Cover Image -->
            <div
                class="cover-image"
                :style="{
                    backgroundImage: `url('${user.coverImage || defaultCover}')`
                }"
            ></div>
            
            <div class="profile-content px-4">
                <!-- Profile Picture -->
                <div class="profile-picture-container">
                    <el-avatar
                        :src="user.profile_picture || defaultProfile"
                        :icon="!user.profile_picture ? UserFilled : ''"
                        style="
                            width: 150px;
                            height: 150px;
                            font-size: 60px;
                            background-color: #1f1f1f;
                            color: #fff;
                        "
                    />
                </div>
                
                <!-- Profile Info -->
                <div class="profile-info mt-4">
                    <p class="profile-name">
                        {{ user.first_name }} {{ user.last_name }}
                    </p>
                        <p class="profile-title">
                            {{ user.roles[0]?.name }} at {{ user.company?.company_name }}
                        </p>
                        
                        <div class="profile-contact mt-1">
                            <span class="contact-info">
                                {{ user.email || 'Email not provided' }}, {{ user.phoneNumber || 'Phone not provided' }}
                            </span>
                        </div>

                    </div>
            </div>
        </div>


        <!-- Form section -->

        <div class="col-lg-9 col-xl-9 col-md-9 rounded pt-4">
                <div class="col-12 rounded py-4 shadow-border">
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

                                <el-input
                                    v-model="user.first_name"
                                    class="w-100 search-input"
                                />

                                <div class="text-danger pt-1">
                                    {{ errors.first_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="lastName" class="form-label"
                                    >Last Name</label
                                >

                                <el-input
                                    v-model="user.last_name"
                                    class="w-100 search-input"
                                />

                                <div class="text-danger pt-1">
                                    {{ errors.last_name }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phoneNumber" class="form-label"
                                    >Phone Number</label
                                >

                                <el-input
                                    v-model="user.phoneNumber"
                                    class="w-100 search-input"
                                />

                                <div class="text-danger pt-1">
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

                                <el-input
                                    v-model="formPassword.password"
                                    class="w-100 search-input"
                                />

                                <div class="text-danger pt-1">
                                    {{ errorPassword.password }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirmPassword" class="form-label"
                                    >Confirm Password</label
                                >

                                <el-input
                                    v-model="formPassword.password_confirmation"
                                    class="w-100 search-input"
                                />

                                <div class="text-danger pt-1"></div>
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
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { UserFilled } from "@element-plus/icons-vue";

export default defineComponent({
    layout: AdminLayout,
    name: "profile-view",

    components: {
        Head,
        Link,
    },

    props: {
        user: {
            type: Object,
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
        
        const defaultCover =
            "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6?q=80&w=1920&fit=crop"; 
        // dark abstract background

        const defaultProfile =
            "https://images.unsplash.com/photo-1603415526960-f8f0a2b52f75?q=80&w=200&fit=crop"; 
        // dark neutral gradient profile placeholder

        return {
            UserFilled,
            user: props.user,
            defaultCover,
            defaultProfile,
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
.page-wrapper {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.profile-container {
    position: relative;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    width: 85%;
    max-width: 1000px;
}

.cover-image {
    height: 200px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-size: cover;
    background-position: center;
    filter: brightness(0.85); /* makes it darker */
}

.profile-content {
    position: relative;
    padding-bottom: 30px;
}

.profile-picture-container {
    position: absolute;
    top: -75px;
    left: 20px;
    border: 4px solid #ffffff;
    border-radius: 50%;
    background-color: #1f1f1f;
}

.profile-info {
    padding-top: 90px;
}

.profile-name {
    font-size: 27px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #050505;
}

.profile-title {
    font-size: 15px;
    font-weight: 300;
    color: #050505;
    margin-bottom: 5px;
}

.profile-contact {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #65676b;
}

.contact-item {
    color: #1877f2;
}

.contact-info {
    font-size: 12px;
    color: #65676b;
}

.contact-separator {
    color: #65676b;
}
.border-profile {
    border-top: 1px solid #c7cdd2;
    color: #000000 !important;
}
.profile-nav-header {
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

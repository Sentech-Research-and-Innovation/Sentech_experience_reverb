<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="col-12 px-3 mx-0 pt-2">
        <div class="row pb-0">
            <div class="col-12 px-4">
                <h2>Profile</h2>
            </div>
        </div>

        <div class="row px-3">
            <div class="col-lg-3 col-xl-3 col-md-3 pt-4">
                <div class="col-12 rounded py-4 shadow-border">
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
                        <h4 class="pt-4 form-label">
                            {{ user.first_name }} {{ user.last_name }}
                        </h4>
                        <span class="fs-6">{{ user.company.company_name }}</span
                        ><br />
                        <span style="font-size: 15px">{{
                            user.roles[0].name
                        }}</span>
                    </div>
                    <div
                        class="col-12 text-start border-profile mx-0 px-4 pt-3 pb-3"
                    >
                        <h4 class="form-label">{{ user.email }}</h4>
                    </div>
                    <div
                        class="col-12 text-start border-profile mx-0 px-4 pt-3 pb-3"
                    >
                        <h4 class="form-label">{{ user.phoneNumber }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-9 col-md-9 rounded pt-4">
                <div class="col-12 rounded py-4 shadow-border">
                    <div class="profile-nav-header col-12 px-0">
                        <nav
                            class="nav nav-pills flex-column flex-sm-row py-3 mb-4"
                        >
                            <a
                                class="flex-sm-fill text-sm-center nav-link py-3 active"
                                ><strong>Profile Details</strong>
                            </a>
                        </nav>
                    </div>
                    <div class="row register-form">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="firstName" class="form-label"
                                    >First Name</label
                                >
                                <h4>{{ user.first_name }}</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="lastName" class="form-label"
                                    >Last Name</label
                                >
                                <h4>{{ user.last_name }}</h4>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phoneNumber" class="form-label"
                                    >Phone Number</label
                                >
                                <h4>{{ user.phoneNumber }}</h4>
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
    name: "index",

    components: {
        Head,
    },

    props: {
        user: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        return {
            UserFilled,
            user: props.user,
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

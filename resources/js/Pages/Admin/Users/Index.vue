<template>
    <Head :title="'Users'"><title>Users</title></Head>
    <div class="col-12 mt-5">
        <!-- Users List Table -->
        <div class="row px-0 pb-3">
            <div class="col-6 px-4">
                <h2>Users</h2>
            </div>
            <div class="col-6"><CreateUser v-if="can('users-create')" /></div>
        </div>

        <div class="company-nav-header col-12 px-0">
            <NaviigationUsers />
        </div>

        <div class="col-12 pt-2">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col table-cell">
                            <span class="table-cell">#</span>
                        </th>
                        <th scope="col">
                            <span class="table-cell">Name & Surname</span>
                        </th>
                        <th scope="col">
                            <span class="table-cell">Email Address</span>
                        </th>
                        <th scope="col">
                            <span class="table-cell">Role</span>
                        </th>
                        <th scope="col">
                            <span class="table-cell">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <th scope="row">
                            <span class="table-cell">{{ index + 1 }}</span>
                        </th>
                        <td>
                            <a 
                                :href="`/profile/${user.id}`"
                                class="table-cell text-primary link-style"
                                style="cursor: pointer;">
                                {{ user.first_name }} {{ user.last_name }}
                            </a>
                        </td>
                        <td>
                            <span class="table-cell">{{ user.email }}</span>
                        </td>
                        <td>
                            <span class="table-cell">
                                {{ user.roles[0]?.name }}
                            </span>
                        </td>
                        <td>
                            <div class="row">
                                <div class="pt-1 col-4">
                                    <EditRole
                                        :userId="user.id"
                                        v-if="can('users-update')"
                                    />
                                </div>
                                <div class="col-4 pt-1 text-start">
                                    <DeleteUser
                                        :userId="user.id"
                                        v-if="can('users-update')"
                                    />
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- User Profile Display -->
        <div class="row px-3 mt-5" v-if="selectedUser">
            <div class="col-12">
                <h2>User Profile</h2>
            </div>
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
                            {{ selectedUser.first_name }} {{ selectedUser.last_name }}
                        </h4>
                        <span class="fs-6">{{ selectedUser.company?.company_name }}</span
                        ><br />
                        <span style="font-size: 15px">{{
                            selectedUser.roles[0]?.name
                        }}</span>
                    </div>
                    <div
                        class="col-12 text-start border-profile mx-0 px-4 pt-3 pb-3"
                    >
                        <h4 class="form-label">{{ selectedUser.email }}</h4>
                    </div>
                    <div
                        class="col-12 text-start border-profile mx-0 px-4 pt-3 pb-3"
                    >
                        <h4 class="form-label">{{ selectedUser.phoneNumber }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-xl-9 col-md-9 rounded pt-4">
                <div class="col-12 rounded py-4 shadow-border">
                    <!-- Empty right panel as requested -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, ref } from "vue";
import EditRole from "./EditRole.vue";
import CreateUser from "./CreateUser.vue";
import DeleteUser from "./DeleteUser.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { UserFilled, Edit } from "@element-plus/icons-vue";

import NaviigationUsers from "../../../Layouts/Partials/companies/Naviigation_users.vue";

export default defineComponent({
    name: "users-list",
    layout: AdminLayout,

    components: { EditRole, CreateUser, DeleteUser, Head, Link, NaviigationUsers },

    props: {
        users: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { users } = props;
        const selectedUser = ref(null);

        const showUserProfile = (user) => {
            selectedUser.value = user;
            // Scroll to the profile section
            setTimeout(() => {
                const element = document.querySelector('.mt-5');
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }, 100);
        };

        return {
            users,
            selectedUser,
            showUserProfile,
            UserFilled,
            Edit,
        };
    },
});
</script>

<style>
.form-control-error {
    border-radius: 1px solid #ff1744 !important;
}

.link-style {
    font-weight: bold; /* Bold by default */
    text-decoration: none; /* Remove underline */
}

.link-style:hover {
    text-decoration: underline; /* Underline on hover */
}
    
.border-profile {
    border-top: 1px solid #c7cdd2;
    color: #000000 !important;
}
.shadow-border {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.25rem;
}
</style>

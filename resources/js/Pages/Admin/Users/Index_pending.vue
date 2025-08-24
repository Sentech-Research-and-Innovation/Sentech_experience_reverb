<template>
    <Head :title="'Users'"><title>Users</title></Head>
    <div class="col-12 mt-5">
        <!-- Users List Table -->
        <div class="row px-0 pb-3">
            <div class="col-6 px-4">
                <h2>Pending Users</h2>
            </div>
            <div class="col-6"><CreateUser v-if="can('users-create')" /></div>
        </div>

        <div class="company-nav-header col-12">
            <NaviigationUsers />
        </div>

        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name & Surname</th>
                        <th>Email Address</th>
                        <th>Role</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>
                            <a 
                                :href="`/profile/${user.id}`"
                                class="table-cell text-primary link-style"
                                style="cursor: pointer;">
                                {{ user.first_name }} {{ user.last_name }}
                            </a>
                        </td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.roles[0]?.name }}</td>
                        <td style="text-align: center;">
                            <div class="d-flex justify-content-center gap-2">
                                <!-- Resend Approval -->
                                <el-button
                                    @click="approve(user.id, user.email)"
                                >
                                    Resend Approval Email
                                </el-button>

                                <!-- Delete User -->
                                <el-popconfirm
                                    confirm-button-text="Yes"
                                    cancel-button-text="No"
                                    :icon="InfoFilled"
                                    icon-color="#f44336"
                                    title="Are you sure to delete this user?"
                                    @confirm="decline(user.id)"
                                >
                                    <template #reference>
                                        <el-button type="danger">
                                            Delete
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent } from "vue";
import CreateUser from "./CreateUser.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import NaviigationUsers from "../../../Layouts/Partials/companies/Naviigation_users.vue";
import { InfoFilled } from "@element-plus/icons-vue";
import axios from "axios";

export default defineComponent({
    name: "users-list",
    layout: AdminLayout,

    components: { CreateUser, Head, Link, NaviigationUsers },

    props: {
        users: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { users } = props;

        // Resend approval email for a user
        const approve = async (userId, email) => {
            try {
                const res = await axios.post(`/users/approve/${userId}`, { email });
                if (res.status === 200) {
                    location.reload();
                }
            } catch (err) {
                console.error("Approval failed", err);
            }
        };

        // Delete user
        const decline = async (userId) => {
            try {
                const res = await axios.post(`/admin/user/delete/${userId}`);
                if (res.status === 200) {
                    location.reload();
                }
            } catch (err) {
                console.error("Deletion failed", err);
            }
        };

        return {
            users,
            approve,
            decline,
            InfoFilled,
        };
    },
});
</script>

<style>
.link-style {
    font-weight: bold;
    text-decoration: none;
}

.link-style:hover {
    text-decoration: underline;
}

.d-flex {
    display: flex;
}

.gap-2 {
    gap: 8px;
}
</style>

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

        <div class="company-nav-header col-12">
            <NaviigationUsers />
        </div>

        <div class="col-12" v-if="!loading">
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
                        <th scope="col" style="text-align: center;">
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
                                    @confirm="decline(user.id, user.email)"
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

        <!-- Loading spinner -->
        <div
            class="col-12 px-0 mx-0 text-center"
            v-else
            style="height: 300px; padding-top: 100px"
        >
            <img :src="LoadingGif" width="50" />
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, ref } from "vue";
import CreateUser from "./CreateUser.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import LoadingGif from "../../assets/loading.gif";
import NaviigationUsers from "../../../Layouts/Partials/companies/Naviigation_users.vue";
import { InfoFilled } from "@element-plus/icons-vue";

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
        const loading = ref(false);

        // Resend approval email for a user
        const approve = async (userId, email) => {
            loading.value = true;
            try {
                await axios.post(`/users/approve/${userId}`, { email });
                location.reload();
            } catch (err) {
                loading.value = false;
            }
        };

        // Delete user
        const decline = async (userId, email) => {
            loading.value = true;
            try {
                await axios.post(`/admin/user/delete/${userId}`);
                location.reload();
            } catch (err) {
                loading.value = false;
            }
        };

        return {
            users,
            approve,
            decline,
            InfoFilled,
            loading,
            LoadingGif,
        };
    },
});
</script>

<style>
.form-control-error {
    border-radius: 1px solid #ff1744 !important;
}

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

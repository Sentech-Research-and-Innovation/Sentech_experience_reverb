<template>
    <Head :title="'Users'"><title>Users</title></Head>
    <div class="col-12 mt-5">
        <div class="row px-0 pb-3">
            <div class="col-6 px-4">
                <h2>Users</h2>
            </div>
            <div class="col-6"><CreateUser v-if="can('users-create')" /></div>
        </div>
        <div class="col-12 pt-2">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col table-cell">
                            <span class="table-cell">#</span>
                        </th>
                        <th scope="col">
                            <span class="table-cell">Name</span>
                        </th>
                        <th scope="col">
                            <span class="table-cell">Surname</span>
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
                            <span class="table-cell">{{
                                user.first_name
                            }}</span>
                        </td>
                        <td>
                            <span class="table-cell">{{ user.last_name }}</span>
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
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent } from "vue";
import EditRole from "./EditRole.vue";
import CreateUser from "./CreateUser.vue";
import DeleteUser from "./DeleteUser.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { UserFilled, Edit } from "@element-plus/icons-vue";

export default defineComponent({
    name: "users-list",
    layout: AdminLayout,

    components: { EditRole, CreateUser, DeleteUser, Head, Link },

    props: {
        users: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { users } = props;

        return {
            users,
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
</style>

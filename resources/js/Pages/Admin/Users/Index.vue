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
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Surname</th>
                        <th scope="col">Email Address</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ user.first_name }}</td>
                        <td>{{ user.last_name }}</td>
                        <td>{{ user.email }}</td>
                        <td>
                            {{ user.roles[0]?.name }}
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

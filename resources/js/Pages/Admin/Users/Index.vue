<template>
    <Head :title="'Users'"><title>Users</title></Head>

    <div class="col-12">
        <h1>Users</h1>
        <div class="col-12 px-4"><CreateUser /></div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
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
                        <td v-for="role in user.roles" :key="role.id">
                            {{ role.name }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <EditRole :userId="user.id" />
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

import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "users-list",
    layout: AdminLayout,

    components: { EditRole, CreateUser, Head, Link },

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
        };
    },
});
</script>

<style>
.form-control-error {
    border-radius: 1px solid #ff1744 !important;
}
</style>

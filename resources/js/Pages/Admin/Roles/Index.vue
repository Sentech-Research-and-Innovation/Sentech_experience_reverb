<template>
    <Head :title="'Roles'"><title>Roles</title></Head>

    <div class="col-12">
        <h1>Roles</h1>
        <div class="col-12 px-4">
            <CreateRole v-if="can('roles-create')" />
        </div>
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Role Name</th>
                        <th scope="col">Created at</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(role, index) in roles" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ role.name }}</td>
                        <td>{{ role.created_at }}</td>
                        <td>
                            <div class="d-flex">
                                <DeleteRole :roleId="role.id" />

                                <ViewRole
                                    v-if="can('roles-update')"
                                    :roleName="role.name"
                                />
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
import CreateRole from "./CreateRole.vue";
import DeleteRole from "./DeleteRole.vue";
import ViewRole from "./ViewRole.vue";

import { defineComponent } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "list-roles",
    layout: AdminLayout,

    components: {
        CreateRole,
        DeleteRole,
        ViewRole,
        Head,
        Link,
    },

    props: {
        roles: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { roles } = props;

        const deleteRole = (id) => {
            console.log(id);
        };
        const editRole = (id) => {
            console.log(id);
        };

        return {
            roles,
            deleteRole,
            editRole,
        };
    },
});
</script>

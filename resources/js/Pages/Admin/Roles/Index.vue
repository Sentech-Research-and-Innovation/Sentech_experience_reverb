<template>
    <Head :title="'Roles'"><title>Roles</title></Head>

    <div class="col-12 px-3 mx-0 pt-5">
        <div class="row px-0 pb-3">
            <div class="col-6 px-4">
                <h2>Roles</h2>
            </div>
            <div class="col-6"><CreateRole v-if="can('roles-create')" /></div>
        </div>
        <div class="col-md-12 px-0 mx-0">
            <table class="table">
                <tbody>
                    <tr v-for="(role, index) in roles" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ extractRole(role.name) }}</td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <div class="col-lg-3 col-6">
                                    <DeleteRole
                                        :roleId="role.id"
                                        v-if="can('roles-delete')"
                                    />
                                </div>

                                <div class="col-lg-3 col-6">
                                    <ViewRole
                                        :roleName="role.name"
                                        v-if="can('roles-update')"
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
        const extractRole = (text) => {
            const parts = text.split("_");
            return parts[0];
        };

        const { roles } = props;

        const deleteRole = (id) => {
            console.log(id);
        };
        const editRole = (id) => {
            console.log(id);
        };

        return {
            extractRole,
            roles,
            deleteRole,
            editRole,
        };
    },
});
</script>

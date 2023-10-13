<template>
    <Head :title="'Users'"><title>Users</title></Head>
    <div class="col-12 mt-5">
        <div class="row px-0 pb-3">
            <div class="col-6 px-4">
                <h2>Users</h2>
            </div>
            <div class="col-6"><CreateUser /></div>
        </div>
        <div class="col-12 py-4 mr-3 rounded" style="background-color: #fff">
            <div class="row">
                <div
                    v-for="(user, index) in users"
                    :key="index"
                    class="col-lg-4 col-12 pl-5 mb-lg-0 mb-3"
                    style="border-right: 2px solid #ebebeb"
                >
                    <div class="d-flex">
                        <div class="mr-4">
                            <el-avatar
                                :icon="UserFilled"
                                style="
                                    color: #144f9f;
                                    font-size: 30px;
                                    background-color: #ebebeb;
                                "
                                :size="60"
                            />
                        </div>
                        <div class="">
                            <div class="fs-lg-5">
                                {{ user.first_name }} {{ user.last_name }}
                            </div>
                            <div class="py-1" style="color: #a2a1a1">
                                {{ user.email }}
                            </div>
                            <div class="d-flex justify-content-between">
                                <div
                                    class="pt-2"
                                    v-for="role in user.roles"
                                    :key="role.id"
                                >
                                    {{ role.name }}
                                </div>
                                <div class="pt-1">
                                    <EditRole :userId="user.id" />
                                </div>
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
import EditRole from "./EditRole.vue";
import CreateUser from "./CreateUser.vue";

import { Head, Link } from "@inertiajs/inertia-vue3";
import { UserFilled, Edit } from "@element-plus/icons-vue";

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

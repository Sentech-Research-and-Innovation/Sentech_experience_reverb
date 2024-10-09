<template>
    <Head :title="'Companies'"><title>Companies</title></Head>

    <div class="col-12 pt-5">
        <div class="row px-0 pb-3">
            <div class="col-6">
                <h2>Companies</h2>
            </div>
            <!-- v-if="can('roles-create')"  -->
            <div class="col-6"></div>
        </div>

        <div class="col-12 px-0 mx-0">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col table-cell">#</th>
                        <th scope="col table-cell">Company Name</th>
                        <th scope="col table-cell">Contact Person</th>
                        <th scope="col table-cell">Contact Person Email</th>
                        <th scope="col table-cell"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(company, index) in companies" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ company.company_name }}</td>
                        <td>{{ company.contact_person?.name }}</td>
                        <td>{{ company.contact_person?.email }}</td>

                        <!-- <td v-for="role in user.roles" :key="role.id">
                            {{ role.name }}
                        </td> -->
                        <td>
                            <!-- <EditRole :userId="user.id" />
                            | delete -->
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
import CreateCompany from "./CreateCompany.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "company-list",
    layout: AdminLayout,

    components: { CreateCompany, Link, Head },

    props: {
        companies: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { companies } = props;

        return {
            companies,
        };
    },
});
</script>

<style>
.form-control-error {
    border-radius: 1px solid #ff1744 !important;
}
.company-nav-header {
    background-color: #ffff;
}
.company-nav-header .nav-link {
    color: #737272;
    font-weight: 500;
    border: 1px solid #737272;
}

.company-nav-header .active {
    background-color: #144f9f !important;
}
</style>

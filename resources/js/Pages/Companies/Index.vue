<template>
    <Head :title="'Companies'"><title>Companies</title></Head>

    <div class="col-12">
        <h1>Companies</h1>
        <div class="col-12 px-4"><CreateCompany /></div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Company Name</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Contact Person Email</th>
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
</style>

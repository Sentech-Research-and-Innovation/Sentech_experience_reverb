<template>
    <Head :title="'Companies'"><title>Companies</title></Head>

    <div class="col-12 pt-3">
        <div class="row px-0 pb-4">
            <div class="col-6">
                <h2>Companies</h2>
            </div>
            <div class="col-6">
                <CreateCompany v-if="can('companies-create_company')" />
            </div>
        </div>

        <div class="company-nav-header col-12 px-0">
            <NaviigationVue />
        </div>
        <div class="col-12 px-0 mx-0 company">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col table-cell">#</th>
                        <th scope="col table-cell">Company Name</th>
                        <th scope="col table-cell">Contact Person</th>
                        <th scope="col table-cell">Position</th>
                        <th scope="col table-cell">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(company, index) in companies" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ company.company_name }}</td>
                        <td>
                            <a 
                                :href="`/profile/${company.contact_person?.id}`"
                                class="table-cell text-primary link-style"
                                style="cursor: pointer;">
                                {{ company.contact_person?.first_name }} {{ company.contact_person?.last_name }}
                            </a>
                        </td>
                        <td>{{ company.contact_person?.position }}</td>
                        <td>
                            <el-popconfirm
                                confirm-button-text="Yes"
                                cancel-button-text="No"
                                :icon="InfoFilled"
                                icon-color="#f44336"
                                title="Are you sure to delete this?"
                                @confirm="
                                    decline(
                                        company.id,
                                        company.contact_person?.email
                                    )
                                "
                            >
                                <template #reference>
                                    <el-button type="danger">
                                        Delete
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref } from "vue";
import CreateCompany from "./CreateCompany.vue";
import { Head, Link } from "@inertiajs/vue3";
import { Search } from "@element-plus/icons-vue";
import { InfoFilled } from "@element-plus/icons-vue";
import NaviigationVue from "../../Layouts/Partials/companies/Naviigation.vue";

export default defineComponent({
    name: "company-list",
    layout: AdminLayout,

    components: { CreateCompany, Link, Head, NaviigationVue },

    props: {
        companies: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const active = ref(true);
        const { companies } = props;

        const decline = async (companyid, email) => {
            loading.value = true;
            try {
                await axios.post(`/organizantions/declineCompany_1/${companyid}`, {
                    email: email,
                });
                location.reload();
            } catch (err) {
                loading.value = false;
            }
        };

        return {
            companies,
            Search,
            active,
            decline,
            InfoFilled
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
</style>

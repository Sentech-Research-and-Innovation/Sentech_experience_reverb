<template>
    <Head :title="'Companies'"><title>Companies</title></Head>

    <div class="col-12 pt-5">
        <div class="row px-0 pb-3">
            <div class="col-6">
                <h2>Companies</h2>
            </div>
            <div class="col-6"><CreateCompany /></div>
        </div>

        <div class="company-nav-header col-12 px-0">
            <NaviigationVue />
        </div>
        <div class="col-12 px-0 mx-0 company" v-if="!loading">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Company Name</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Position</th>
                         <th scope="col" style="text-align: center;">Action</th>
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
                        <td style="text-align: center;>
                            <div class="d-flex gap-2">
                                <el-button
                                    @click="
                                        approve(
                                            company.id,
                                            company.contact_person?.email
                                        )
                                    "
                        
                                >
                                    Resend Approval Email
                                </el-button>

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
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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
import CreateCompany from "./CreateCompany.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import LoadingGif from "../../assets/loading.gif";
import NaviigationVue from "../../Layouts/Partials/companies/Naviigation.vue";

import {
    View,
    CircleCheckFilled,
    CircleCloseFilled,
    InfoFilled,
} from "@element-plus/icons-vue";

export default defineComponent({
    name: "Pending",
    layout: AdminLayout,

    components: { CreateCompany, Link, Head, NaviigationVue },

    props: {
        companies: {
            type: Array,
            required: true,
        },
    },

    setup(props) {
        const { companies } = props;
        const loading = ref(false);

        const approve = async (companyid, email) => {
            loading.value = true;
            try {
                await axios.post(`/organizantions/approve/${companyid}`, {
                    email: email,
                });
                location.reload();
            } catch (err) {
                loading.value = false;
            }
        };

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
            approve,
            decline,
            companies,
            View,
            CircleCheckFilled,
            CircleCloseFilled,
            InfoFilled,
            loading,
            LoadingGif,
        };
    },
});
</script>

<style>
.link-style {
    font-weight: bold;
    text-decoration: none;
    color: #144f9f;
}

.link-style:hover {
     color: #409EFF;
    text-decoration: underline;
}

.d-flex {
    display: flex;
}

.gap-2 {
    gap: 8px;
}
</style>

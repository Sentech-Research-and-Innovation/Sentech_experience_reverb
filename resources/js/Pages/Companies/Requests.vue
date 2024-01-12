<template>
    <Head :title="'Companies'"><title>Companies</title></Head>

    <div class="col-12 pt-5">
        <div class="row px-0 pb-3">
            <div class="col-6">
                <h2>Companies</h2>
            </div>
            <div class="col-6"><CreateCompany /></div>
        </div>

        <div class="company-nav-header col-12 px-0 my-4">
            <nav class="nav nav-pills flex-column flex-sm-row py-3 px-2">
                <Link
                    class="flex-sm-fill text-sm-center nav-link py-3 mx-2"
                    :class="{
                        active: $page.url === '/organizantions/approved',
                    }"
                    href="/organizantions/approved"
                    >Approved
                </Link>
                <Link class="flex-sm-fill text-sm-center nav-link py-3 mx-2"
                    >Pending
                </Link>
                <Link
                    class="flex-sm-fill text-sm-center nav-link py-3 mx-2"
                    :class="{
                        active: $page.url === '/organizantions/request',
                    }"
                    href="/organizantions/request"
                    >Requests
                </Link>
            </nav>
        </div>
        <div class="col-12 px-0 mx-0 company" v-if="!loading">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Company Name</th>
                        <th scope="col">Contact Person</th>
                        <th scope="col">Contact Person Email</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Position</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(company, index) in companies" :key="index">
                        <th scope="row">{{ index + 1 }}</th>
                        <td>{{ company.company_name }}</td>
                        <td>{{ company.contact_person?.first_name }}</td>
                        <td>{{ company.contact_person?.email }}</td>
                        <td>{{ company.contact_person.phoneNumber }}</td>
                        <td>{{ company.contact_person?.position }}</td>
                        <!-- <td v-for="role in user.roles" :key="role.id">
                            {{ role.name }}
                        </td> -->
                        <td>
                            <el-popconfirm
                                confirm-button-text="Yes"
                                cancel-button-text="No"
                                :icon="InfoFilled"
                                icon-color="#626AEF"
                                title="Are you sure to approve this?"
                                @confirm="
                                    approve(
                                        company.id,
                                        company.contact_person?.email
                                    )
                                "
                                @cancel="cancelEvent"
                            >
                                <template #reference>
                                    <el-button
                                        type="success"
                                        syle="color:black !important"
                                        :icon="CircleCheckFilled"
                                        class="fs-5"
                                    />
                                </template>
                            </el-popconfirm>

                            <el-button
                                type="danger"
                                :icon="CircleCloseFilled"
                                class="fs-5"
                                @click="company.centerDialogVisible = true"
                            />
                            <el-dialog
                                v-model="company.centerDialogVisible"
                                :title="'Decline ' + company.company_name"
                                width="40%"
                                center
                            >
                                <span>
                                    <el-form-item label="Reason (optional)">
                                    </el-form-item>
                                    <el-input
                                        type="textarea"
                                        v-model="declineMessage"
                                    />
                                </span>
                                <template #footer>
                                    <span class="dialog-footer">
                                        <el-button
                                            @click="
                                                company.centerDialogVisible = false
                                            "
                                            >Cancel</el-button
                                        >
                                        <el-button
                                            type="danger"
                                            @click="decline(company.id)"
                                        >
                                            Confirm
                                        </el-button>
                                    </span>
                                </template>
                            </el-dialog>
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

import {
    View,
    CircleCheckFilled,
    CircleCloseFilled,
    InfoFilled,
} from "@element-plus/icons-vue";

export default defineComponent({
    name: "Requests",
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
        const loading = ref(false);
        const centerDialogVisible = ref(false);
        const declineMessage = ref("");
        const companyDialogStates = companies.map(() => ref(false));

        const approve = async (companyid, email) => {
            loading.value = true;
            try {
                await axios.post(`/organizantions/approve/${companyid}`, {
                    email: email,
                });
                location.reload();
            } catch (err) {}
        };

        const decline = async (companyid) => {
            console.log(companyid, declineMessage.value);
        };

        return {
            approve,
            companies,
            View,
            CircleCheckFilled,
            CircleCloseFilled,
            InfoFilled,
            loading,
            LoadingGif,
            centerDialogVisible,
            companyDialogStates,
            decline,
            declineMessage,
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

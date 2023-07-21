<template>
    <div class="main-wrapper">
        <h1>Roles</h1>
        <div class="text-right mb-25">
            <button type="button" class="button button-dark" @click="showModal">
                Create new role
            </button>
        </div>
        <div class="col-md-12">
            <DataTable :datatable="datatable" :data="data"></DataTable>
            <Pagination :links="data.roles.links" :data="data.roles" />
        </div>

        <SideModal
            :content="content"
            :showing="showing"
            @hideModal="showing = false"
        >
            <CreateRole
                :formData="formData"
                :data="data"
                :editData="false"
                @hideModal="showing = false"
            ></CreateRole>
        </SideModal>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SideModal from "@/Layouts/SideModal.vue";
import { AlertError, Button, HasError } from "vform/src/components/bootstrap5";
import CreateRole from "./CreateRoleOld.vue";
import DataTable from "../../Shared/DataTable.vue";
import Pagination from "../../../Components/Pagination.vue";

export default {
    props: ["data"],
    components: {
        Button,
        HasError,
        AlertError,
        Link,
        CreateRole,
        SideModal,
        DataTable,
        Pagination,
    },

    layout: AdminLayout,

    data: () => {
        return {
            itemsPerPage: 10,
            companies: false,
            search: "",
            datatable: {
                modalComponent: "edit-role",
                headers: [
                    {
                        title: "Role name",
                        align: "start",
                        sortable: true,
                        key: "role_name",
                    },
                    {
                        title: "Actions",
                        key: "actions",
                        sortable: false,
                    },
                ],
                items: false,
                itemsPerPage: 10,
                editLink: "/role-action",
                action: "_role_edit",
                modal: true,
                view: true,
                viewLink: false,
                formData: {
                    action: "edit",
                    edit: true,
                },
                content: {
                    create: {
                        title: "Edit role",
                    },
                },
            },

            dialog: false,
            dialogDelete: false,
            desserts: [],
            editedIndex: -1,
            modal: true,
            formData: {
                action: "create",
                edit: false,
            },
            showing: false,
            content: {
                create: {
                    title: "Add new role",
                },
            },
        };
    },

    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },
    },

    created() {},
    methods: {
        showModal() {
            this.showing = true;
        },
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? "New Item" : "Edit Item";
        },
    },

    mounted() {
        // console.log(this.data.roles.links);
        if (this.data.roles) {
            this.datatable.items = this.data.roles.data;
        }
    },
};
</script>

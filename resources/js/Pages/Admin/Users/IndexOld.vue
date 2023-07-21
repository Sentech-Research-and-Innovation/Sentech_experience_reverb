<template>
    <div class="main-wrapper mb-2">
        <h1>Users</h1>
        <div class="text-right mb-25">
            <button type="button" class="button button-dark" @click="showModal">
                Create new user
            </button>
        </div>
        <!-- <div class="col-md-12">
            <DataTable :datatable="datatable" :data="data"></DataTable>
            <Pagination :links="data.users.links" :data="data.users" />
        </div> -->

        <SideModal
            :content="content"
            :showing="showing"
            @hideModal="showing = false"
        >
            <CreateUser
                :formData="formData"
                :data="data"
                :editData="false"
            ></CreateUser>
        </SideModal>
        <!--        {{editData}}-->
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SideModal from "@/Layouts/SideModal.vue";
import { AlertError, Button, HasError } from "vform/src/components/bootstrap5";
import CreateUser from "./CreateUser.vue";
import DataTable from "../../Shared/DataTable.vue";
import Pagination from "../../../Components/Pagination.vue";

export default {
    props: ["data"],
    components: {
        Button,
        HasError,
        AlertError,
        Link,
        CreateUser,
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
                modalComponent: "edit-user",
                headers: [
                    {
                        title: "First name",
                        align: "start",
                        sortable: true,
                        key: "first_name",
                    },
                    {
                        title: "Surname",
                        align: "start",
                        sortable: true,
                        key: "last_name",
                    },
                    {
                        title: "Email",
                        align: "start",
                        sortable: true,
                        key: "email",
                    },
                    {
                        title: "Role",
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
                editLink: "/user-action",
                action: "_user_edit",
                modal: true,
                view: false,
                viewLink: false,
                formData: {
                    action: "edit",
                    edit: true,
                },
                content: {
                    create: {
                        title: "Edit user",
                    },
                },
            },
            dialog: false,
            dialogDelete: false,
            desserts: [],
            editedIndex: -1,
            formData: {
                action: "create",
                edit: false,
            },
            showing: false,
            content: {
                create: {
                    title: "Create new user",
                },
            },
            modal: true,
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
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem);
            } else {
                this.desserts.push(this.editedItem);
            }
            this.close();
        },

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
        this.datatable.items = this.data.users.data;
        if (this.data.roles) {
            this.roles = this.data.roles;
        }
        if (this.data.menu_items) {
            this.menu_items = this.data.menu_items;
        }
    },
};
</script>
<style>
.text-paginate {
    color: #9e9090;
}

.active-page {
    background-color: #fa501e;
    color: #fff;
    text-align: center;
    font-weight: 600;
}
</style>

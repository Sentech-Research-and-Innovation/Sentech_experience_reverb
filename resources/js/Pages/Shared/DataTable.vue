<template>

<!--    {{editData}}-->
    <v-data-table
        v-if="this.datatable.items"
        v-model:items-per-page="this.datatable.itemsPerPage"
        :headers="this.datatable.headers"
        :items="this.datatable.items"
        item-value="name"
        class="elevation-1"
    >
        <template v-slot:item.actions="{ item }">
            <a v-if="!this.datatable.modal" @click="edit(item.raw)"><i class="fa fa-eye "></i>View</a>
            <a v-else @click="openModal(item.raw)"><i class="fa fa-eye "></i>Edit </a>
            <a v-if="this.datatable.view" @click="edit(item.raw)"><i class="fa fa-eye "></i>View</a>
<!--            <a v-if="this.datatable.action==='_category_edit'   " @click="openModal(item.raw)"><i class="fa fa-eye "></i>Sub Categories</a>-->
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">
                Reset
            </v-btn>
        </template>
        <!-- {{ data.users.links }} -->
    </v-data-table>

    <div v-if="this.editData!==false">
        <SideModal v-if="datatable.modal" :content="datatable.content" :showing="showing" @hideModal="showing=false">
            <CreateUser v-if="datatable.modalComponent==='edit-user'" :formData="datatable.formData"
                        :editData="editData" :data="data" @hideModal="showing=false"></CreateUser>
            <CreateRole v-if="datatable.modalComponent==='edit-role'" :formData="datatable.formData"
                        :editData="editData" :data="data" @hideModal="showing=false"></CreateRole>
            <CreateBranch v-if="datatable.modalComponent==='edit-branch'" :formData="datatable.formData" :data="data"
                          :editData="editData" @hideModal="showing=false"></CreateBranch>

            <CreateCategory v-if="datatable.modalComponent==='edit-category'" :formData="datatable.formData" :data="data"
                          :editData="editData" @hideModal="showing=false"></CreateCategory>

            <CreateRule v-if="datatable.modalComponent==='edit-rule'" :formData="datatable.formData" :data="data"
                          :editData="editData" @hideModal="showing=false"></CreateRule>

        </SideModal>
    </div>


</template>


<script>


    //Modals
    import SideModal from '@/Layouts/SideModal.vue';
    import CreateUser from '@/Pages/Admin/Users/CreateUser.vue';
    import CreateRole from '@/Pages/Admin/Roles/CreateRole.vue';
    import CreateBranch from '@/Pages/Admin/Branches/Branch/CreateBranch.vue';
    import CreateCategory from '@/Pages/Admin/Categories/CreateCategory.vue';
    import CreateRule from '@/Pages/Admin/Rules/CreateRule.vue';
    import {Link} from '@inertiajs/vue3'
    import {AlertError, Button, HasError,} from 'vform/src/components/bootstrap5'

    export default {
        props: ['datatable', 'data'],
        components: {
            Button, HasError, AlertError, Link, SideModal, CreateUser, CreateRole, CreateBranch,CreateCategory,CreateRule
        },
        data: () => {
            return {
                showing: false,
                emitForm: false,
                payload: {},
                editData: false
            }
        },
        created() {
        },

        methods: {
            openModal(item) {
                this.payload.action = this.datatable.action;
                this.payload.id = item.id;
                axios.post(this.datatable.editLink, this.payload).then((res) => {
                    if (res.data.success) {
                        this.editData = res.data.data;
                        this.showing = true;
                    } else {

                    }
                }).catch((err) => {
                    console.log(err);
                });

            },
            edit(item) {
                // alert(this.datatable.modal)
                if (this.datatable.modal === false) {
                    this.$inertia.visit(this.datatable.editLink, {
                        method: 'get',
                        data: {'i_id': item.id, 'action': this.datatable.action}
                    });
                }else{
                    if(this.datatable.view){
                        this.$inertia.visit(this.datatable.viewLink, {
                            method: 'get',
                            data: {'i_id': item.id, 'action': this.datatable.action}
                        });
                    }
                }

            },
        },
        computed: {
            filterOwners() {
                return this.owners.filter((owner) => {
                    return owner.name.toLowerCase().match(this.search.toLowerCase()) || owner.surname.toLowerCase().match(this.search.toLowerCase());
                })
            },

        },

        mounted() {
        }

    }
</script>


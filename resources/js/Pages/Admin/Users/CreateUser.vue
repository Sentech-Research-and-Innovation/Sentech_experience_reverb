<template>

    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="first_name">First name</label>
                <input id="first_name" type="text" class="" placeholder="First name" v-model="form.first_name">
                <div v-if="response.errorBag.first_name" v-text="response.errorBag.first_name"
                     class="text-danger"></div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="last_name">Last name</label>
                <input id="last_name" type="text" class="" placeholder="Last name"
                       v-model="form.last_name">
                <div v-if="response.errorBag.last_name" v-text="response.errorBag.last_name"
                     class="text-danger"></div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="text" class="" placeholder="Email"
                       v-model="form.email">
                <div v-if="response.errorBag.email"
                     v-text="response.errorBag.email"
                     class="text-danger"></div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="password">User role</label>
                <select v-if="roles" v-model="form.role_id">
                    <option v-for="(role, index) in roles" :value="role.id">{{role.role_name}} </option>
                </select>


            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Permissions</h3>
        </div>

    </div>

    <div v-for="(item, index) in menu_items" class="row m-2">


        <div class="col-md-4">
            <strong>{{item.title}}</strong>
        </div>
        <div class="col-md-2">
            <input v-if="editData" class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'create')"    :checked="form.selected_permissions[item.id].create">
            <input v-else class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'create')">
<!--            <input  v-else class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'create')">-->
            <label class="form-check-label m-10" >
                Create
            </label>
        </div>
        <div class="col-md-2">
            <input   v-if="editData" class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'read')" :checked="form.selected_permissions[item.id].read">
            <input   v-else class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'read')" >
            <label class="form-check-label m-10" >
                Read
            </label>
        </div>
        <div class="col-md-2">
            <input  v-if="editData" class="form-check-input" type="checkbox" @click="permissionsObject(item,index,'update')" :checked="form.selected_permissions[item.id].update">
            <input  v-else class="form-check-input" type="checkbox" id="update" @click="permissionsObject(item,index,'update')" >
            <label class="form-check-label m-10" >
                Update
            </label>
        </div>
        <div class="col-md-2">
            <input  v-if="editData" class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'delete')" :checked="form.selected_permissions[item.id].delete" >
            <input  v-else class="form-check-input" type="checkbox"  @click="permissionsObject(item,index,'delete')" >
            <label class="form-check-label m-10" >
                Delete
            </label>
        </div>


    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="text-right add-company-btn">
                <button type="submit" class="btn btn-dark button button-dark" @click="createUser()">{{btnText}}
                </button>
            </div>
        </div>
    </div>

</template>
<style>
    .add-company-btn {
        margin-top: 20px;
    }
</style>
<script>

    export default {
        props: ['formData', 'editData','data'],
        setup() {
        },
        data: function () {
            return {
                btnText: 'Add User',
                errorMessage: false,
                payload: false,
                roles: false,
                menu_items: false,
                permissions: [],
                response: {
                    errorBag: {
                        first_name: false,
                        last_name: false,
                        email: false,
                        create: false,

                    }
                },
                closeDropdown: false,
                showDropDown: false,
                owners: [],
                assignedOwners: [],
                ownersList: {},
                search: '',
                form: {
                    role_id: 0,
                    id: 0,
                    first_name: '',
                    last_name: '',
                    email: '',
                    selected_permissions: false,
                    permissions: false,

                },
            }
        },
        components: {},
        computed: {},
        headers: {},
        watch: {
            editData: {
                handler(value) {

                    if (value.roles) {
                        this.roles = value.data.roles;
                    }

                    if (value.menu_items) {
                        this.menu_items = value.menu_items;
                    }
                    if (this.formData.action === 'edit') {
                        if (this.formData.edit === true) {
                            this.form = value.form;
                            this.btnText = 'Update User';
                        }
                    }

                },
                deep: true
            }
        },
        mounted() {
                if (this.data.roles) {
                    this.roles = this.data.roles;
                }

                if (this.data.menu_items) {
                    this.menu_items = this.data.menu_items;
                }
            if (this.formData.action === 'edit') {
                if (this.formData.edit === true) {
                    this.form = this.editData.form;
                    this.btnText = 'Update User';
                }
            }


        },
        methods: {

            permissionsObject(item, index, value) {
                if (this.permissions[item.id]) {
                    this.permissions[item.id].push(value);
                } else {
                    this.permissions[item.id] = [value];
                }

            },
            createUser() {


                this.errorMessage = false;
                this.payload = this.form;
                this.payload.permissions = this.permissions;
                this.payload.name = this.form.first_name + ' ' + this.form.last_name;
                this.payload.action = this.formData.action;

                axios.post('/user-action', this.payload).then((res) => {
                    if (res.data.success) {
                        this.form = res.data.data;
                        this.$emit('hideModal',true);
                    } else {
                        this.errorMessage = res.data.message;
                    }
                }).catch((err) => {
                    console.log(err);
                });


            },

        },
    }
</script>



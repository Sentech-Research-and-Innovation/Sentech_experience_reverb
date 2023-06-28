<template>


    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="first_name">First name</label>
                <input id="first_name" type="text" class="" placeholder="Company name" v-model="form.first_name">
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
                <label class="form-label" for="password">Password</label>
                <input id="password" type="text" class="" placeholder="Password"
                       v-model="form.password">
                <div v-if="response.errorBag.password" v-text="response.errorBag.password"
                     class="text-danger"></div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="password">User role</label>
                <select v-if="roles"  v-model="form.role_id">
                    <option v-for="(role, index) in roles" :value="role.id">{{role.role_name}}</option>
                </select>


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
        props: ['formData', 'data'],
        setup() {
        },
        data: function () {
            return {
                btnText: 'Add User',
                errorMessage: false,
                payload: false,
                roles: false,
                form: {
                    id: 0,
                    first_name: '',
                    last_name: '',
                    email: '',

                },
                response: {
                    errorBag: {
                        first_name: false,
                        last_name: false,
                        email: false,

                    }
                },
                closeDropdown: false,
                showDropDown: false,
                owners: [],
                assignedOwners: [],
                ownersList: {},
                search: ''
            }
        },
        components: {},
        computed: {},
        watch: {},
        headers: {},
        mounted() {
            if (this.data.roles) {
                this.roles = this.data.roles;
            }
            if (this.formData.action === 'edit') {
                if (this.formData.edit) {
                    this.form = this.data.form;
                    this.btnText = 'Update User';
                }
            }
        },
        methods: {


            createUser() {
                this.errorMessage = false;
                this.payload = this.form;
                this.payload.name = this.form.owner.first_name + ' ' + this.form.owner.last_name;
                this.payload.action = this.formData.action;

                axios.post('/user-action', this.payload).then((res) => {
                    if (res.data.success) {
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



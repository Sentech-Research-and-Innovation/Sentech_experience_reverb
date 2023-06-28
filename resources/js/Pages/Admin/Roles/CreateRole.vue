<template>


    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="first_name">Role name</label>
                <input id="first_name" type="text" class="" placeholder="First name" v-model="form.role_name">
                <div v-if="response.errorBag.role_name" v-text="response.errorBag.role_name"
                     class="text-danger"></div>
            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="text-right add-company-btn">
                <button type="submit" class="btn btn-dark button button-dark" @click="createRole()">{{btnText}}
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
                btnText: 'Add Role',
                errorMessage: false,
                payload: false,
                permissions: [],
                response: {
                    errorBag: {
                        role_name: false,

                    }
                },
                closeDropdown: false,
                showDropDown: false,
                form: {
                    role_name: '',
                    id: 0,
                },
            }
        },
        components: {},
        computed: {},
        headers: {},

        watch: {
            editData: {
                handler(value) {
                    if (this.formData.action === 'edit') {
                        if (this.formData.edit === true) {
                            this.btnText = 'Edit Role';
                            if(this.editData){
                                this.form = value.form;
                            }
                        }
                    }
                },
                deep: true
            }
        },

        mounted() {
            if (this.formData.action === 'edit') {
                if (this.formData.edit === true) {
                    this.btnText = 'Edit Role';
                    if(this.editData){
                        this.form = this.editData.form;
                    }
                }
            }
        },
        methods: {

            createRole() {

                this.errorMessage = false;
                this.payload = this.form;
                this.payload.action = this.formData.action;
                axios.post('/role-action', this.payload).then((res) => {
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



<template>
    <div>
        <div class="col-12 text-end px-0">
            <!-- <button
                type="button"
                class="button button-dark"
              
            >
                Create Company
            </button> -->
            <el-button
                type="button"
                class="button-dark"
                @click="showing = true"
            >
                Create Company
            </el-button>
        </div>
        <SideModal
            :content="content"
            :showing="showing"
            @hideModal="showing = false"
        >
            <div class="col-12 pb-5 pt-4 px-4">
                <div class="row register-form">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="companyName" class="form-label"
                                >Company Name</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="form.company_name"
                            />
                            <div class="text-danger">
                                {{ errors.company_name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastName" class="form-label">
                                Contact Person Last Name</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="form.last_name"
                            />
                            <div class="text-danger">
                                {{ errors.last_name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="position" class="form-label"
                                >Contact Person Position</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="form.position"
                            />
                            <div class="text-danger">
                                {{ errors.position }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstName" class="form-label"
                                >Contact Person First Name</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="form.first_name"
                            />
                            <div class="text-danger">
                                {{ errors.first_name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label"
                                >Contact Person Email</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="form.email"
                            />
                            <div class="text-danger">
                                {{ errors.email }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="form-label"
                                >Contact Person Phone Number</label
                            >
                            <input
                                type="text"
                                class="form-control login-form-inputs"
                                v-model="form.phoneNumber"
                            />
                            <div class="text-danger">
                                {{ errors.phoneNumber }}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pt-3">
                        <div
                            class="btn btn-primary sentech-login-button d-flex justify-content-center align-items-center"
                            @click="createCompany()"
                        >
                            Submit
                        </div>
                        <!-- <button
                            @click="createCompany()"
                            class="btn button button-dark btn-block"
                        >
                            Create Company
                        </button>
                    </div> -->
                    </div>
                </div>
            </div>
        </SideModal>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import SideModal from "@/Layouts/SideModal.vue";

export default defineComponent({
    name: "create-new-user",

    components: {
        SideModal,
    },

    setup() {
        const content = ref({
            create: {
                title: "Create new company",
            },
        });

        const errors = ref({});

        const form = ref({});

        const showing = ref(false);

        const closeModal = () => {
            showing.value = false;
        };

        const createCompany = async () => {
            errors.value = {};
            try {
                await axios.post(`/organizantions/create`, form.value);
                errors.value = {};
                showing.value = false;
                location.reload();
            } catch (err) {
                const res = err.response.data.errors;

                errors.value = {
                    first_name: res?.first_name?.[0] || "",
                    last_name: res?.last_name?.[0] || "",
                    email: res?.email?.[0] || "",
                    position: res?.position?.[0] || "",
                    phoneNumber: res?.phoneNumber?.[0] || "",
                    company_name: res?.company_name?.[0] || "",
                };
            }
        };

        return {
            content,
            showing,
            closeModal,
            createCompany,
            errors,
            form,
        };
    },
});
</script>

<style scoped>
.error {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
</style>

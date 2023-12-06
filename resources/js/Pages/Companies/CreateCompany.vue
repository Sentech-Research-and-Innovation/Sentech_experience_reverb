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
            <div class="col-12 pb-5 pt-4">
                <div class="col-12 pb-5">
                    <label class="pb-2">Company Name</label>
                    <input
                        type="text"
                        v-model="form.company_name"
                        class
                        placeholder="Company Name"
                    />
                    <div class="error">
                        {{ errors.company_name }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <label class="pb-2">Contact Person First Name</label>
                        <input
                            type="text"
                            v-model="form.first_name"
                            class
                            placeholder="Contact Person First Name"
                        />
                        <div class="error">
                            {{ errors.first_name }}
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="pb-2">Contact Person Last Name</label>
                        <input
                            type="text"
                            v-model="form.last_name"
                            class
                            placeholder="Contact Person Last Name"
                        />
                        <div class="error">
                            {{ errors.last_name }}
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="pb-2">Contact Person Email</label>
                        <input
                            type="text"
                            v-model="form.email"
                            class
                            placeholder="Contact Person Email"
                        />
                        <div class="error">
                            {{ errors.email }}
                        </div>
                    </div>

                    <div class="col-12 pt-5">
                        <button
                            @click="createCompany()"
                            class="btn btn-dark button button-dark btn-block"
                        >
                            Create Company
                        </button>
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
                const data = form.value;
                await axios.post(`/organizantions/create`, {
                    company_name: data.company_name,
                    first_name: data.first_name,
                    last_name: data.last_name,
                    email: data.email,
                });
                errors.value = {};
                showing.value = false;
                location.reload();
            } catch (err) {
                const res = err.response.data.errors;

                errors.value = {
                    first_name: res?.first_name?.[0] || "",
                    last_name: res?.last_name?.[0] || "",
                    email: res?.email?.[0] || "",
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

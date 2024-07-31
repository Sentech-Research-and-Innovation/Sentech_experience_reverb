<template>
    <div>
        <div class="col-12 text-end">
            <el-button type="button" class="button-dark" @click="getRoles">
                Create User
            </el-button>
        </div>
        <SideModal
            :content="content"
            :showing="showing"
            @hideModal="showing = false"
        >
            <div class="col-12 pb-5 pt-4">
                <div class="row">
                    <div class="col-4">
                        <label class="pb-2 form-label">First Name</label>
                        <input
                            type="text"
                            v-model="form.first_name"
                            class="form-control login-form-inputs"
                            placeholder="First Name"
                        />
                        <div class="error">
                            {{ errors.first_name }}
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="pb-2 form-label">Last Name</label>
                        <input
                            type="text"
                            v-model="form.last_name"
                            class="form-control login-form-inputs"
                            placeholder="Last Name"
                        />
                        <div class="error">
                            {{ errors.last_name }}
                        </div>
                    </div>
                    <div class="col-4">
                        <label class="pb-2 form-label">Email</label>
                        <input
                            type="text"
                            v-model="form.email"
                            class="form-control login-form-inputs"
                            placeholder="Email"
                        />
                        <div class="error">
                            {{ errors.email }}
                        </div>
                    </div>
                    <div class="col-4 pt-4">
                        <label class="form-label" for="password"
                            >User role</label
                        >
                        <select
                            v-if="roles"
                            v-model="form.role"
                            class="form-control"
                        >
                            <option
                                v-for="role in roles"
                                :value="role.id"
                                :key="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </select>
                        <div class="error">
                            {{ errors.role }}
                        </div>
                    </div>
                    <div class="col-12 pt-5">
                        <button
                            @click="createUser()"
                            class="btn btn-dark button button-dark btn-block"
                        >
                            Create User
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
                title: "Create new user",
            },
        });

        const errors = ref({});

        const form = ref({
            first_name: "",
            last_name: "",
            email: "",
            role: "",
        });

        const showing = ref(false);

        const roles = ref([]);

        const closeModal = () => {
            showing.value = false;
        };

        const createUser = async () => {
            errors.value = {};
            try {
                const data = form.value;
                await axios.post(`/admin/user/create`, {
                    first_name: data.first_name,
                    last_name: data.last_name,
                    email: data.email,
                    role: data.role,
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
                    role: res?.role?.[0] || "",
                };
            }
        };

        const getRoles = async () => {
            showing.value = true;
            const response = await axios.get("/admin/roles/getRoles");
            roles.value = response.data;
        };

        return {
            getRoles,
            content,
            showing,
            closeModal,
            roles,
            createUser,
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

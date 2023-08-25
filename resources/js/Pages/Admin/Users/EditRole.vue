<template>
    <div>
        <div class="col-12=">
            <button type="button" class="btn btn-sm btn-dark" @click="getRoles">
                Change role
            </button>

            <SideModal
                :content="content"
                :showing="showing"
                @hideModal="showing = false"
            >
                <div class="col-12 py-4 px-4 border">
                    <div class="row">
                        <div
                            class="col-3 py-2"
                            v-for="role in roles"
                            :key="role.id"
                        >
                            <!-- <label class="form-check-label" :for="role.name">
                                <input
                                    class="form-check-input mx-2"
                                    type="radio"
                                    v-model="selectedRole"
                                    :value="role.name"
                                    :checked="isSelected(role.name)"
                                />

                                {{ role.name }}
                                <i class="input-helper"></i>
                            </label> -->

                            <input
                                class="form-check-input"
                                type="radio"
                                v-model="selectedRole"
                                :value="role.name"
                                :checked="isSelected(role.name)"
                            />
                            <label
                                class="form-check-label pt-1"
                                :for="role.name"
                            >
                                {{ role.name }}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 pt-3">
                    <div class="text-right add-company-btn">
                        <button
                            @click="EditRole()"
                            class="btn btn-dark button button-dark"
                        >
                            Save Role
                        </button>
                    </div>
                </div>
            </SideModal>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";
import SideModal from "@/Layouts/SideModal.vue";

export default defineComponent({
    name: "edit-role",
    props: {
        userId: {
            type: Number,
            required: true,
        },
    },

    components: {
        SideModal,
    },

    setup(props) {
        const { userId } = props;

        const roles = ref([]);
        const dialog = ref(false);
        const selectedRole = ref("");
        const roleByUser = ref([]);

        const content = ref({
            create: {
                title: "Change role for user",
            },
        });

        const getRoles = async () => {
            showing.value = true;

            const response = await axios.get("/admin/roles/getRoles");
            roles.value = response.data;
            getRoleByUser();
        };

        const getRoleByUser = async () => {
            const res = await axios.get(`/admin/user/role/${userId}`);
            if (res.status === 200) {
                roleByUser.value = res.data;

                selectedRole.value = roleByUser.value
                    .map((role) => role.name)
                    .reduce((prev, curr) => prev + ", " + curr);

                // console.log(roleByUser.value);
            }
        };

        const isSelected = (roleName) => {
            return roleByUser.value.some((role) => role.name === roleName);
        };

        const EditRole = async () => {
            ///admin/user/role/update/
            // console.log(selectedRole.value);

            const res = await axios.post(`/admin/user/role/update/${userId}`, {
                roleName: selectedRole.value,
            });
            if (res.status === 200) {
                dialog.value = false;
                location.reload();
            }
        };

        const showing = ref(false);

        const closeModal = () => {
            showing.value = false;
        };

        return {
            getRoles,
            roles,
            dialog,
            selectedRole,
            EditRole,
            isSelected,
            content,
            closeModal,
            showing,
        };
    },
});
</script>

<style></style>

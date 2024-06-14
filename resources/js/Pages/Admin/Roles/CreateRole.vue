<template>
    <div class="roles-form">
        <div class="col-12 text-end px-0 mx-0">
            <el-button @click="showing = true" class="button-dark">
                Create Role
            </el-button>
        </div>
        <SideModal
            :content="content"
            :showing="showing"
            @hideModal="showing = false"
        >
            <div>
                <div class="col-12 pb-5 pt-0">
                    <label for="RoleName" class="form-label">Role Name</label>
                    <input
                        type="text"
                        v-model="roleName"
                        class="form-control login-form-inputs"
                        placeholder="Role Name"
                    />
                    <div v-if="errorRoleName" class="py-2 text-danger">
                        {{ errorRoleName }}
                    </div>
                    <div class="col-12 py-4 mt-2 mx-0 px-0">
                        <h4>Select Permissions for this role</h4>
                        <div
                            class="col-12"
                            v-for="(group, groupName) in groupedData"
                            :key="groupName"
                        >
                            <div class="row mb-2 border">
                                <div class="col-3 border-right py-3">
                                    <strong>{{ groupName }}</strong>
                                </div>
                                <div class="col-9 py-3">
                                    <div class="row">
                                        <div
                                            class="col-3 mb-2"
                                            v-for="perm in group"
                                            :key="perm.id"
                                        >
                                            <input
                                                class="form-check-input mx-0"
                                                type="checkbox"
                                                v-model="SelectedPermissions"
                                                :value="perm.name"
                                            />

                                            <label
                                                class="form-check-label pt-1 pl-4"
                                                :for="perm.name"
                                            >
                                                {{ perm.label }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 pt-3">
                            <div class="text-right add-company-btn">
                                <el-button
                                    @click="SaveRole()"
                                    class="button-dark"
                                >
                                    Save Role
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SideModal>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import { useGroupedData } from "../../../utils/useGroupedData";
import SideModal from "@/Layouts/SideModal.vue";

export default defineComponent({
    name: "create-roles",
    components: {
        SideModal,
    },
    setup() {
        const content = ref({
            create: {
                title: "Create new role",
            },
        });

        const roleName = ref("");
        const errorRoleName = ref("");
        const permissions = ref(null);
        const SelectedPermissions = ref([]);

        const SaveRole = async () => {
            try {
                const res = await axios.post("/admin/roles/create", {
                    name: roleName.value,
                    permissions: SelectedPermissions.value,
                });

                if (res.status === 200) {
                    roleName.value = "";
                    errorRoleName.value = "";

                    location.reload();
                }
            } catch (err) {
                errorRoleName.value = err.response.data.message;
            }
        };

        const queryPermissions = async () => {
            const response = await axios.get("/admin/permissions");
            permissions.value = response.data;
        };
        const { groupedData } = useGroupedData(permissions);

        onMounted(async () => {
            queryPermissions();
        });

        const showing = ref(false);

        const closeModal = () => {
            showing.value = false;
        };

        return {
            roleName,
            SaveRole,
            errorRoleName,
            permissions,
            SelectedPermissions,
            showing,
            content,
            closeModal,
            groupedData,
        };
    },
});
</script>

<style lang="scss">
.form-control-error {
    border-radius: solid 1px #ff1744 !important;
}
.roles-form {
    color: #000 !important;
}
</style>

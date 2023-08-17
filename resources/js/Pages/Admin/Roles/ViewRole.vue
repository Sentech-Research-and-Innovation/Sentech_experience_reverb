<template>
    <button type="button" class="btn btn-dark"  @click="queryPermissions"> Edit Permissions </button>

    <div>
        <div class="text-end">
            <v-dialog
                v-model="dialog"
                activator="parent"
                persistent
                width="70%"
            >
                <v-card>
                    <v-card-text>
                        <div class="col-12 pt-4 pb-2 px-4">
                            <h5>Edit Permissions for {{ roleName }} Role</h5>
                        </div>
                        <div class="col-12 py-4 px-4 border">
                            <div
                                class="col-12 pt-2"
                                v-for="(group, groupName) in groupedData"
                                :key="groupName"
                            >
                                <div class="row">
                                    <div class="col-4">
                                        <h6>{{ groupName }}</h6>
                                    </div>
                                    <div class="col-8">
                                        <div class="row">
                                            <div
                                                class="col-3"
                                                v-for="perm in group"
                                                :key="perm.id"
                                            >
                                                <input
                                                    class="form-check-input mx-2"
                                                    type="checkbox"
                                                    :value="perm.name"
                                                    :checked="
                                                        isSelected(perm.name)
                                                    "
                                                    v-model="
                                                        selectedPermissions
                                                    "
                                                />
                                                <label
                                                    class="form-check-label"
                                                    :for="perm.name"
                                                >
                                                    {{ perm.label }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card-text>
                    <v-card-actions>
                        <div class="row px-4 py-4">
                            <div class="col-6">
                                <v-btn block class="btn-dark" @click="saveRole"
                                    >Save</v-btn
                                >
                            </div>
                            <div class="col-6">
                                <v-btn block @click="dialog = false"
                                    >Cancel</v-btn
                                >
                            </div>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";
import axios from "axios";

import { useGroupedData } from "../../../utils/useGroupedData";

export default defineComponent({
    name: "view-role",
    props: {
        roleName: {
            type: String,
            required: true,
        },
    },

    setup(props) {
        const { roleName } = props;

        const rolePermissions = ref([]);
        const permissions = ref([]);

        const dialog = ref(false);
        const selectedPermissions = ref([]);

        const queryPermissions = async () => {
            dialog.value = true;

            const response = await axios.get("/admin/permissions");
            permissions.value = response.data;
            getRolePermissions();
        };

        const getRolePermissions = async () => {
            try {
                const res = await axios.post(`/admin/roles/show`, {
                    name: roleName,
                });
                if (res.status === 200) {
                    rolePermissions.value = res.data;
                    selectedPermissions.value = rolePermissions.value.map(
                        (perm) => perm.name
                    );
                }
            } catch (err) {
                console.log(errr);
            }
        };

        const { groupedData } = useGroupedData(permissions);

        const isSelected = (permissionName) => {
            return rolePermissions.value.some(
                (rolePerm) => rolePerm.name === permissionName
            );
        };

        const saveRole = async () => {
            try {
                const res = await axios.post(`/admin/roles/update`, {
                    permissions: selectedPermissions.value,
                    roleName: roleName,
                });
                if (res.status === 200) {
                    dialog.value = false;
                    location.reload();
                }
            } catch (err) {
                console.log(errr);
            }
        };

        return {
            queryPermissions,
            selectedPermissions,
            dialog,
            permissions,
            groupedData,
            isSelected,
            saveRole,
        };
    },
});
</script>

<style scoped>
.btn-dark {
    color: #ffff;
    background-color: black;
}
.btn-dark:hover {
    background-color: black;
}
</style>

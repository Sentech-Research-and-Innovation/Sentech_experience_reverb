<template>
    <el-button class="button-dark" @click="queryPermissions">
        Edit Permissions
    </el-button>

    <el-drawer
        v-model="dialog"
        title="I am the title"
        size="80%"
        :with-header="false"
    >
        <div>
            <div>
                <div class="col-12 pt-4 pb-2 text-start">
                    <h5>Edit Permissions for {{ roleName }} Role</h5>
                </div>
                <div class="col-12 py-4 px-0border text-start">
                    <div
                        class="col-12 pt-lg-4 pt-2"
                        v-for="(group, groupName) in groupedData"
                        :key="groupName"
                    >
                        <div class="row border">
                            <div class="col-lg-9 col-12">
                                <div class="row">
                                    <div
                                        class="col-lg-3 col-8 text-lg-start text-end py-3"
                                        v-for="perm in group"
                                        :key="perm.id"
                                    >
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :value="perm.name"
                                            :checked="isSelected(perm.name)"
                                            v-model="selectedPermissions"
                                        />

                                        <label
                                            class="form-check-label pt-1 pr-4"
                                            :for="perm.name"
                                        >
                                            {{ perm.label }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-12 border-right py-3">
                                <h5>{{ groupName }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="d-flex justiify-content-end py-4">
                    <div class="px-2">
                        <el-button @click="dialog = false">Cancel</el-button>
                    </div>
                    <div class="">
                        <el-button type="primary" @click="saveRole"
                            >Save
                        </el-button>
                    </div>
                </div>
            </div>
        </div>
    </el-drawer>
    <div>
        <!-- <div class="text-end">
            <v-dialog
                v-model="dialog1"
                activator="parent"
                persistent
                width="70%"
            >
               
            </v-dialog>
        </div> -->
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

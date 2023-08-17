<template>
    <button type="button" class="btn btn-dark"  id="ffg" @click="dialog1 = true"> Delete </button>
    <div>
        <v-dialog v-model="dialog1" activator="parent" persistent width="30%">
            <v-card>
                <div class="col-12 text-center border py-4">
                    Are You Sure You Want to delete this? {{ roleId }}
                    <div class="text-danger">{{ errMessage }}</div>
                    <div class="row px-4 py-4">
                        <div class="col-6">
                            <v-btn
                                block
                                class="btn-dark"
                                @click="dialog1 = false"
                                >No</v-btn
                            >
                        </div>
                        <div class="col-6">
                            <v-btn block @click="deleteRole">Yes</v-btn>
                        </div>
                    </div>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";

export default defineComponent({
    name: "delete-roles",
    props: {
        roleId: {
            type: Number,
            required: true,
        },
    },

    setup(props) {
        const { roleId } = props;

        const dialog1 = ref(false);
        const errMessage = ref("");

        const deleteRole = async () => {
            try {
                const res = await axios.post("/admin/roles/delete", {
                    roleId,
                    roleId,
                });

                if (res.status === 200) {
                    location.reload();
                }
            } catch (err) {
                errMessage.value =
                    "Can't Delete this role because it's assigned to: ";
                const errorData = err.response.data;
                errorData.forEach(function (name) {
                    errMessage.value = errMessage.value.concat(", ", name);
                });
            }
        };

        return {
            deleteRole,
            dialog1,
            roleId,
            errMessage,
        };
    },
});
</script>

<style></style>

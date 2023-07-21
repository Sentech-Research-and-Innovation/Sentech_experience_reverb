<template>
    <v-btn color="#FFCDD2" @click="deleteRole()"> Delete </v-btn>
</template>

<script>
import { defineComponent, ref } from "vue";

export default defineComponent({
    name: "delete-roles",

    setup() {
        const deleteRole = async () => {
            try {
                const res = await axios.post("/admin/roles/Delete", {
                    name: roleName.value,
                });

                if (res.status === 200) {
                    console.log("deleted");
                }
            } catch (err) {
                console.log(errr);
            }
        };

        return {
            deleteRole,
        };
    },
});
</script>

<style></style>

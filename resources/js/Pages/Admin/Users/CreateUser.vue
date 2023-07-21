<template>
    <div>
        <div class="col-12 text-end">
            <button
                type="button"
                class="button button-dark"
                @click="showing = true"
            >
                Create Role
            </button>
        </div>
        <SideModal
            :content="content"
            :showing="showing"
            @hideModal="showing = false"
        >
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
                title: "Create new role",
            },
        });

        const showing = ref(false);

        const closeModal = () => {
            showing.value = false;
        };

        return {
            content,
            showing,
            closeModal,
        };
    },
});
</script>

<style></style>

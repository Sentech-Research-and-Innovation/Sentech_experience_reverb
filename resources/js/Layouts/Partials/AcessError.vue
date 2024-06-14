<template>
    <div class="error">
        <p>{{ message }}</p>
        <button @click="goBack">Back</button>
    </div>
</template>

<script setup>
import { defineProps } from "vue";
// import { useRouter } from "vue-router";

const props = defineProps({
    message: {
        type: String,
        required: true,
    },
});

// const router = useRouter();

const goBack = () => {
    history.back();
};
</script>

<style scoped>
/* Add your styles here */
</style>

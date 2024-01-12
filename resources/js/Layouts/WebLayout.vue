<template>
    <Nav></Nav>

    <div class="sentech-web">
        <div class="container">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import Nav from "@/Layouts/Partials/Nav.vue";
import { Link } from "@inertiajs/vue3";
import { defineComponent, onMounted, ref, unref } from "vue";

import { useDark, useToggle, useColorMode } from "@vueuse/core";

export default defineComponent({
    components: {
        Nav,
        Link,
    },

    setup() {
        const isDark = useDark({
            selector: "body",
            attribute: "class",
            valueLight: "light",
        });

        const colorMode = useColorMode();

        const toggleMode = () => {
            colorMode.value = "light";
        };

        onMounted(() => {
            toggleMode();
        });
        return {};
    },
});
</script>

<style lang="scss" scoped>
.sentech-web {
    background-color: #144f9f;
    color: #fff;
}
</style>

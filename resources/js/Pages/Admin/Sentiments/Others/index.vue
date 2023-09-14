<template>
    <Head :title="'Others'"><title>Others</title></Head>

    <div class="col-12 px-0">
        <div class="col-12 px-0"><navigationSearchBar /></div>
        <div class="col-12 px-4">
            <Map />
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, ref } from "vue";

import Map from "./vectorMap.vue";

import navigationSearchBar from "../../../../Layouts/sentiments/navigationSearchBar.vue";

import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "sentiment-analysis-timelines-index",
    layout: AdminLayout,

    components: {
        Link,
        Head,
        Map,
        navigationSearchBar,
    },
    setup() {
        return {};
    },
});
</script>

<style scoped></style>

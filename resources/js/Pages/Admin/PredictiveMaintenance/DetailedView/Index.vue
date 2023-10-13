<template>
    <navigationTabsVue />
    <div class="col-12">
        <deatiledViewFiltersVue />
    </div>
    <div class="mt-4 col-12">
        <TableViewVue />
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import deatiledViewFiltersVue from "../../../../Layouts/predictions/deatiledViewFilters.vue";

import navigationTabsVue from "../../../../Layouts/predictions/navigationTabs.vue";

import TableViewVue from "./TableView.vue";

export default defineComponent({
    layout: AdminLayout,
    components: {
        deatiledViewFiltersVue,
        navigationTabsVue,
        TableViewVue,
    },
    setup() {},
});
</script>

<style lang="scss"></style>

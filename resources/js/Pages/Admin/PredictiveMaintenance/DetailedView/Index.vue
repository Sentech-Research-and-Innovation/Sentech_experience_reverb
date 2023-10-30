<template>
    <navigationTabsVue />
    <div class="col-12">
        <deatiledViewFiltersVue :predictions="predictions" />
    </div>
    <div class="mt-4 col-12">
        <TableViewVue :predictions="predictions" />
    </div>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import deatiledViewFiltersVue from "../../../../Layouts/predictions/deatiledViewFilters.vue";

import navigationTabsVue from "../../../../Layouts/predictions/navigationTabs.vue";

import TableViewVue from "./TableView.vue";

export default defineComponent({
    props: {
        predictions: {
            type: Array,
            required: true,
        },
    },
    layout: AdminLayout,
    components: {
        deatiledViewFiltersVue,
        navigationTabsVue,
        TableViewVue,
    },
    setup(props) {
        const { predictions } = props;

        return {
            predictions,
        };
    },
});
</script>

<style lang="scss"></style>

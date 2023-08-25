<template>
    <div class="col-12">
        <h1>National Site Data</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Operation Centre</th>
                    <th>Region</th>
                    <th>Province</th>
                    <th>Type TX</th>
                    <th>Site Name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="site in sites" :key="site.id">
                    <td>{{ site.id }}</td>
                    <td>{{ site.operation_centre || "N/A" }}</td>
                    <td>{{ site.region }}</td>
                    <td>{{ site.provence }}</td>
                    <td>{{ site.type_TX }}</td>
                    <td>{{ site.site_name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default defineComponent({
    layout: AdminLayout,
    components: {},
    props: {
        sites: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const { sites } = props;
        return {
            sites,
        };
    },
});
</script>

<style>
/* Add your styling here */
</style>

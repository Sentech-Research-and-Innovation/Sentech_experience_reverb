<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light py-3 mb-2">
            <ul class="navbar-nav mr-auto pt-1">
                <li
                    class="nav-item mr-5"
                    :class="{
                        active:
                            $page.url === '/admin/predictive-maintenance/index',
                    }"
                >
                    <a
                        class="nav-link py-0"
                        href="/admin/predictive-maintenance/index"
                        >Master View</a
                    >
                </li>

                <li
                    class="nav-item mr-5"
                    :class="{
                        active:
                            $page.url ===
                            '/admin/predictive-maintenance/predictions/detailed-view',
                    }"
                >
                    <Link
                        class="nav-link"
                        href="/admin/predictive-maintenance/predictions/detailed-view"
                        >Detailed View</Link
                    >
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
    components: { Link },

    setup() {
        return {};
    },
});
</script>

<style scoped>
.nav-link {
    color: #a8abb2;
    font-size: 14px;
    padding: 0px !important;
    margin: 0px !important;
}

.active {
    border-bottom: 3px solid #144f9f !important;
    color: #144f9f !important;
}
</style>

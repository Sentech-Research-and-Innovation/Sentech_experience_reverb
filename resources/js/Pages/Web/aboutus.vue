<template>
    <Head :title="'aboutus'"><title>About us</title></Head>
    <WebLayout>
        <div class="sentech-index-page">
            <div class="container">
                <div class="sentech-index-page">
                    <div>About us</div>
                </div>
            </div>
        </div>
    </WebLayout>
</template>

<script>
import { Head, Link } from "@inertiajs/vue3";
import WebLayout from "@/Layouts/WebLayout.vue";

export default {
    components: {
        WebLayout,
        Head,
        Link,
    },
    data: function () {
        return {};
    },
};
</script>

<style scoped>
.sentech-index-page {
    padding-top: 100px;
    min-height: 100vh;
}
</style>

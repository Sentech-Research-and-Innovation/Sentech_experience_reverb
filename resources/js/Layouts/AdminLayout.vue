<template>
    <!--    <div class="container-fluid">-->
    <!--        <div class="row">-->

    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="admin-layout bg-white">-->
    <!--        <side-nav></side-nav>-->
    <!--        <AdminHeader />-->
    <!--        <main class="admin-content">-->
    <!--            <div class="container">-->
    <!--                <slot></slot>-->
    <!--            </div>-->
    <!--        </main>-->
    <!--    </div>-->

    <div class="sentech-admin">
        <div class="container-fluid">
            <div class="row">
                <SideNav></SideNav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div v-if="is('Admin')">
                        Only users with this role can view this DIV.
                    </div>
                    <AdminHeader />
                    <slot></slot>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import SideNav from "@/Layouts/Partials/SideNav.vue";
import AdminHeader from "@/Layouts/Partials/AdminHeader.vue";

import { Link } from "@inertiajs/vue3";
export default {
    components: {
        SideNav,
        Link,
        AdminHeader,
    },
    mounted() {
        console.log(this.$page.props.auth.user.name);
        console.log("this is the header");
    },
};
</script>

<style scoped lang="scss">
.sentech-admin {
    background-color: #ffffff;
    height: 100vh;
}
</style>

<template>
    <div class="admin-layout bg-white">
        <side-nav></side-nav>
        <AdminHeader />
        <main class="admin-content">
            <div class="container">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script>

    import SideNav from '@/Layouts/Partials/SideNav.vue';
    import AdminHeader from '@/Layouts/Partials/AdminHeader.vue';

    import { Link } from '@inertiajs/vue3'
    export default {
        components:{
            SideNav, Link,
            AdminHeader
        },
        mounted(){
            console.log(this.$page.props.auth.user.username);
            console.log('this is the header');
        }
    }
</script>

<style>

</style>

<template>
    <div class="admin-layout bg-white">
        <slot></slot>
       
    </div>
</template>

<script>

    import SideNav from '@/Layouts/Partials/LoanWizzardSidebar.vue';
    import AdminHeader from '@/Layouts/Partials/AdminHeader.vue';

    import { Link } from '@inertiajs/vue3'
    export default {
        components:{
            SideNav, Link,
            AdminHeader
        },
        data: function () {
            return {
                buttonText: 'Continue',
                currentStep: 0
            }
        },
        mounted(){
        }
    }
</script>

<style lang="scss" scoped>
    .lw-form-wrapper{
        position: relative;
        min-height: calc(100vh - 115px);
        // NEST
        .main-floating-button{
            position: absolute;
            right: 0;
            bottom: 0;
        }
        .btn{
            padding: 10px 25px;
            &.btn-primary{
                border-radius: 0;
                background-color: #000;
                border-color: #000;
            }
        }
    }
</style>

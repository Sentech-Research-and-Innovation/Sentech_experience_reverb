<template>
    <Nav :showing="showing" @modal="updateModal"></Nav>
    <SideModal :content="content" :showing="showing"  @modal="updateModal">
<!--        <Login :formData="formData" :data="data" :editData="false" @modal="updateModal"></Login>-->
    </SideModal>
    <div class="sentech-web">
    <div class="container ">
            <slot></slot>
        </div>

    </div>
</template>

<script>

    import Nav from '@/Layouts/Partials/Nav.vue';
    import {Link} from '@inertiajs/vue3'
    import SideModal from "../Layouts/SideModal.vue";
    import Login from "../Pages/Auth/Login.vue";
    export default {
        components: {
            Nav, Link,SideModal,Login
        },
        data: function () {
            return {
                showing:false,
                formData: {
                    action: 'login',
                    edit: true
                },
                content: {
                    create: {
                        title: 'Sentech Login'
                    }
                },
            }
        },
        methods: {
            updateModal(value){
                this.showing = value;
                console.log(this.showing,'hello Show');
            },

        },
        mounted() {
        }
    }
</script>

<style lang="scss" scoped>

    .sentech-web{
        background-color: #144f9f;
       color: #fff;
    }
</style>

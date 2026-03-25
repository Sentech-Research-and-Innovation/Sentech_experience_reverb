<template>
    <div class="col-12 mx-0 px-0" style="position: fixed; z-index: 1">
        <nav class="navbar navbar-expand mx-0">
            <div class="container pl-1">
                <a class="navbar-brand" href="/">
                    <img src="../../assets/sentech-white-logo.png" class="logo" />
                </a>

                <div class="nav-lg" id="sentech-nav">
                    <div class="navbar-nav ms-auto ml-5 mr-auto pt-2">
                        <!-- <Link
                            class="register nav-link nav-link-text mr-2"
                            aria-current="page"
                            href="/"
                            ><span class="">Home</span>
                        </Link>
                        <Link
                            class="nav-link nav-link-text mr-2"
                            aria-current="page"
                            href="/services"
                        >
                            Services
                        </Link>
                        <Link
                            class="nav-link nav-link-text mr-2"
                            aria-current="page"
                            href="/aboutus"
                            ><span>About us</span>
                        </Link> -->
                        <Link class="nav-link nav-link-text mr-2" aria-current="page" href="/"><span>Home</span>
                        </Link>
                        <a class="nav-link nav-link-text mr-5" aria-current="page" target="_blank"
                            href="https://www.sentech.co.za/about-us"><span>About us</span>
                        </a>

                        <template v-if="isLoggedIn">
                            <Link class="nav-link nav-link-text mr-2" aria-current="page" href="/admin/dashboard">
                                <span><i class="fa-solid fa-table-columns"></i> Dashboard</span>
                            </Link>
                        </template>
                        <template v-else>
                            <Login />
                        </template>
                    </div>
                </div>
                <div class="mobile-nav1">
                    <i class="fa-solid fa-bars" @click="drawer = true" style="
                            cursor: pointer;
                            color: #ffff;

                            padding: 0px;
                            font-size: 20px;
                        "></i>
                    <el-drawer v-model="drawer" :direction="direction" size="60%" :with-header="false" style="
                            overflow: hidden !important;
                            background-color: #144f9f;
                        ">
                        <div class="col-12 px-0 mx-0">
                            <div class="row">
                                <a class="navbar-brand pt-3" href="/">
                                    <img src="../../assets/sentech-white-logo.png" class="logo" />
                                </a>

                                <div class="col-12 px-3 mx-0 pt-3">
                                    <Link class="register nav-link nav-link-text" aria-current="page" href="/"><span
                                        class="">Home</span>
                                    </Link>

                                    <a class="nav-link nav-link-text mr-5" aria-current="page" target="_blank"
                                        href="https://www.sentech.co.za/about-us/who-we-are"><span>About us</span>
                                    </a>
                                </div>
                                <!-- <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/services"
                                        ><span class="">Services</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/aboutus"
                                        ><span class="">About us</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/news"
                                        ><span class="">News</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/contactus"
                                        ><span class="">Contact us</span>
                                    </Link>
                                </div> -->

                                <div class="col-12 px-0 mx-0">
                                    <template v-if="isLoggedIn">
                                        <Link class="nav-link nav-link-text mr-2" aria-current="page" href="/admin/dashboard">
                                            <span>Dashboard</span>
                                        </Link>
                                    </template>
                                    <template v-else>
                                        <Login />
                                    </template>
                                </div>
                            </div>
                        </div>
                    </el-drawer>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import Login from "../../Pages/Auth/Login.vue";
import Register from "../../Pages/Auth/Register.vue";
import { Collection } from "@element-plus/icons-vue";
import { defineComponent, onMounted, ref, unref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export default defineComponent({
    props: ["showing"],
    components: {
        Link,
        Login,
        Register,
    },

    setup() {
        const page = usePage();
        const drawer = ref(false);
        const direction = ref("ltr");

        const user = computed(() => page.props.auth?.user);
        const isLoggedIn = computed(() => !!user.value);

        const showingModal = (value) => {
            showing.value = value;
            // this.$emit('modal', value);
        };

        return {
            drawer,
            direction,
            showingModal,
            Collection,
            user,
            isLoggedIn,
        };
    },
});
</script>
<style scoped lang="scss">
.navbar {
    background: #144f9f;
    height: 100px;
    border-bottom: solid 1px #fff;

    .nav-link {
        color: #fff;

        .fa.fa-user {
            padding-right: 10px;
        }
    }
}

.nav-link:hover svg path {
    fill: #4b84d3 !important;
}
</style>
<style>
.logo {
    width: 200px;
}

.mobile-nav1 {
    display: none;
}

@media (max-width: 1024px) {
    .nav-lg {
        visibility: hidden;
        clear: both;
        float: left;
        margin: 10px auto 5px 20px;
        width: 28%;
        display: none;
    }

    .mobile-nav1 {
        display: flex;
        color: #fff;
    }

    .navbar .social {
        visibility: hidden;
    }

    .logo {
        width: 120px;
    }

    .navbar {
        height: 50px !important;
    }
}

.social {
    padding: 0px !important;
}

.nav-link-text:hover {
    /* font-size: 17px; */
    background-color: #0c368b !important;
}

@media (min-width: 1200px) {
    .container {
        max-width: 1250px;
    }
}

.navbar a {
    font-weight: 400 !important;
}
</style>

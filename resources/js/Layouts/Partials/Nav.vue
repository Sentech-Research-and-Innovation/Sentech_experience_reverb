<template>
    <div class="col-12 mx-0 px-0" style="position: fixed; z-index: 1">
        <nav class="navbar navbar-expand">
            <div class="container">
                <a class="navbar-brand pt-3" href="/"
                    ><img src="../../assets/white-logo.png" class="logo"
                /></a>

                <div class="nav-lg" id="sentech-nav">
                    <div class="navbar-nav ms-auto ml-5 mr-0 pt-2">
                        <Link
                            class="register nav-link nav-link-text pr-5"
                            aria-current="page"
                            href="/"
                            ><span class="">Home</span>
                        </Link>
                        <Link
                            class="nav-link nav-link-text pr-5"
                            aria-current="page"
                            href="/services"
                        >
                            Services
                        </Link>
                        <Link
                            class="nav-link nav-link-text pr-5"
                            aria-current="page"
                            href="/aboutus"
                            ><span>About us</span>
                        </Link>
                        <Link
                            class="nav-link nav-link-text pr-5"
                            aria-current="page"
                            href="/news"
                            ><span>News</span>
                        </Link>
                        <Link
                            class="nav-link nav-link-text mr-5"
                            aria-current="page"
                            href="/contactus"
                            ><span>Contact us</span>
                        </Link>

                        <Login />
                    </div>
                </div>
                <div class="mobile-nav pt-2">
                    <i
                        class="fa-solid fa-bars fa-2x"
                        @click="drawer = true"
                        style="cursor: pointer"
                    ></i>
                    <el-drawer
                        v-model="drawer"
                        :direction="direction"
                        size="60%"
                        :with-header="false"
                        style="
                            overflow: hidden !important;
                            background-color: #144f9f;
                        "
                    >
                        <div class="col-12 px-0 mx-0">
                            <div class="row">
                                <div class="col-6 mx-0">
                                    <Login />
                                </div>
                                <div class="col-6 col-6 mx-0">
                                    <Link
                                        class="register nav-link nav-link-text text-end"
                                        aria-current="page"
                                        href="/login"
                                        ><span class="">Register</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/login"
                                        ><span class="">Home</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/login"
                                        ><span class="">Services</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/login"
                                        ><span class="">About us</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/login"
                                        ><span class="">News</span>
                                    </Link>
                                </div>
                                <div class="col-12 pc-0 mx-0 pt-3">
                                    <Link
                                        class="register nav-link nav-link-text"
                                        aria-current="page"
                                        href="/login"
                                        ><span class="">Contact us</span>
                                    </Link>
                                </div>

                                <div class="col-4 pt-3">
                                    <a
                                        href="https://www.facebook.com/sentechLtd/"
                                        class="nav-link pt-3"
                                        target="_blank"
                                    >
                                        <svg
                                            width="20"
                                            height="20"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 320 512"
                                        >
                                            <path
                                                d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                                                fill=" #ffffff"
                                            ></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="col-4 pt-3">
                                    <a
                                        href="https://twitter.com/sentechltd"
                                        class="nav-link pt-3"
                                        target="_blank"
                                    >
                                        <svg
                                            width="22"
                                            height="22"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 448 512"
                                        >
                                            <path
                                                d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"
                                                fill=" #ffffff"
                                            ></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="col-4 pt-3">
                                    <a
                                        href="https://www.linkedin.com/company/sentech-soc-limited"
                                        class="nav-link pt-3"
                                        target="_blank"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="20"
                                            height="20"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                                                fill=" #ffffff"
                                            ></path>
                                        </svg>
                                    </a>
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

import { defineComponent, onMounted, ref, unref } from "vue";

export default defineComponent({
    props: ["showing"],
    components: {
        Link,
        Login,
        Register,
    },

    setup() {
        const drawer = ref(false);
        const direction = ref("ltr");

        const showingModal = (value) => {
            showing.value = value;
            // this.$emit('modal', value);
        };

        return {
            drawer,
            direction,
            showingModal,
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
.mobile-nav {
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

    .mobile-nav {
        display: flex;
        color: #fff;
    }

    .navbar .social {
        visibility: hidden;
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

<template>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div
                class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center"
            >
                <a class="navbar-brand brand-logo" href="../../index-2.html">
                    <img
                        src="sentech-logo2.png"
                        alt="Sentech Logo"
                        width="50px"
                    />
                </a>
                <a
                    class="navbar-brand brand-logo-mini"
                    href="../../index-2.html"
                >
                    <img
                        src="sentech-logo2.png"
                        alt="Sentech Logo"
                        width="50px"
                    />
                </a>
                <button
                    class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex"
                    type="button"
                    data-toggle="minimize"
                >
                    <span class="fa-solid fa-bars"></span>
                </button>
            </div>

            <div
                class="navbar-menu-wrapper d-flex align-items-center justify-content-end"
            >
                <ul class="navbar-nav mr-lg-2">
                    <li class="nav-item d-none d-lg-flex">
                        <a class="nav-link" href="#"> Calendar </a>
                    </li>
                    <li class="nav-item d-none d-lg-flex">
                        <a class="nav-link active" href="#"> Statistic </a>
                    </li>
                    <li class="nav-item d-none d-lg-flex">
                        <a class="nav-link" href="#"> Employee </a>
                    </li>
                </ul>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-flex">
                        <a
                            class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center"
                            id="notificationDropdown"
                            href="#"
                            data-toggle="dropdown"
                        >
                            <i class="fa-solid fa-bell mr-0"></i>
                            <span class="count bg-danger mr-3">2</span>
                        </a>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a
                            class="nav-link dropdown-toggle pl-0 pr-0"
                            href="#"
                            data-toggle="dropdown"
                            id="profileDropdown"
                        >
                            <span class="nav-profile-name"
                                >{{ $page.props.auth.user.name }}
                            </span>
                            <span
                                class="initials-background"
                                style="padding: 20px"
                                ><strong>{{
                                    $page.props.auth.user.name
                                        .charAt(0)
                                        .toUpperCase()
                                }}</strong></span
                            >
                        </a>
                        <div
                            class="dropdown-menu dropdown-menu-right navbar-dropdown"
                            aria-labelledby="profileDropdown"
                        >
                            <a class="dropdown-item">
                                <i class="typcn typcn-cog text-primary"></i>
                                Settings
                            </a>
                            <a class="dropdown-item">
                                <i class="typcn typcn-power text-primary"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button
                    class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                    type="button"
                    data-toggle="offcanvas"
                >
                    <span class="typcn typcn-th-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_settings-panel.html -->
            <div class="theme-setting-wrapper">
                <div id="settings-trigger">
                    <i class="fa-solid fa-gear"></i>
                </div>
            </div>
            <!-- partial -->
            <!-- partial:../../partials/_sidebar.html -->
            <nav class="sidebar sidebar-light-theme">
                <ul class="nav">
                    <div class="nav-item">
                        <div class="d-flex sidebar-profile col-12 text-start">
                            <div
                                class="sidebar-profile-image initials-background mx-1"
                            >
                                <i class="fa-solid fa-landmark"></i>
                            </div>
                            <div class="sidebar-profile-name text-center">
                                <p class="sidebar-name">
                                    {{ userdata[1] }}
                                </p>
                                <div class="sidebar-designation">
                                    <li
                                        v-for="(user, index) in userdata"
                                        :key="index"
                                    >
                                        {{ getRoleNames(user.roles) }}
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <!-- <i class="typcn typcn-device-desktop menu-icon"></i> -->
                            <i class="menu-icon fa-solid fa-house"></i>
                            <span class="menu-title">Dashboard </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <!-- <i class="typcn typcn-device-desktop menu-icon"></i> -->
                            <i class="fa-solid fa-chart-simple menu-icon"></i>
                            <span class="menu-title">Sentiment Analysis </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <i class="fa-solid fa-sliders menu-icon"></i>
                            <span class="menu-title"
                                >Predictive Maintenance</span
                            >
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <i class="fa-solid fa-clipboard menu-icon"></i>
                            <span class="menu-title">Reporting</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <i class="fa-solid fa-comments menu-icon"></i>
                            <span class="menu-title">Feedback</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <i class="fa-solid fa-users-gear menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <i class="fa-solid fa-lock menu-icon"></i>
                            <span class="menu-title"
                                >Roles and permissions</span
                            >
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index-2.html">
                            <i class="fa-solid fa-business-time menu-icon"></i>
                            <span class="menu-title">Companies</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="row"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
</template>

<script>
import { Link } from "@inertiajs/vue3";
import arrow from "../../../../public/svg/arrow.svg";

import { defineComponent, onMounted, ref } from "vue";

export default defineComponent({
    name: "navigation",

    setup() {
        const userdata = ref([]);
        const getuser = async () => {
            const response = await axios.get("/user");
            userdata.value = response.data;
        };

        const getRoleNames = (roles) => {
            if (Array.isArray(roles)) {
                return roles.map((role) => role.name).join(", ");
            } else {
                return "";
            }
        };

        onMounted(() => {
            getuser();
        });

        return {
            userdata,
            getRoleNames,
        };
    },
});
</script>

<style lang="scss" scoped>
.nav-link:hover,
.nav-link:focus {
    color: #144f9f;
}
.initials-background {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    font-weight: 600;
    width: 35px;
    height: 35px;
    background-color: #144f9f;
    color: #fff;
    border-radius: 100%;
    margin-left: 20px;
}

.sidebar-name {
    color: #144f9f !important;
}
// .menu-title {
//     color: black !important;
// }
</style>

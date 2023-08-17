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
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a
                                    class="nav-link dropdown-toggle login-name"
                                    href="#"
                                    id="navbarDropdownMenuLink"
                                    role="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    {{ $page.props.auth.user.name }}
                                    <span class="initials-background"
                                        ><strong>{{
                                            $page.props.auth.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}</strong></span
                                    >
                                </a>
                                <ul
                                    class="dropdown-menu"
                                    aria-labelledby="navbarDropdownMenuLink"
                                >
                                    <li>
                                        <Link
                                            href="/dashboard"
                                            method="post"
                                            as="link"
                                            class="nav-link px-3"
                                            >Dashboard</Link
                                        >
                                    </li>
                                    <li>
                                        <Link
                                            href="/profile"
                                            method="post"
                                            as="link"
                                            class="nav-link px-3"
                                            >Profile</Link
                                        >
                                    </li>
                                    <li>
                                        <Link
                                            href="/help"
                                            method="post"
                                            as="link"
                                            class="nav-link px-3"
                                            >Help</Link
                                        >
                                    </li>
                                    <li>
                                        <Link
                                            href="/logout"
                                            method="post"
                                            as="link"
                                            class="nav-link px-3"
                                            >Logout</Link
                                        >
                                    </li>
                                </ul>
                            </li>
                        </ul>
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
                        <Link
                            class="nav-link"
                            href="/dashboard"
                            aria-current="page"
                            :class="{ active: $page.url === '/dashboard' }"
                        >
                            <i class="menu-icon fa-solid fa-house"></i>
                            <span class="menu-title">Dashboard </span>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/sentiment-analysis"
                            :class="{
                                active: $page.url === '/sentiment-analysis',
                            }"
                        >
                            <i class="fa-solid fa-chart-simple menu-icon"></i>
                            <span class="menu-title">Sentiment Analysis </span>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/predictive-maintenance"
                            :class="{
                                active: $page.url === '/predictive-maintenance',
                            }"
                        >
                            <i class="fa-solid fa-sliders menu-icon"></i>
                            <span class="menu-title"
                                >Predictive Maintenance</span
                            >
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/reporting"
                            :class="{ active: $page.url === '/reporting' }"
                        >
                            <i class="fa-solid fa-clipboard menu-icon"></i>
                            <span class="menu-title">Reporting</span>
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/dashboard"
                            :class="{ active: $page.url === '/feedback' }"
                        >
                            <i class="fa-solid fa-comments menu-icon"></i>
                            <span class="menu-title">Feedback</span>
                        </Link>
                    </li>

                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/admin/roles"
                            :class="{ active: $page.url === '/Roles' }"
                        >
                            <i class="fa-solid fa-lock menu-icon"></i>
                            <span class="menu-title"
                                >Roles and permissions</span
                            >
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/admin/getUsers"
                            :class="{ active: $page.url === '/Users' }"
                        >
                            <i class="fa-solid fa-users-gear menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </Link>
                    </li>

                    <li class="nav-item" v-if="company_type == 'super_company'">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/organizantions"
                            :class="{ active: $page.url === '/Companies' }"
                        >
                            <i class="fa-solid fa-business-time menu-icon"></i>
                            <span class="menu-title">Companies</span>
                        </Link>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="row">
                                    <div class="card-body">
                                        <slot></slot>
                                    </div>
                                </div>
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

import { defineComponent, onMounted, ref } from "vue";

export default defineComponent({
    name: "navigation",
    components: { Link },

    setup() {
        const company_type = ref([]);
        const userdata = ref([]);

        const getuser = async () => {
            const response = await axios.get("/user");
            userdata.value = response.data;
            company_type.value = response.data[0].company.companyType;
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
            company_type,
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

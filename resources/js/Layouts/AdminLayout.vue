<template>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->

        <!-- partial -->
        <div
            class="container-fluid page-body-wrapper"
            style="min-height: 100vh"
        >
            <nav class="sidebar sidebar-light-theme">
                <div class="col-12 text-start pt-4 pb-5 mb-3">
                    <img :src="Logo" alt="Sentech Logo" height="40" />
                </div>
                <ul class="nav mx-4">
                    <li
                        class="nav-item"
                        :class="{ active: $page.url === '/dashboard' }"
                    >
                        <Link
                            class="nav-link mb-2"
                            href="/dashboard"
                            aria-current="page"
                        >
                            <span class="menu-title">Home </span>
                        </Link>
                    </li>
                    <li
                        class="nav-item mb-2"
                        :class="{
                            active:
                                $page.url === '/admin/sentiments/overview' ||
                                $page.url === '/admin/sentiments/timelines',
                        }"
                    >
                        <a class="nav-link" @click="toggleCollapse">
                            <span class="menu-title">Sentiment Analysis </span>
                        </a>
                        <div
                            :class="['collapse', { show: isCollapsed }]"
                            id="ui-basic"
                            style=""
                        >
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/admin/sentiments/overview"
                                        :class="{
                                            activeSub:
                                                $page.url ===
                                                '/admin/sentiments/overview',
                                        }"
                                    >
                                        Overview</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/admin/sentiments/timelines"
                                        :class="{
                                            activeSub:
                                                $page.url ===
                                                '/admin/sentiments/timelines',
                                        }"
                                        >Time Lines</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/sentiment-analysis/trends"
                                        >Trends</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/sentiment-analysis/others"
                                        >Others</Link
                                    >
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li
                        class="nav-item mb-2"
                        :class="{
                            active:
                                $page.url ===
                                    '/admin/predictive-maintenance/national-sites' ||
                                $page.url ===
                                    '/admin/predictive-maintenance/predictions' ||
                                $page.url ===
                                    '/admin/predictive-maintenance/device-config' ||
                                $page.url ===
                                    '/admin/predictive-maintenance/alarm-list',
                        }"
                    >
                        <a class="nav-link" @click="toggleCollapse_pm">
                            <span class="menu-title"
                                >Predictive Maintenance
                            </span>
                        </a>
                        <div
                            :class="['collapse', { show: isCollapsed_pm }]"
                            id="ui-basic"
                            style=""
                        >
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/admin/predictive-maintenance/national-sites"
                                        :class="{
                                            activeSub:
                                                $page.url ===
                                                '/admin/predictive-maintenance/national-sites',
                                        }"
                                    >
                                        National Sites</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/admin/predictive-maintenance/predictions"
                                        :class="{
                                            activeSub:
                                                $page.url ===
                                                '/admin/predictive-maintenance/predictions',
                                        }"
                                        >Prediction</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/admin/predictive-maintenance/device-config"
                                        :class="{
                                            activeSub:
                                                $page.url ===
                                                '/admin/predictive-maintenance/device-config',
                                        }"
                                        >Device Config</Link
                                    >
                                </li>
                                <li class="nav-item">
                                    <Link
                                        class="nav-link"
                                        href="/admin/predictive-maintenance/alarm-list"
                                        :class="{
                                            activeSub:
                                                $page.url ===
                                                '/admin/predictive-maintenance/alarm-list',
                                        }"
                                        >Alarm List</Link
                                    >
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item mb-2">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/reporting"
                            :class="{ active: $page.url === '/reporting' }"
                        >
                            <span class="menu-title">Reporting</span>
                        </Link>
                    </li>
                    <li class="nav-item mb-2">
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/dashboard"
                            :class="{ active: $page.url === '/feedback' }"
                        >
                            <span class="menu-title">Feedback</span>
                        </Link>
                    </li>

                    <li
                        class="nav-item mb-2"
                        :class="{ active: $page.url === '/admin/roles' }"
                    >
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/admin/roles"
                        >
                            <span class="menu-title"
                                >Roles and permissions</span
                            >
                        </Link>
                    </li>
                    <li
                        class="nav-item mb-2"
                        :class="{ active: $page.url === '/admin/getUsers' }"
                    >
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/admin/getUsers"
                            :class="{ active: $page.url === '/Users' }"
                        >
                            <span class="menu-title">Users </span>
                        </Link>
                    </li>

                    <li
                        class="nav-item"
                        :class="{ active: $page.url === '/organizantions' }"
                        v-if="company_type == 'super_company'"
                    >
                        <Link
                            class="nav-link"
                            aria-current="page"
                            href="/organizantions"
                        >
                            <span class="menu-title">Companies </span>
                        </Link>
                    </li>
                </ul>
            </nav>
            <!-- partial -->

            <div class="main-panel">
                <div
                    class="content-wrapper"
                    style="background-color: #f8f8f8 !important"
                >
                    <div class="col-12 text-end mb-4">
                        <a
                            class="nav-link dropdown-toggle pl-0 pr-0"
                            @click="toggleCollapse_user"
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
                        <div class="d-flex justify-content-end">
                            <div
                                style="background-color: #ffff"
                                class="navbar-dropdown shadow col-2 py-4"
                                :class="[
                                    'collapse',
                                    { hideUser: isCollapsed_user },
                                ]"
                            >
                                <Link
                                    href="/dashboard"
                                    method="get"
                                    as="link"
                                    class="nav-link px-3"
                                    >Dashboard</Link
                                >

                                <Link
                                    href="/profile"
                                    method="post"
                                    as="link"
                                    class="nav-link px-3"
                                    >Profile</Link
                                >
                                <Link
                                    href="/help"
                                    method="post"
                                    as="link"
                                    class="nav-link px-3"
                                    >Help</Link
                                >
                                <Link
                                    href="/logout"
                                    method="post"
                                    as="link"
                                    class="nav-link px-3"
                                    >Logout</Link
                                >
                            </div>
                        </div>
                    </div>
                    <div class="row px-2">
                        <div class="col-12 grid-margin stretch-card">
                            <slot></slot>
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

import Logo from "../assets/sentech-logo2.jpg";

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

        const isCollapsed = ref(false);

        const toggleCollapse = () => {
            isCollapsed.value = !isCollapsed.value;
        };

        const isCollapsed_pm = ref(false);

        const toggleCollapse_pm = () => {
            isCollapsed_pm.value = !isCollapsed_pm.value;
        };

        const isCollapsed_user = ref(false);

        const toggleCollapse_user = () => {
            isCollapsed_user.value = !isCollapsed_user.value;
        };

        onMounted(() => {
            getuser();
        });

        return {
            userdata,
            getRoleNames,
            company_type,
            Logo,
            toggleCollapse,
            isCollapsed,
            toggleCollapse_pm,
            isCollapsed_pm,
            toggleCollapse_user,
            isCollapsed_user,
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
.activeSub {
    color: #144f9f !important;
    font-weight: bold;
}

.hideUser {
    display: inline !important;
    position: absolute;
    z-index: 1000;
}
</style>

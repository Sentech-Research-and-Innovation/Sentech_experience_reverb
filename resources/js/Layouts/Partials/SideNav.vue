<template>
    <nav
        id="sidebarMenu"
        class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
    >
        <div class="position-sticky pt-3">
            <div class="cm-logo">
                <img src="sentech-logo2.png" alt="Sentech Logo" />
            </div>

            <ul class="nav flex-column">
                <li class="nav-item sidebar-links">
                    <Link
                        v-if="is('AdmiN')"
                        class="nav-link"
                        aria-current="page"
                        href="/dashboard"
                        :class="{ active: $page.url === '/dashboard' }"
                    >
                        <span class="nav-link-item">Home</span>
                    </Link>
                    <Link
                        class="nav-link"
                        aria-current="page"
                        href="/sentiment-analysis"
                        :class="{ active: $page.url === '/sentiment-analysis' }"
                    >
                        <span class="nav-link-item">Sentiment Analysis</span>
                    </Link>
                    <Link
                        class="nav-link"
                        aria-current="page"
                        href="/predictive-maintenance"
                        :class="{
                            active: $page.url === '/predictive-maintenance',
                        }"
                    >
                        <span class="nav-link-item"
                            >Predictive Maintenance
                        </span>
                    </Link>

                    <Link
                        class="nav-link"
                        aria-current="page"
                        href="/reporting"
                        :class="{ active: $page.url === '/reporting' }"
                    >
                        <span class="nav-link-item">Reporting </span>
                    </Link>

                    <Link
                        class="nav-link"
                        aria-current="page"
                        href="/dashboard"
                        :class="{ active: $page.url === '/feedback' }"
                    >
                        <span class="nav-link-item">Feedback </span>
                    </Link>

                    <Link
                        v-if="can('roles-read')"
                        class="nav-link"
                        aria-current="page"
                        href="/admin/roles"
                        :class="{ active: $page.url === '/Roles' }"
                    >
                        <span class="nav-link-item">Roles </span>
                    </Link>
                    <Link
                        class="nav-link"
                        aria-current="page"
                        href="/admin/getUsers"
                        :class="{ active: $page.url === '/Roles' }"
                    >
                        <span class="nav-link-item">Users </span>
                    </Link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import arrow from "../../../../public/svg/arrow.svg";

export default {
    components: { Link, arrow },
    mounted() {},
};
</script>

<style lang="scss" scoped>
.nav-link.active {
    background: #144f9f;
    color: #fff !important;
    border-radius: 8px;
    -webkit-transition: background-color 1000ms ease;
    transition: background-color 1000ms ease;
}
.nav-link a:hover {
    -webkit-transition: background-color 1000ms ease;
    transition: background-color 1000ms ease;
}

.nav-link-item {
    margin: 20px;
}
.sidebar {
    position: fixed;
    padding: 35px;
    background-color: #fff !important;
    // NEST
    .cm-logo {
        font-size: 30px;
        margin-bottom: 45px;
        // NEST
        img {
            width: 100%;
        }
        span {
            display: block;
            font-weight: 600;
            line-height: 1.2;
            &:first-child {
                font-size: 18px;
            }
        }
    }

    .dashboard-main {
        margin-bottom: 35px;
        // NEST
        h4 {
            font-size: 36px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 25px;
        }

        .dash-role {
            padding: 10px 15px;
            background-color: #144f9f;
            border-radius: 15px;
            color: #fff;
            text-align: center;
            font-weight: 600;
        }
    }

    .sidebar-links {
        // NEST
        a {
            font-weight: normal;
            display: block;
            padding: 10px 0;
            /*border-bottom: 1px solid #144F9F;*/

            &.active {
                color: #144f9f;
            }
        }
    }
}
.nav-link {
    background: rgba(20, 79, 159, 0.08);
    color: #706f6f !important;
    border-radius: 8px;
    margin-bottom: 5px;
}
</style>

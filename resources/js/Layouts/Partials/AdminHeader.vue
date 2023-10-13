<template>
    <div style="min-height: 100vh; position: fixed">
        <div class="col-12 text-start pt-lg-4 pb-lg-5 mb-3">
            <img :src="Logo" alt="Sentech Logo" height="25" />
        </div>
        <ul class="nav list-group text-start mx-lg-4">
            <li
                class="nav-item"
                :class="{ active: $page.url === '/dashboard' }"
            >
                <Link class="nav-link" href="/dashboard">
                    <span class="menu-title">Home </span>
                </Link>
            </li>
            <li
                class="nav-item"
                :class="{
                    active:
                        $page.url === '/admin/sentiments/all' ||
                        $page.url === '/admin/sentiments/overview' ||
                        $page.url === '/admin/sentiments/timelines' ||
                        $page.url === '/admin/sentiments/trends' ||
                        $page.url === '/admin/sentiments/others',
                }"
            >
                <Link class="nav-link" href="/admin/sentiments/all">
                    <span class="menu-title">Sentiment Analysis </span>
                </Link>
            </li>

            <li
                class="nav-item"
                :class="{
                    active: $page.url === '/admin/predictive-maintenance/index',
                }"
            >
                <Link
                    class="nav-link mb-2"
                    href="/admin/predictive-maintenance/index"
                >
                    <span class="menu-title">Predictive Maintenance </span>
                </Link>
            </li>

            <li class="nav-item">
                <Link
                    class="nav-link"
                    href="/reporting"
                    :class="{ active: $page.url === '/reporting' }"
                >
                    <span class="menu-title">Reporting</span>
                </Link>
            </li>
            <li class="nav-item">
                <Link
                    class="nav-link"
                    href="/dashboard"
                    :class="{ active: $page.url === '/feedback' }"
                >
                    <span class="menu-title">Feedback</span>
                </Link>
            </li>

            <li
                class="nav-item"
                :class="{ active: $page.url === '/admin/roles' }"
            >
                <Link class="nav-link" href="/admin/roles">
                    <span class="menu-title">Roles and permissions</span>
                </Link>
            </li>
            <li
                class="nav-item"
                :class="{ active: $page.url === '/admin/getUsers' }"
            >
                <Link
                    class="nav-link"
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
                <Link class="nav-link" href="/organizantions">
                    <span class="menu-title">Companies </span>
                </Link>
            </li>

            <li class="nav-item mt-lg-5">
                <Link href="/admin/weather/forcast">
                    <Weather />
                </Link>
            </li>
            <li class="nav-item pt-2">
                <Link class="nav-link">
                    <span class="menu-title">Settings </span>
                </Link>
            </li>
            <li class="nav-item">
                <Link class="nav-link">
                    <span class="menu-title">Help? </span>
                </Link>
            </li>
        </ul>
    </div>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import { Link } from "@inertiajs/vue3";

import Logo from "../../assets/sentech-logo2.png";

import Weather from "../WeatherWidget.vue";

export default defineComponent({
    components: {
        Weather,
        Link,
    },
    setup() {
        return {
            Logo,
        };
    },
});
</script>

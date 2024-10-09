<template>
    <div style="min-height: 100vh; position: fixed">
        <div class="col-12 pt-lg-4">
            <div class="col-12 text-start pt-lg-4 pb-lg-5 mb-3 admin-logo">
                <!--<img :src="Logo" alt="Sentech Logo" height="45" /> -->
            </div>
        </div>
        <ul class="nav list-group text-start mx-lg-0">
            <li
                class="nav-item"
                :class="{ active: $page.url === '/admin/dashboard' }"
            >
                <a class="nav-link" href="/admin/dashboard">
                    <span class="menu-title">Home </span>
                </a>
            </li>
            <li
                v-if="can('sentiment_Analysis-read_overview')"
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
                <a class="nav-link" href="/admin/sentiments/all">
                    <span class="menu-title">Sentiment Analysis </span>
                </a>
            </li>

            <li
                v-if="can('predictive_maintenance-read_master_view')"
                class="nav-item"
                :class="{
                    active:
                        $page.url === '/admin/predictive-maintenance/index' ||
                        $page.url ===
                            '/admin/predictive-maintenance/predictions/detailed-view',
                }"
            >
                <a class="nav-link" href="/admin/predictive-maintenance/index">
                    <span class="menu-title">Predictive Maintenance </span>
                </a>
            </li>

            <li
                v-if="can('roles-read')"
                class="nav-item pt-0"
                :class="{ active: $page.url === '/admin/roles' }"
            >
                <a class="nav-link" href="/admin/roles">
                    <span class="menu-title">Roles and permissions</span>
                </a>
            </li>
            <li
                v-if="can('users-read')"
                class="nav-item"
                :class="{ active: $page.url === '/admin/getUsers' }"
            >
                <a
                    class="nav-link"
                    href="/admin/getUsers"
                    :class="{ active: $page.url === '/Users' }"
                >
                    <span class="menu-title">Users </span>
                </a>
            </li>

            <li
                v-if="can('companies-read_approved')"
                class="nav-item"
                :class="{ active: $page.url === '/organizantions/approved' }"
            >
                <!-- v-if="company_type == 'super_company'" -->
                <a class="nav-link" href="/organizantions/approved">
                    <span class="menu-title">Companies </span>
                </a>
            </li>

            <li
                class="nav-item my-lg-5"
                style="text-decoration: none !important"
            >
                <a
                    href="/web/weather/forcast"
                    style="text-decoration: none !important"
                >
                    <Weather />
                </a>
            </li>
            <!-- <li class="nav-item pt-2">
                <a class="nav-link">
                    <span class="menu-title">Settings </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link">
                    <span class="menu-title">Help? </span>
                </a>
            </li> -->
        </ul>
    </div>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import { Link } from "@inertiajs/vue3";

import Weather from "../WeatherWidget.vue";

export default defineComponent({
    components: {
        Weather,
        Link,
    },
    setup() {
        return {};
    },
});
</script>

import "./bootstrap";
import "./assets/styles.css";
import "./assets/bundleStyles.css";
import "./assets/typicons.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import * as bootstrap from "bootstrap";
// import JSONView from 'vue-json-component';
import { createVuetify } from "vuetify";
// import 'vuetify/styles';
import { VDataTable } from "vuetify/labs/VDataTable";
import { VToolbar } from "vuetify/components/VToolbar";
import { VBtn } from "vuetify/components/VBtn";

import { VDialog } from "vuetify/components/VDialog";
import { VDivider } from "vuetify/components/VDivider";
import { VCard, VCardText } from "vuetify/components/VCard";
import { VProgressLinear } from "vuetify/components/VProgressLinear";
import { VSelect } from "vuetify/components/VSelect";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

import * as directives from "vuetify/directives";
import { createPinia } from "pinia";

import VueApexCharts from "vue3-apexcharts";

const pinia = createPinia();

const vuetify = createVuetify({
    components: {
        VDataTable,
        VToolbar,
        VBtn,
        VDialog,
        VDivider,
        VCard,
        VProgressLinear,
        VSelect,
        VCardText,
    },
    directives,
    theme: { defaultTheme: "light" },
});

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Sentech";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return (
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(bootstrap)
                .use(vuetify)
                .use(ZiggyVue, Ziggy)
                .use(pinia)
                .use(LaravelPermissionToVueJS)
                .use(VueApexCharts)
                // .use(JSONView)
                .mount(el)
        );
    },
    progress: {
        color: "#4B5563",
    },
});

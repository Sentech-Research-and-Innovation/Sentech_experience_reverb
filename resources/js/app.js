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
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import VueVectorMap from "vuevectormap";
import "vuevectormap/src/scss/vuevectormap.scss";
import jsVectorMap from "jsvectormap";
window.jsVectorMap = jsVectorMap;

import "jsvectormap/dist/maps/world";

import vuescroll from "vuescroll";

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
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(bootstrap)
            .use(vuetify)
            .use(ZiggyVue, Ziggy)
            .use(pinia)
            .use(LaravelPermissionToVueJS)
            .use(VueApexCharts)
            .use(VueDatePicker)
            .use(VueVectorMap, {
                backgroundColor: "#f6f6f6",
            })
            .use(vuescroll, {
                ops: {
                    // The global config
                    vuescroll: {
                        checkShiftKey: true,
                        locking: false,
                        // deltaPercent: 0.75
                    },
                    scrollButton: {
                        enable: true,
                        background: "rgb(3, 185, 118)",
                        opacity: 1,
                        step: 180,
                        mousedownStep: 30,
                    },
                    bar: {
                        opacity: "0.5",
                        background: "blue",
                    },
                    scrollPanel: {
                        initialScrollY: true,
                        initialScrollX: true,
                        scrollingX: true,
                        scrollingY: true,
                        speed: 300,
                        easing: undefined,
                        verticalNativeBarPos: "right",
                    },
                    rail: {
                        background: "#A3ACBC",
                        opacity: 0.3,
                        size: "1%",
                        specifyBorderRadius: "1%",
                        gutterOfEnds: "0",
                        gutterOfSide: "0",
                        keepShow: false,
                    },
                },
            })
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});

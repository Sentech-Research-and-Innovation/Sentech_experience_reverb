import "./bootstrap";
import "./assets/styles.css";
import "./assets/bundleStyles.css";
import "./assets/typicons.css";

import "./assets/lightTheme.css";
import "./assets/darkTheme.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import * as bootstrap from "bootstrap";

import ElementPlus from "element-plus";
import "element-plus/dist/index.css";

import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";

import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

import VueApexCharts from "vue3-apexcharts";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import VueVectorMap from "vuevectormap";
import "vuevectormap/src/scss/vuevectormap.scss";
import jsVectorMap from "jsvectormap";
window.jsVectorMap = jsVectorMap;

import "./assets/world-merc";

import vuescroll from "vuescroll";

import VueBlocksTree from "vue3-blocks-tree";
import "vue3-blocks-tree/dist/vue3-blocks-tree.css";

let defaultoptions = { treeName: "blocks-tree" };

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

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
                .use(ElementPlus)
                .use(plugin)
                .use(bootstrap)
                // .use(vuetify)
                .use(pinia)
                .use(LaravelPermissionToVueJS)
                .use(VueApexCharts)

                .use(VueDatePicker)
                .use(VueVectorMap, {
                    backgroundColor: "#fffff",
                    map: "south_africa",
                })
                .use(VueBlocksTree, defaultoptions)
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
                .mount(el)
        );
    },
    progress: {
        color: "#4B5563",
    },
});

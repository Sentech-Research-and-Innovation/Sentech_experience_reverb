<template>
    <div class="container-fluid page-body-wrapper" style="min-height: 100vh">
        <nav class="sidebar d-none d-lg-block d-xl-block" style="">
            <AdminHeaderVue />
        </nav>
        <!-- partial -->

        <div class="main-panel">
            <div class="col-12 px-0">
                <div class="col-12 py-4">
                    <div class="row">
                        <el-backtop :right="100" :bottom="100" />
                        <div
                            class="col-4 mx-0 px-0 d-xl-none d-xxl-block d-lg-none"
                        >
                            <div class="d-xl-none d-xxl-block d-lg-none">
                                <el-button
                                    type="primary"
                                    style="margin-left: 16px"
                                    @click="drawer = true"
                                    class="py-0"
                                >
                                    <i class="fa-solid fa-bars"></i>
                                </el-button>

                                <el-drawer
                                    v-model="drawer"
                                    :direction="direction"
                                    size="80%"
                                    :with-header="false"
                                    style="overflow: hidden !important"
                                >
                                    <nav
                                        class="sidebar"
                                        style="overflow: hidden !important"
                                    >
                                        <AdminHeaderVue />
                                    </nav>
                                </el-drawer>
                            </div>
                        </div>
                        <div class="col-lg-6 col-4">
                            <div v-if="colorMode === 'dark'">
                                <!-- <button @click="toggleMode">Swicth To Light</button> -->
                                <el-button
                                    @click="toggleMode"
                                    type="primary"
                                    :icon="Sunny"
                                    class="fs-5"
                                    circle
                                />
                            </div>
                            <div v-else>
                                <!-- <button @click="toggleMode">Switch to Dark</button> -->
                                <el-button
                                    @click="toggleMode"
                                    type="primary"
                                    :icon="Moon"
                                    class="fs-5"
                                    circle
                                />
                            </div>
                        </div>
                        <div class="col-lg-6 col-4 pt-0 text-end">
                            <el-popover
                                ref="popoverRef"
                                :virtual-ref="buttonRef"
                                trigger="click"
                                virtual-triggering
                            >
                                <span>
                                    <div class="d-flex justify-content-start">
                                        <div style="background-color: #ffff">
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
                                                style="cursor: pointer"
                                                href="/logout"
                                                method="post"
                                                as="link"
                                                class="nav-link px-3"
                                                >Logout</Link
                                            >
                                        </div>
                                    </div>
                                </span>
                            </el-popover>
                            <div class="d-flex justify-content-end">
                                <span
                                    class="nav-profile-name d-none d-lg-block d-xl-block pt-2"
                                    >{{ $page.props.auth.user.name }}
                                </span>
                                <span
                                    class="initials-background"
                                    ref="buttonRef"
                                    style="padding: 20px; cursor: pointer"
                                    ><strong>
                                        {{
                                            $page.props.auth.user.name
                                                .charAt(0)
                                                .toUpperCase()
                                        }}</strong
                                    ></span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <slot> </slot>
                    </div>
                </div>
            </div>
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</template>

<script>
import { Link } from "@inertiajs/vue3";

import { defineComponent, onMounted, ref, unref } from "vue";

import { ElMessageBox } from "element-plus";

import AdminHeaderVue from "./Partials/AdminHeader.vue";
import { ClickOutside as vClickOutside } from "element-plus";

import { Expand, Moon, Sunny } from "@element-plus/icons-vue";

import { useDark, useToggle, useColorMode } from "@vueuse/core";

export default defineComponent({
    name: "navigation",
    components: {
        Link,
        ElMessageBox,
        AdminHeaderVue,
        vClickOutside,
    },

    setup() {
        const drawer = ref(false);
        const direction = ref("ltr");

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

        const buttonRef = ref();
        const popoverRef = ref();
        const onClickOutside = () => {
            unref(popoverRef).popperRef?.delayHide?.();
        };
        const isDark = useDark({
            selector: "body",
            attribute: "class",
            valueDark: "dark",
            valueLight: "light",
        });

        const colorMode = useColorMode();
        const toggleDark = useToggle(isDark);

        const toggleMode = () => {
            colorMode.value = colorMode.value === "light" ? "dark" : "light";
        };
        return {
            userdata,
            getRoleNames,
            company_type,
            drawer,
            direction,
            buttonRef,
            onClickOutside,
            Expand,
            toggleDark,
            isDark,
            colorMode,
            toggleMode,
            Moon,
            Sunny,
        };
    },
});
</script>

<style lang="scss" scoped>
.nav-link:hover,
.nav-link:focus {
    color: #144f9f;
}
.dropdown-toggle {
    cursor: pointer;
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

// @media (max-width: 768px) {
//     .sidebar {
//         display: none; /* Hide sidebar on smaller screens */
//     }

//     .drawer-mobile {
//         display: none;
//     }

//     .main-panel {
//         width: 100%; /* Take full width on smaller screens */
//     }

//     .navbar-dropdown {
//         width: 100%; /* Take full width on smaller screens */
//         /* Add more styles as needed for smaller screens */
//     }

//     .initials-background {
//         margin-left: 0; /* Center initials on smaller screens */
//     }

//     /* Add more responsive styles as needed for smaller screens */
// }

.sidebar-mobile {
    min-height: 100vh;
    background: #ffffff;
    font-family: "Roboto", sans-serif;
    padding: 0;
    width: 90px;
    z-index: 11;
}

.sidebar-mobile .nav {
    flex-wrap: nowrap;
    flex-direction: column;
    margin-bottom: 60px;
}

.sidebar-mobile .nav .nav-item .nav-link .menu-title {
    color: #737272;
    display: inline-block;
    font-size: 15px;
    line-height: 1;
    vertical-align: middle;
    font-weight: 500;
}

.el-button--primary {
    background-color: #144f9f !important;
}

@media only screen and (max-width: 600px) {
    .sidebar {
        border-right: none !important;
    }
}
</style>

<style></style>

<template>
    <span class="px-5 pt-2">
        <el-badge :value="notificationsCount" type="danger">
            <el-button
                :icon="BellFilled"
                class="fs-5 notificationBell"
                ref="buttonRef"
            >
            </el-button>
        </el-badge>
    </span>
    <div>
        <el-popover
            ref="popoverRef"
            popper-class="notifications-container px-0 mx-0"
            :virtual-ref="buttonRef"
            trigger="click"
            virtual-triggering
        >
            <div class="col-12 px-0">
                <div class="row px-4 py-2">
                    <div class="col-8 text-start">
                        <h4>Notifications</h4>
                    </div>
                    <div class="col-4 text-end">
                        <i class="fa-solid fa-list-check fa-lg"></i>
                    </div>
                </div>
                <div
                    class="border-top"
                    v-for="(notification, index) in notifications"
                    :key="index"
                >
                    <div class="col-12 px-0">
                        <div class="d-flex">
                            <div class="col-2 text-center pt-3">
                                <i
                                    v-if="notification.active == 1"
                                    class="fa-solid fa-bell fa-lg"
                                    style="color: #409eff"
                                ></i>
                                <i v-else class="fa-regular fa-bell fa-lg"></i>
                            </div>
                            <div
                                class="notificationsFalse py-2 col-10 px-0"
                                :class="{
                                    notificationsTrue: notification.active == 1,
                                }"
                            >
                                <Link
                                    :href="notification.link"
                                    class="link-not"
                                >
                                    <!-- Display the notification details -->

                                    <span
                                        style="font-weight: 500; color: #000000"
                                    >
                                        {{ notification.notification_type }}
                                    </span>
                                    :
                                    {{ notification.message }}

                                    <div
                                        class="col-6 text-start fs-7 px-0"
                                        style="font-size: 11px"
                                    >
                                        {{ notification.created_at }}
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </el-popover>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

import { defineComponent, onMounted, ref, unref } from "vue";

import { BellFilled } from "@element-plus/icons-vue";
import { ElIcon } from "element-plus";

export default defineComponent({
    components: { Link },

    setup() {
        const notifications = ref(null);
        const notificationsCount = ref(0);

        const buttonRef = ref();
        const popoverRef = ref();

        const notificationsApi = async () => {
            const response = await axios.get("/admin/notifications");
            notifications.value = response.data;

            const activeNotifications = response.data.filter(
                (notification) => notification.active === 1
            );
            notificationsCount.value = activeNotifications.length;
        };

        onMounted(() => {
            notificationsApi();
        });

        return {
            BellFilled,
            notificationsCount,
            notifications,
            buttonRef,
            popoverRef,
        };
    },
});
</script>

<style scoped>
.notificationBell {
    color: #144f9f !important;
    cursor: pointer;
    border: none;
    padding: 0px !important;
    height: 0px !important;
}

.notificationsTrue {
    font-weight: 400;
}

.notificationsTrue .link-not {
    text-decoration: none !important;
    color: #409eff !important;
}

.notificationsFalse {
    font-weight: 400;
    font-size: 12px;
}

.notificationsFalse .link-not {
    text-decoration: none !important;
    color: #737272;
}
</style>
<style>
.notifications-container {
    width: 400px !important;
}
</style>

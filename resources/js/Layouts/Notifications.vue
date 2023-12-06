<template>
    <span class="pt-1 px-5">
        <el-badge :value="notificationsCount" type="primary">
            <el-button
                :icon="BellFilled"
                class="fs-5 notificationBell"
                circle
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
            <div class="col-12">
                <h4>Notifications</h4>
                <div
                    v-for="(notification, index) in notifications"
                    :key="index"
                >
                    <div
                        class="notificationsFalse py-3 my-2 rounded col-12 mx-0 px-2 mx-0"
                        :class="{
                            notificationsTrue: notification.active == 1,
                        }"
                    >
                        <Link :href="notification.link" class="link-not">
                            <!-- Display the notification details -->
                            {{ notification.message }}

                            <div class="col-12 px-0 pt-2">
                                <div class="row">
                                    <div class="col-6 text-start">
                                        {{ notification.notification_type }}
                                    </div>
                                    <div class="col-6 text-end fs-7">
                                        {{ notification.created_at }}
                                    </div>
                                </div>
                            </div>
                        </Link>
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

<style>
.notificationBell {
    color: #144f9f !important;
    cursor: pointer;
}
.notifications-container {
    width: 350px !important;
}

.notificationsTrue {
    background-color: #e3eefa !important;

    font-weight: 400;
}

.notificationsTrue .link-not {
    text-decoration: none !important;
    color: #409eff !important;
}

.notificationsFalse {
    font-weight: 400;
}

.notificationsFalse .link-not {
    text-decoration: none !important;
    color: #737272;
}
</style>

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
<style>
.notifications-container {
    width: 350px !important;
}
</style>

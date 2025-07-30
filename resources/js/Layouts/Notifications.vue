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
                        <i class="fa-solid fa-list-check fa-lg icon-color"></i>
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
                                    class="fa-regular fa-bell fa-lg icon-color"
                                ></i>
                            </div>
                            <div
                                class="notificationsFalse py-2 col-10 px-0"
                                :class="{
                                    notificationsFalse:
                                        notification.active == 1,
                                }"
                            >
                                <Link
                                    :href="notification.link"
                                    class="link-not"
                                >
                                    <span style="font-weight: 500">
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
import { defineComponent, onMounted, ref } from "vue";
import { BellFilled } from "@element-plus/icons-vue";

export default defineComponent({
    components: { Link },

    setup() {
        const notifications = ref([]);
        const notificationsCount = ref(0);
        const buttonRef = ref();
        const popoverRef = ref();

        const fetchNotifications = async () => {
            try {
                const response = await axios.get("/admin/notifications");
                notifications.value = response.data;
            } catch (error) {
                console.error("Failed to fetch notifications", error);
            }
        };

        const fetchUnreadCount = async () => {
            try {
                const response = await axios.get("/api/notifications/unread-count");
                notificationsCount.value = response.data.count;
            } catch (error) {
                console.error("Failed to fetch unread count", error);
            }
        };

        onMounted(() => {
            fetchNotifications();
            fetchUnreadCount();

            // Optional: Refresh count every 60 seconds
            setInterval(() => {
                fetchUnreadCount();
            }, 60000);
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

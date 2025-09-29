<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="page-wrapper">
        <div class="col-12 px-0 mx-0 profile-container">
            <!-- Background Cover Image -->
            <div
                class="cover-image"
                :style="{ backgroundImage: `url('${user.cover_photo_url || defaultCover}')` }"
            ></div>
            
            <div class="profile-content px-4">
                <!-- Profile Picture -->
                <div class="profile-picture-container">
                    <el-avatar
                        :src="user.profile_photo_url || defaultProfile"
                        :icon="!user.profile_photo_url ? UserFilled : ''"
                        class="blue-profile-image"
                    />
                </div>
                
                <!-- Profile Info -->
                <div class="profile-info mt-4">
                    <p class="profile-name">
                        {{ user.first_name }} {{ user.last_name }}
                    </p>
                    <p class="profile-title">
                        {{ user.roles[0]?.name }} at {{ user.company?.company_name }}
                    </p>
                    <div class="profile-contact mt-1">
                        <span class="contact-info">
                            {{ user.email || 'Email not provided' }},
                            {{ user.phoneNumber || 'Phone not provided' }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="profile-actions mt-4">
                    <el-button type="primary" @click="startCall">📞 Call</el-button>
                    <el-button type="info" @click="openLogs">📑 View Call Logs</el-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Logs Dialog -->
    <el-dialog v-model="logsVisible" title="Call Logs" width="400px">
        <ul class="call-logs">
            <li v-for="(log, index) in callLogs" :key="index">
                <span>{{ log.type }} with {{ log.name }}</span>
                <span class="time">{{ log.time }}</span>
            </li>
        </ul>
        <template #footer>
            <el-button @click="logsVisible = false">Close</el-button>
        </template>
    </el-dialog>

    <!-- Incoming Call Dialog -->
    <el-dialog v-model="incomingVisible" title="Incoming Call" width="300px" align-center>
        <p>{{ incomingCall.from }} is calling...</p>
        <div class="incoming-actions">
            <el-button type="success" @click="acceptCall">✅ Accept</el-button>
            <el-button type="danger" @click="declineCall">❌ Decline</el-button>
        </div>
    </el-dialog>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { UserFilled } from "@element-plus/icons-vue";

export default defineComponent({
    layout: AdminLayout,
    name: "profile-view",

    components: {
        Head,
    },

    props: {
        user: {
            type: Object,
            required: true,
        },
    },

    setup(props) {
        const defaultCover =
            "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6?q=80&w=1920&fit=crop";
        const defaultProfile =
            "https://images.unsplash.com/photo-1603415526960-f8f0a2b52f75?q=80&w=200&fit=crop";

        // UI States
        const logsVisible = ref(false);
        const callLogs = ref([
            { type: "Outgoing", name: "Alice", time: "2025-09-28 10:45" },
            { type: "Incoming (Missed)", name: "Bob", time: "2025-09-27 20:12" },
        ]);

        const incomingVisible = ref(false);
        const incomingCall = ref({ from: "Unknown" });

        // Functions
        function startCall() {
            // Here you’d emit offer → backend → callee
            callLogs.value.push({
                type: "Outgoing",
                name: props.user.first_name,
                time: new Date().toLocaleString(),
            });
            console.log("Calling user:", props.user.first_name);
        }

        function openLogs() {
            logsVisible.value = true;
        }

        function acceptCall() {
            console.log("Call accepted");
            incomingVisible.value = false;
        }

        function declineCall() {
            console.log("Call declined");
            incomingVisible.value = false;
        }

        // Simulate incoming call for demo
        setTimeout(() => {
            incomingCall.value = { from: "Charlie" };
            incomingVisible.value = true;
        }, 5000);

        return {
            UserFilled,
            user: props.user,
            defaultCover,
            defaultProfile,
            logsVisible,
            callLogs,
            openLogs,
            startCall,
            incomingVisible,
            incomingCall,
            acceptCall,
            declineCall,
        };
    },
});
</script>

<style scoped>
.page-wrapper {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.profile-container {
    position: relative;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    width: 85%;
    max-width: 1000px;
}

.cover-image {
    height: 200px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-size: cover;
    background-position: center;
    filter: brightness(0.85) sepia(0.3) hue-rotate(180deg) saturate(1.5);
}

.profile-picture-container {
    position: absolute;
    top: -75px;
    left: 20px;
    border-radius: 50%;
    background-color: #144f9f;
}

.blue-profile-image {
    width: 150px;
    height: 150px;
    font-size: 60px;
    background-color: #144f9f !important;
    color: #fff;
    border: 4px solid #ffffff;
}

.profile-info {
    padding-top: 90px;
}

.profile-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

.call-logs {
    list-style: none;
    padding: 0;
}

.call-logs li {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.call-logs .time {
    font-size: 12px;
    color: #888;
}

.incoming-actions {
    display: flex;
    justify-content: space-around;
    margin-top: 15px;
}
</style>

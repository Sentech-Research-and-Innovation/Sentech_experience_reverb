<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="page-wrapper">
        <div class="col-12 px-0 mx-0 profile-container">
            <!-- Background Cover Image -->
            <div
                class="cover-image"
                :style="{
                    backgroundImage: `url('${user.cover_photo_url || defaultCover}')`
                }"
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
                                {{ user.email || 'Email not provided' }}, {{ user.phoneNumber || 'Phone not provided' }}
                            </span>
                        </div>

                    </div>
            </div>

            <!-- Chat Section -->
            <!-- <ChatBox :receiver="user" /> -->
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import { UserFilled } from "@element-plus/icons-vue";
import ChatBox from "./ChatBox.vue";

export default defineComponent({
    layout: AdminLayout,
    name: "profile-view",

    components: {
        Head, ChatBox
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
        // dark abstract background

        const defaultProfile =
            "https://images.unsplash.com/photo-1603415526960-f8f0a2b52f75?q=80&w=200&fit=crop"; 
        // dark neutral gradient profile placeholder

        return {
            UserFilled,
            user: props.user,
            defaultCover,
            defaultProfile,
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
    transition: filter 0.3s ease;
}

.profile-content {
    position: relative;
    padding: 0 20px 30px 20px;
    z-index: 1;
}

.profile-picture-container {
    position: absolute;
    top: -75px;
    left: 20px;
    border-radius: 50%;
    background-color: #144f9f;
    z-index: 2;
}

.blue-profile-image {
    width: 150px;
    height: 150px;
    font-size: 60px;
    background-color: #144f9f !important;
    color: #fff;
    border: 4px solid #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.profile-info {
    padding-top: 90px;
}

.profile-name {
    font-size: 27px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #050505;
    line-height: 1.2;
}

.profile-title {
    font-size: 15px;
    font-weight: 400;
    color: #050505;
    margin-bottom: 5px;
    line-height: 1.4;
}

.profile-contact {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #65676b;
    margin-top: 10px;
}

.contact-item {
    color: #1877f2;
}

.contact-info {
    font-size: 12px;
    color: #65676b;
}

.contact-separator {
    color: #65676b;
}
</style>

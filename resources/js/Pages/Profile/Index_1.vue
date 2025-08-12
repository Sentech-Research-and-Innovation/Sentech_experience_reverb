<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="page-wrapper">
        <div class="col-12 px-0 mx-0 profile-container">
            <!-- Background Cover Image -->
            <div
                class="cover-image"
                :style="{
                    backgroundImage: `url('${user.coverImage || defaultCover}')`
                }"
            ></div>
            
            <div class="profile-content px-4">
                <!-- Profile Picture -->
                <div class="profile-picture-container">
                    <el-avatar
                        :src="user.profile_picture || defaultProfile"
                        :icon="!user.profile_picture ? UserFilled : ''"
                        style="
                            width: 150px;
                            height: 150px;
                            font-size: 60px;
                            background-color: #f0f2f5;
                        "
                    />
                </div>
                
                <!-- Profile Info -->
                <div class="profile-info mt-4">
                    <h1 class="profile-name">
                        {{ user.first_name }} {{ user.last_name }}
                    </h1>
                    <p class="profile-title">
                        {{ user.roles[0]?.name }} at {{ user.company?.company_name }}
                    </p>
                    <div class="profile-contact mt-3">
                        <span class="contact-item">{{ user.email }}</span>
                        <span class="contact-separator">·</span>
                        <span class="contact-item">{{ user.phoneNumber || 'Phone not provided' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent } from "vue";
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
            "https://via.placeholder.com/1200x300?text=Cover+Image";
        const defaultProfile =
            "https://via.placeholder.com/150?text=Profile";

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
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    width: 85%;
    max-width: 1000px;
}

.cover-image {
    height: 200px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-color: #e9ebee;
    background-size: cover;
    background-position: center;
}

.profile-content {
    position: relative;
    padding-bottom: 20px;
}

.profile-picture-container {
    position: absolute;
    top: -75px;
    left: 20px;
    border: 4px solid #ffffff;
    border-radius: 50%;
    background-color: #ffffff;
}

.profile-info {
    padding-top: 90px;
}

.profile-name {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #050505;
}

.profile-title {
    font-size: 20px;
    font-weight: 700;
    color: #050505;
    margin-bottom: 5px;
}

.profile-contact {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
}

.contact-item {
    color: #1877f2;
}

.contact-separator {
    color: #65676b;
}
</style>

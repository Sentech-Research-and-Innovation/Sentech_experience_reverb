<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="col-12 px-0 mx-0 profile-container">
        <!-- Background Cover Image -->
        <div class="cover-image"></div>
        
        <div class="profile-content px-5 py-4">
            <!-- Profile Picture -->
            <div class="profile-picture-container">
                <el-avatar
                    :src="user.profile_picture || ''"
                    :icon="UserFilled"
                    style="
                        width: 152px;
                        height: 152px;
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
                <p class="profile-location">
                    {{ user.location || 'Location not specified' }} · <span class="contact-info">Contact info</span>
                </p>
            </div>
        </div>

        <!-- Additional Sections -->
        <div class="profile-sections px-5 pb-4">
            <div class="section-divider"></div>
            
            <!-- Experience Section -->
            <div class="experience-section mt-4">
                <h2 class="section-title">Experience</h2>
                <div class="experience-item mt-3">
                    <h3 class="company-name">{{ user.company?.company_name }}</h3>
                    <p class="position-title">{{ user.roles[0]?.name }}</p>
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
        return {
            UserFilled,
            user: props.user,
        };
    },
});
</script>

<style scoped>
.profile-container {
    position: relative;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.cover-image {
    height: 200px;
    background-color: #e9ebee;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.profile-content {
    position: relative;
    padding: 24px 24px 16px 24px;
}

.profile-picture-container {
    position: absolute;
    top: -76px;
    left: 24px;
    border: 4px solid #ffffff;
    border-radius: 50%;
    background-color: #ffffff;
}

.profile-info {
    padding-top: 80px;
    margin-bottom: 16px;
}

.profile-name {
    font-size: 32px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #000000e6;
    line-height: 1.25;
}

.profile-title {
    font-size: 20px;
    color: #000000e6;
    margin-bottom: 8px;
    font-weight: 400;
    line-height: 1.4;
}

.profile-location {
    font-size: 16px;
    color: #00000099;
    margin-bottom: 16px;
}

.contact-info {
    color: #0a66c2;
    font-weight: 600;
}

.profile-sections {
    padding: 0 24px;
}

.section-divider {
    border-top: 1px solid #e0e0e0;
    margin: 0 -24px;
}

.section-title {
    font-size: 20px;
    font-weight: 600;
    color: #000000e6;
}

.experience-item {
    margin-bottom: 16px;
}

.company-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 4px;
    color: #000000e6;
}

.position-title {
    font-size: 16px;
    color: #00000099;
    margin-bottom: 0;
}
</style>

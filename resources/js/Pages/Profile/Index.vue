<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="page-wrapper">
        <!-- Profile Header Section -->
        <div class="profile-header-container">
            <div class="col-12 px-0 mx-0 profile-container">
                <!-- Background Cover Image -->
                <div class="cover-image-wrapper">
                    <div
                        class="cover-image"
                        :style="{
                            backgroundImage: `url('${user.coverImage || defaultCover}')`
                        }"
                    >
                        <!-- Cover image edit button -->
                        <div class="cover-image-edit">
                            <el-button 
                                circle 
                                class="edit-icon-button"
                                @click="openCoverImageDialog"
                            >
                                <el-icon><Camera /></el-icon>
                            </el-button>
                        </div>
                    </div>
                </div>
                
                <div class="profile-content px-4">
                    <!-- Profile Picture -->
                    <div class="profile-picture-wrapper">
                        <div class="profile-picture-container">
                            <el-avatar
                                :src="user.profile_photo_url"
                                :icon="!user.profile_photo_url ? UserFilled : ''"
                                class="blue-profile-image"
                            />
                            <!-- Profile picture edit button -->
                            <div class="profile-image-edit">
                                <el-button 
                                    circle 
                                    class="edit-icon-button"
                                    @click="openProfileImageDialog"
                                >
                                    <el-icon><Camera /></el-icon>
                                </el-button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Info Block -->
                <div class="profile-info-block" style="margin-top: -65px;">
                    <!-- Profile Info -->
                    <div class="profile-info text-center">
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

                <!-- 👇 EXTRA: Display full profile image -->
                <div v-if="user.profile_photo_url" class="profile-photo-display">
                    <img :src="user.profile_photo_url" alt="Profile" class="profile-photo-large" />
                </div>
            </div>
        </div>

        <!-- Image Upload Dialogs -->
        <el-dialog v-model="profileImageDialogVisible" 
                title="Profile Photo" 
                width="40%" > 
                <!-- Preview image --> 
                <div class="dialog-image-preview">
                    <template v-if="user.profile_photo_url"> 
                        <img 
                            :src="user.profile_photo_url" 
                            alt="Profile Preview" 
                            class="preview-large"
                        />
                    </template> 
                    <template v-else>
                        <div class="no-image-placeholder"> 
                            No image provided 
                        </div> 
                    </template>
                </div> 
                
                <!-- Actions--> 
                <div class="dialog-actions"> 
                    <el-upload 
                        class="action-button" 
                        action="/profile/upload-profile-image" 
                        name="file" 
                        :on-success="handleProfileImageSuccess" 
                        :show-file-list="false" > 
                        <div class="action-icon"> 
                            <el-icon><Edit /></el-icon> 
                            <span>Edit</span> 
                        </div> 
                    </el-upload>
        
                    <div 
                        v-if="user.profile_photo_url"
                        class="action-button" 
                        @click="deleteProfileImage" >
                        <div class="action-icon delete"> 
                            <el-icon><Delete /></el-icon> 
                            <span>Delete</span> 
                        </div> 
                    </div> 
                </div> 
        </el-dialog>

        <!-- Cover Photo Dialog -->
        <el-dialog 
            v-model="coverImageDialogVisible" 
            title="Cover Photo" 
            width="60%"
        >
            <!-- Preview -->
            <div class="dialog-image-preview">
                <template v-if="user.coverImage">
                    <img 
                        :src="user.coverImage" 
                        alt="Cover" 
                        style="max-width: 100%; border-radius: 8px;"
                    />
                </template>
                <template v-else>
                    <div class="no-cover-placeholder">
                        No image provided
                    </div>
                </template>
            </div>

            <!-- Actions -->
            <div class="dialog-actions">
                <el-upload
                    class="action-button"
                    action="/api/upload-cover-image"
                    :on-success="handleCoverImageSuccess"
                    :show-file-list="false"
                >
                    <div class="action-icon">
                        <el-icon><Edit /></el-icon>
                        <span>Edit</span>
                    </div>
                </el-upload>

                <div 
                    v-if="user.coverImage" 
                    class="action-button" 
                    @click="deleteCoverImage"
                >
                    <div class="action-icon delete">
                        <el-icon><Delete /></el-icon>
                        <span>Delete</span>
                    </div>
                </div>
            </div>
        </el-dialog>

        <!-- Form Section -->
        <!-- (no changes here, left as is) -->
        <div class="form-section">
            ...
        </div>
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { UserFilled, Camera } from "@element-plus/icons-vue";
import { Delete, UploadFilled, Edit } from "@element-plus/icons-vue";
import axios from "axios";

export default defineComponent({
    layout: AdminLayout,
    name: "profile-view",

    components: { Head, Link, Camera, Delete, UploadFilled, Edit },

    props: {
        user: { type: Object, required: true },
    },

    setup(props) {
        const defaultCover = "https://images.unsplash.com/photo-1517816743773-6e0fd518b4a6?q=80&w=1920&fit=crop";
        const defaultProfile = "https://images.unsplash.com/photo-1603415526960-f8f0a2b52f75?q=80&w=200&fit=crop";

        const form = ref({});
        const errors = ref({});
        const page = ref("profile");
        const success = ref(false);
        const successPassword = ref(false);
        const errorPassword = ref({});
        const formPassword = ref({});
        
        // Image upload dialogs
        const profileImageDialogVisible = ref(false);
        const coverImageDialogVisible = ref(false);

        const openProfileImageDialog = () => profileImageDialogVisible.value = true;
        const openCoverImageDialog = () => coverImageDialogVisible.value = true;

        const handleProfileImageSuccess = (response) => {
            profileImageDialogVisible.value = false;
            props.user.profile_photo_url = response.profile_photo_url;
            props.user.profile_photo_path = response.path;
            props.user = { ...props.user }; // trigger reactivity
        };

        const handleCoverImageSuccess = (response) => {
            coverImageDialogVisible.value = false;
            props.user.coverImage = response.path;
        };

        const updateDetails = async () => {
            errors.value = {};
            form.value = {
                first_name: props.user.first_name,
                last_name: props.user.last_name,
                phoneNumber: props.user.phoneNumber,
            };

            try {
                await axios.post(`/profile/update`, form.value);
                errors.value = {};
                success.value = true;
            } catch (err) {
                const res = err.response.data.errors;
                success.value = false;
                errors.value = {
                    first_name: res?.first_name?.[0] || "",
                    last_name: res?.last_name?.[0] || "",
                    phoneNumber: res?.phoneNumber?.[0] || "",
                };
            }
        };

        const changePassword = async () => {
            try {
                await axios.post(`/profile/update/password`, formPassword.value);
                errorPassword.value = {};
                successPassword.value = true;
            } catch (err) {
                const res = err.response.data.errors;
                successPassword.value = false;
                errorPassword.value = {
                    password: res?.password?.[0] || "",
                };
            }
        };

        const deleteProfileImage = async () => {
            await axios.delete('/profile/delete-profile-image');
            props.user.profile_photo_url = null;
        };

        const deleteCoverImage = async () => {
            await axios.delete('/admin/delete-profile-image');
            props.user.coverImage = null;
        };

        return {
            UserFilled,
            Camera,
            defaultCover,
            defaultProfile,
            page,
            user: props.user,
            updateDetails,
            errors,
            success,
            changePassword,
            formPassword,
            errorPassword,
            successPassword,
            profileImageDialogVisible,
            coverImageDialogVisible,
            openProfileImageDialog,
            openCoverImageDialog,
            handleProfileImageSuccess,
            handleCoverImageSuccess,
            deleteProfileImage,
            deleteCoverImage,
        };
    },
});
</script>

<style scoped>
/* your existing styles here... */

.profile-photo-display {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.profile-photo-large {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.preview-large {
    max-width: 250px;
    max-height: 250px;
    border-radius: 50%;
    object-fit: cover;
}
</style>

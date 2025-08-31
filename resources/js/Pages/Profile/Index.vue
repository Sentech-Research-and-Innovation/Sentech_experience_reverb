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
                            backgroundImage: `url('${user.cover_photo_url  || defaultCover}')`
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
                    <div class="profile-info">
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
            </div>
        </div>

        <!-- Image Upload Dialogs -->
        <el-dialog v-model="profileImageDialogVisible" 
                title="Profile Photo" 
                width="40%" > 
                <!-- Preview image --> 
                <div class="dialog-image-preview">
                    <template v-if="user.profile_photo_url"> 
                        <el-avatar 
                            :src="user.profile_photo_url" 
                            shape="circle" 
                            :size="180" /> 
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
                    action="/profile/upload-cover-image"
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
        <div class="form-section">
            <div class="col-12 rounded py-4 shadow-border">
                <div class="profile-nav-header col-12 px-0">
                    <nav class="nav nav-pills flex-column flex-sm-row py-3 mb-4">
                        <a
                            class="flex-sm-fill text-sm-center nav-link py-3"
                            :class="{ active: page === 'profile' }"
                            @click="page = 'profile'"
                        >
                            <strong>Profile Details</strong>
                        </a>

                        <a
                            class="flex-sm-fill text-sm-center nav-link py-3"
                            :class="{ active: page === 'password' }"
                            @click="page = 'password'"
                        >
                            <strong>Change Password</strong>
                        </a>
                    </nav>
                </div>

                <!-- Profile Details Form -->
                <div class="row register-form" v-if="page == 'profile'">
                    <div class="col-md-12" v-if="success">
                        <div class="alert alert-success">
                            You have successfully updated your details
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="firstName" class="form-label">First Name</label>
                            <el-input
                                v-model="user.first_name"
                                class="w-100 search-input"
                            />
                            <div class="text-danger pt-1">
                                {{ errors.first_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="lastName" class="form-label">Last Name</label>
                            <el-input
                                v-model="user.last_name"
                                class="w-100 search-input"
                            />
                            <div class="text-danger pt-1">
                                {{ errors.last_name }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phoneNumber" class="form-label">Phone Number</label>
                            <el-input
                                v-model="user.phoneNumber"
                                class="w-100 search-input"
                            />
                            <div class="text-danger pt-1">
                                {{ errors.phoneNumber }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div
                                @click="updateDetails"
                                class="btn btn-primary sentech-login-button d-flex justify-content-center align-items-center"
                            >
                                Submit
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Password Form -->
                <div class="row register-form" v-if="page == 'password'">
                    <div class="col-md-12" v-if="successPassword">
                        <div class="alert alert-success">
                            You have successfully updated your password
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Password" class="form-label">Password</label>
                            <el-input
                                v-model="formPassword.password"
                                class="w-100 search-input"
                                type="password"
                                show-password
                            />
                            <div class="text-danger pt-1">
                                {{ errorPassword.password }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                            <el-input
                                v-model="formPassword.password_confirmation"
                                class="w-100 search-input"
                                type="password"
                                show-password
                            />
                            <div class="text-danger pt-1"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <div
                                @click="changePassword"
                                class="btn btn-primary sentech-login-button d-flex justify-content-center align-items-center"
                            >
                                Submit
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    components: {
        Head,
        Link,
        Camera,
        Delete,
        UploadFilled,
        Edit,

    },

    props: {
        user: {
            type: Object,
            required: true,
        },
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

        const openProfileImageDialog = () => {
            profileImageDialogVisible.value = true;
        };

        const openCoverImageDialog = () => {
            coverImageDialogVisible.value = true;
        };

        

       const handleProfileImageSuccess = (response) => {
        profileImageDialogVisible.value = false;
            
            // Force a complete reactive update
            props.user.profile_photo_url = response.url;
            props.user.profile_photo_path = response.path;
            
            // This forces Vue to recognize the change
            props.user = { ...props.user };
            
        };


        const handleCoverImageSuccess = (response) => {
            coverImageDialogVisible.value = false;
            props.user.cover_photo_url = response.url; // backend returns the accessor url
            props.user.cover_photo_path = response.path; // optional, if you also send path
            props.user = { ...props.user };
        };

        const deleteCoverImage = async () => {
            await axios.delete('/profile/delete-cover-image');
            props.user.cover_photo_url = null;
            props.user.cover_photo_path = null;
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
.page-wrapper {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    padding: 20px;
    gap: 20px;
    min-height: 100vh;
}

.profile-header-container {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
}

.profile-container {
    position: relative;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
    width: 100%;
    overflow: hidden;
}

/* Cover image section */
.cover-image-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
}

.cover-image {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-size: cover;
    background-position: center;
    filter: brightness(0.85) sepia(0.3) hue-rotate(180deg) saturate(1.5);
}

/* Profile picture section */
.profile-picture-wrapper {
    position: relative;
    height: 150px;
}

.profile-picture-container {
    position: absolute;
    top: -75px;
    left: 20px;
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
}

.profile-content {
    position: relative;
    padding: 0 20;
    z-index: 1;
}

/* Profile info block */
.profile-info-block {
    background-color: #ffffff;
    padding: 10px;
    margin-top: -100px; /* Pull up closer to profile picture */
    margin-left: 30px;
    margin-bottom: 20px;    
    border-radius: 0 0 8px 8px;
    position: relative;
    z-index: 1;
    
}

.profile-info {
    margin-left: 0;
    padding-top: 10px;
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

.contact-info {
    font-size: 12px;
    color: #65676b;
    line-height: 1.5;
}

/* Edit buttons */
.cover-image-edit {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 3;
}

.profile-image-edit {
    position: absolute;
    bottom: 0;
    right: 0;
    z-index: 3;
}


.no-image-placeholder {
    width: 200px;
    height: 200px; 
    border: 2px dashed #ccc; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 14px; color: #aaa;
}

.edit-icon-button {
    background-color: white !important;
    border: none !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.edit-icon-button:hover {
    background-color: #f0f7ff !important;
    transform: scale(1.1);
}

/* Camera icon styling */
.el-icon {
    color: #144f9f !important;
    font-size: 20px;
    font-weight: bold;
}

/* Form section */
.form-section {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
}

.shadow-border {
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    background: white;
    padding: 20px;
}

.profile-nav-header {
    cursor: pointer;
}

.profile-nav-header .nav-link {
    color: #c5c2c2;
    background: none !important;
    border: none !important;
    border-bottom: 2px solid #ebe8e8 !important;
    border-radius: 0px;
    transition: all 0.3s ease;
}

.profile-nav-header .active {
    color: #144f9f;
    background: none !important;
    border: none !important;
    border-bottom: 2px solid #144f9f !important;
    border-radius: 0px;
}

.register-form {
    padding: 0 15px;
}

.search-input {
    width: 100%;
    border-radius: 4px;
    border: 1px solid #dcdfe6;
    transition: border-color 0.3s ease;
}

.search-input:hover {
    border-color: #c0c4cc;
}

.sentech-login-button {
    padding: 10px 20px;
    background-color: #144f9f;
    border: none;
    border-radius: 4px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.sentech-login-button:hover {
    background-color: #0d3a73;
}

.text-danger {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
    font-size: 14px;
}

.form-label {
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
    color: #606266;
}

.form-group {
    margin-bottom: 20px;
}

.nav-pills {
    border-bottom: 1px solid #ebe8e8;
}

.nav-link {
    padding: 0.5rem 1rem;
    font-size: 15px;
}

.el-input__inner {
    height: 40px;
    line-height: 40px;
}

.dialog-image-preview {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
}



.dialog-actions {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 20px;
}

.no-cover-placeholder {
    width: 100%;
    height: 180px;
    border: 2px dotted #ccc;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #aaa;
    background-color: #f9f9f9;
}


.action-button {
    cursor: pointer;
    text-align: center;
}

.action-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #144f9f;
    font-size: 14px;
    gap: 5px;
}

.action-icon .el-icon {
    font-size: 24px;
}

.action-icon.delete {
    color: #144f9f;
}



</style>

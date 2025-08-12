<template>
    <Head :title="'Profile'"><title>Profile</title></Head>

    <div class="page-wrapper">
        <!-- Profile Header Section -->
        <div class="profile-header-container">
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
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <div class="col-12 rounded py-4 shadow-border">
                <!-- ... [rest of your form code remains exactly the same] ... -->
            </div>
        </div>
    </div>
</template>

<script>
// ... [script section remains exactly the same] ...
</script>

<style scoped>
.page-wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    gap: 20px;
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
}

.cover-image {
    height: 200px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    background-size: cover;
    background-position: center;
    filter: brightness(0.85) sepia(0.3) hue-rotate(180deg) saturate(1.5);
}

.blue-profile-image {
    width: 150px;
    height: 150px;
    font-size: 60px;
    background-color: #144f9f !important; /* Matching form button color */
    color: #fff;
    border: 4px solid #ffffff;
}

.profile-picture-container {
    position: absolute;
    top: -75px;
    left: 20px;
    border-radius: 50%;
    background-color: #144f9f; /* Matching blue theme */
}

/* ... [rest of your CSS remains the same] ... */
</style>

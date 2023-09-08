<template>
    <div class="col-12">
        <p class="text-grey">Welcome Back,</p>
        <!-- {{ userdata[0].first_name }} -->
        <h1><strong>Dashboard</strong></h1>

        <div class="col-12 tweets-report-wrapper rounded mt-5">
            <div class="d-flex justify-content-between pr-4 py-4">
                <div class="col-3 total-tweets rounded py-4 tweet-box">
                    <div class="tweets-label">Total tweets</div>
                    <div class="tweets-value"><strong>321</strong></div>
                </div>
                <div class="col-3 total-engagement rounded py-4 tweet-box">
                    <div class="tweets-label">Engament</div>
                    <div class="tweets-value"><strong>48%</strong></div>
                </div>
                <div class="col-3 total-tweets2 rounded py-4 tweet-box">
                    <div class="tweets-label">Total tweets</div>
                    <div class="tweets-value"><strong>321</strong></div>
                </div>
                <div class="col-3 total-tweets3 rounded py-4 tweet-box">
                    <div class="tweets-label">Total tweets</div>
                    <div class="tweets-value"><strong>321</strong></div>
                </div>
            </div>
        </div>

        <!-- <div class="justify-content-center col-12 text-center">
            <div class="col-12 text-center pt-5 mt-5">
                <div
                    class="sidebar-profile-image initials-background mx-1 shadow"
                >
                    <i class="fa-solid fa-landmark"></i>
                </div>
            </div>
            <div class="sidebar-profile-name text-center pt-3">
                <h1 class="sidebar-name">
                    {{ userdata[1] }}
                </h1>
                <div class="sidebar-designation">
                    <h3 v-for="(user, index) in userdata" :key="index">
                        {{ getRoleNames(user.roles) }}
                    </h3>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

import { defineComponent, onMounted, ref } from "vue";

export default defineComponent({
    name: "dashboard",
    layout: AdminLayout,
    setup() {
        const userdata = ref([]);
        const company_type = ref([]);

        const getuser = async () => {
            const response = await axios.get("/user");
            userdata.value = response.data;
            company_type.value = userdata.value;
            console.log(userdata.value);
        };

        const getRoleNames = (roles) => {
            if (Array.isArray(roles)) {
                return roles.map((role) => role.name).join(", ");
            } else {
                return "";
            }
        };

        onMounted(() => {
            getuser();
        });

        return {
            getuser,
            userdata,
            getRoleNames,
            company_type,
        };
    },
});
</script>
<style scoped>
.initials-background {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    font-weight: 600;
    width: 100px;
    height: 100px;
    background-color: #144f9f;
    color: #fff;
    border-radius: 100%;
    margin-left: 20px;
}

.sidebar-name {
    color: #144f9f !important;
}

.grey-text {
    color: #737272;
}

.tweets-report-wrapper {
    background-color: #eeeeee;
    color: #fff;
}

.total-tweets {
    background-color: #f7a623;
}

.total-tweets2 {
    background-color: #209cbe;
}

.total-tweets3 {
    background-color: #c51616;
}

.total-engagement {
    background-color: #93ad24;
}

.tweets-value {
    font-size: 40px;
}
.tweets-label {
    font-size: 20px;
}
.tweet-box {
    margin-right: 8px; /* Add margin-right to create space between divs */
}
</style>

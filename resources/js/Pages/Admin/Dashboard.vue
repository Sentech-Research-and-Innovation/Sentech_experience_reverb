<template>
    <div class="col-12 px-lg-5">
        <p class="text-grey" v-if="userdata[0]">
            Welcome Back, {{ userdata[0].first_name }}
        </p>

        <h1><strong> Your Dashboard</strong></h1>

        <div class="col-12 tweets-report-wrapper rounded mt-3 mx-0 px-0">
            <div class="row">
                <div class="col-lg-3 col-6 pr-0">
                    <div class="col-12 total-tweets rounded py-4 tweet-box">
                        <div class="tweets-label">Total tweets</div>
                        <div class="tweets-value"><strong>321</strong></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pr-lg-3">
                    <div class="col-12 total-engagement rounded py-4 tweet-box">
                        <div class="tweets-label">Engagement</div>
                        <div class="tweets-value"><strong>48%</strong></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pr-0 pt-lg-0 pt-3 pl-lg-0">
                    <div class="col-12 total-tweets2 rounded py-4 tweet-box">
                        <div class="tweets-label">Total tweets</div>
                        <div class="tweets-value"><strong>321</strong></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pt-3 pt-lg-0">
                    <div class="col-12 total-tweets3 rounded py-4 tweet-box">
                        <div class="tweets-label">Total tweets</div>
                        <div class="tweets-value"><strong>321</strong></div>
                    </div>
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
</style>

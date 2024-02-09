<template>
    <Head :title="'Dashboard'"><title>Dashboard</title></Head>

    <div class="col-12 px-lg-3">
        <p class="text-grey" v-if="userdata[0]">
            Welcome Back, {{ userdata[0].first_name }}
        </p>

        <h2><strong> Your Dashboard</strong></h2>

        <div class="col-12 tweets-report-wrapper rounded mt-3 mx-0 px-0">
            <div class="row">
                <div class="col-lg-3 col-6 pr-0">
                    <div class="col-12 pending-companies rounded py-4">
                        <div class="tweets-label">Pending companies</div>
                        <div class="tweets-value"><strong>321</strong></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pr-lg-3">
                    <div class="col-12 company-requests rounded py-4 tweet-box">
                        <div class="tweets-label">Company Requests</div>
                        <div class="tweets-value"><strong>20</strong></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pr-0 pt-lg-0 pt-3 pl-lg-0">
                    <div class="col-12 system-users rounded py-4 tweet-box">
                        <div class="tweets-label">System users</div>
                        <div class="tweets-value"><strong>321</strong></div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pt-3 pt-lg-0">
                    <div
                        class="col-12 customer-feedback rounded py-4 tweet-box"
                    >
                        <div class="tweets-label">Customer feedback</div>
                        <div class="tweets-value"><strong>321</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 px-0 mx-0 mt-4">
            <ActivityLog />
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
import { Head, Link } from "@inertiajs/inertia-vue3";
import ActivityLog from "./ActivityLog.vue";
//import SentimentsTimelineChart from "./Sentiments/Overview/sentimentsTimeline.vue";

export default defineComponent({
    name: "dashboard",
    layout: AdminLayout,

    components: { Head, ActivityLog },
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
    color: #fff;
}

.pending-companies {
    background-color: #f7a623;
}

.system-users {
    background-color: #209cbe;
}

.customer-feedback {
    background-color: #c51616;
}

.company-requests {
    background-color: #93ad24;
}

.tweets-value {
    font-size: 40px;
}
.tweets-label {
    font-size: 20px;
}
</style>

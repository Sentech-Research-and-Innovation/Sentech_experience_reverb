<template>

    <Head :title="'Dashboard'">
        <title>Dashboard</title>
    </Head>

    <div class="col-12 px-lg-3">
        <p class="text-grey" v-if="userdata[0]">
            Welcome Back, {{ userdata[0].first_name }}
        </p>
        <h2><strong>Your Dashboard</strong></h2>

        <!-- Show stats if user can read companies -->
        <div v-if="can('companies-read_approved')" class="col-12 tweets-report-wrapper rounded mt-3 mx-0 px-0">
            <div class="row">
                <div class="col-lg-3 col-6 pr-0">
                    <div class="col-12 pending-companies rounded py-4 pl-4">
                        <div class="tweets-label pt-4">Pending companies</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.pending_companies }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="col-12 company-requests rounded py-4 tweet-box pl-4">
                        <div class="tweets-label pt-4">Company Requests</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.company_requests }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pr-0 pt-lg-0 pt-3 pl-lg-0">
                    <div class="col-12 system-users rounded py-4 tweet-box pl-4">
                        <div class="tweets-label pt-4">System users</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.system_users }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pt-3 pt-lg-0">
                    <div class="col-12 customer-feedback rounded py-4 tweet-box pl-4">
                        <div class="tweets-label pt-4">Customer feedback</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.customer_feedback }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Otherwise show icons -->
        <div v-else class="col-12 icon-grid-wrapper rounded mt-3 mx-0 px-0">
            <div class="row text-center">
                <!-- Sentiment -->
                <div class="col-lg-3 col-6 mb-3">
                    <a href="/admin/sentiments/all" class="icon-button full-box icon-sentiment">
                        <Smile class="icon-img" />
                        <span class="icon-label">Sentiment</span>
                    </a>
                </div>

                <!-- Maintenance -->
                <div class="col-lg-3 col-6 mb-3">
                    <a href="/admin/predictive-maintenance/index" class="icon-button full-box icon-maintenance">
                        <Wrench class="icon-img" />
                        <span class="icon-label">Maintenance</span>
                    </a>
                </div>

                <!-- Roles -->
                <div class="col-lg-3 col-6 mb-3">
                    <a href="/admin/roles" class="icon-button full-box icon-roles">
                        <Lock class="icon-img" />
                        <span class="icon-label">Roles</span>
                    </a>
                </div>

                <!-- Users -->
                <div class="col-lg-3 col-6 mb-3">
                    <a href="/admin/getActiveUsers" class="icon-button full-box icon-users">
                        <Users class="icon-img" />
                        <span class="icon-label">Users</span>
                    </a>
                </div>

                <!-- Radio (still commented out) -->
                <!-- <div class="col-lg-3 col-6 mb-3">
          <a href="/admin/radio" class="icon-button full-box">
            <Radio class="icon-img" />
            <span class="icon-label">Radio</span>
          </a>
        </div> -->
            </div>
        </div>

        <!-- SenTalk -->
        <div class="px-0 mx-auto mt-4" style="width: 85%;">
            <senTalk />
        </div>
    </div>
</template>


<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head } from "@inertiajs/vue3";
import senTalk from "./senTalk.vue";
import { Home, Smile, Wrench, Lock, Users, Radio } from "lucide-vue-next";
import { ref, onMounted } from "vue";
import axios from "axios";

defineOptions({ layout: AdminLayout });

const props = defineProps({
    refresh: {
        type: String,
        required: true,
    },
});

const userdata = ref([]);
const company_type = ref([]);
const stats = ref({
    pending_companies: 0,
    company_requests: 0,
    system_users: 0,
    customer_feedback: 0,
});

// Fetch user info
const getuser = async () => {
    const response = await axios.get("/user");
    userdata.value = response.data;
    company_type.value = userdata.value;
};

// Fetch dashboard stats
const getDashboardStats = async () => {
    try {
        const response = await axios.get("/admin/dashboard/stats");
        stats.value = response.data;
    } catch (error) {
        console.error("Failed to fetch dashboard stats", error);
    }
};

onMounted(() => {
    if (props.refresh == true) {
        window.location.href = "/admin/dashboard";
    }
    getuser();
    getDashboardStats();
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

.icon-sentiment {
    background-color: #f7a623;
}

.icon-maintenance {
    background-color: #209cbe;
}


.icon-roles {
    background-color: #c51616;
}

.icon-users {
    background-color: #93ad24;
}

.tweets-value {
    font-size: 55px;
    font-weight: 700;
}

.tweets-label {
    font-size: 20px;
}

@media (max-width: 480px) {
    .tweets-label {
        font-size: 14px;
    }

    .tweets-value {
        font-size: 30px;
        font-weight: 500;
    }
}

.icon-button {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    /* background: #f9f9f9; */
    border-radius: 6px;
    padding: 20px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

.icon-button:hover {
    /* background: #144f9f; Sentech blue */
    transform: translateY(-5px);
}

.icon-img {
    width: 55px;
    height: 55px;
    stroke: #fff;
    /* default color */
    transition: all 0.3s ease-in-out;
}

.icon-button:hover .icon-img {
    stroke: #fff;
    /* change to white on hover */
}

.icon-label {
    margin-top: 10px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
}

.icon-button:hover .icon-label {
    color: #fff;
}

.full-box {
    width: 100%;
    height: 100%;
    padding: 40px 20px;
    /* more padding so it looks like stat boxes */
}
</style>

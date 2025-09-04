<template>
    <Head :title="'Dashboard'"><title>Dashboard</title></Head>

    <div class="col-12 px-lg-3">
        <p class="text-grey" v-if="userdata[0]">
            Welcome Back, {{ userdata[0].first_name }}
        </p>
        <h2><strong>Your Dashboard</strong></h2>

        <div class="col-12 tweets-report-wrapper rounded mt-3 mx-0 px-0">
            <div class="row">
                <div class="col-lg-3 col-6 pr-0">
                    <div class="col-12 pending-companies rounded py-4 pl-4">
                        <div class="tweets-label pt-4">Pending companies</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.pending_companies }}</strong> <!--  Dynamic -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="col-12 company-requests rounded py-4 tweet-box pl-4">
                        <div class="tweets-label pt-4">Company Requests</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.company_requests }}</strong> <!-- Dynamic -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pr-0 pt-lg-0 pt-3 pl-lg-0">
                    <div class="col-12 system-users rounded py-4 tweet-box pl-4">
                        <div class="tweets-label pt-4">System users</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.system_users }}</strong> <!-- Dynamic -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6 pt-3 pt-lg-0">
                    <div class="col-12 customer-feedback rounded py-4 tweet-box pl-4">
                        <div class="tweets-label pt-4">Customer feedback</div>
                        <div class="tweets-value pb-3 pt-3">
                            <strong>{{ stats.customer_feedback }}</strong> <!-- Dynamic -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-0 mx-auto mt-4" style="width: 85%;">
            <senTalk />
        </div>

    </div>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, onMounted, ref } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import senTalk from "./senTalk.vue";

export default defineComponent({
    name: "dashboard",
    layout: AdminLayout,

    components: { Head, senTalk },

    props: {
        refresh: {
            type: String,
            required: true,
        },
    },

    setup(props) {
        const { refresh } = props;

        const userdata = ref([]);
        const company_type = ref([]);
        const stats = ref({
            pending_companies: 0,
            company_requests: 0,
            system_users: 0,
            customer_feedback: 0,
        }); // Stats state

        // Fetch user info
        const getuser = async () => {
            const response = await axios.get("/user");
            userdata.value = response.data;
            company_type.value = userdata.value;
            console.log(userdata.value);
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

        // Get roles for user
        const getRoleNames = (roles) => {
            if (Array.isArray(roles)) {
                return roles.map((role) => role.name).join(", ");
            } else {
                return "";
            }
        };

        onMounted(() => {
            if (refresh == true) {
                window.location.href = "/admin/dashboard";
            }
            getuser();
            getDashboardStats(); // Call stats fetch on mount
        });

        return {
            getuser,
            getRoleNames,
            userdata,
            company_type,
            refresh,
            stats, 
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
</style>

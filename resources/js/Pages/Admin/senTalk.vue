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

        <!-- Icon Grid -->
        <div class="col-12 icon-grid-wrapper rounded mt-3 mx-0 px-0">
          <div class="row text-center">

            <div class="col-lg-2 col-4 mb-3">
              <a href="/admin/dashboard" class="icon-button">
                <Home class="icon-img" />
                <span class="icon-label">Home</span>
              </a>
            </div>

            <div class="col-lg-2 col-4 mb-3">
              <a href="/admin/sentiment" class="icon-button">
                <Smile class="icon-img" />
                <span class="icon-label">Sentiment</span>
              </a>
            </div>

            <div class="col-lg-2 col-4 mb-3">
              <a href="/admin/maintenance" class="icon-button">
                <Wrench class="icon-img" />
                <span class="icon-label">Maintenance</span>
              </a>
            </div>

            <div class="col-lg-2 col-4 mb-3">
              <a href="/admin/roles" class="icon-button">
                <Lock class="icon-img" />
                <span class="icon-label">Roles</span>
              </a>
            </div>

            <div class="col-lg-2 col-4 mb-3">
              <a href="/admin/users" class="icon-button">
                <Users class="icon-img" />
                <span class="icon-label">Users</span>
              </a>
            </div>

            <div class="col-lg-2 col-4 mb-3">
              <a href="/admin/radio" class="icon-button">
                <Radio class="icon-img" />
                <span class="icon-label">Radio</span>
              </a>
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
import * as LucideIcons from "lucide-vue-next";   // ✅ CHANGED HERE

export default defineComponent({
    name: "dashboard",
    layout: AdminLayout,

    components: { 
        Head, 
        senTalk,
        Home: LucideIcons.Home,   // ✅ CHANGED HERE
        Smile: LucideIcons.Smile,
        Wrench: LucideIcons.Wrench,
        Lock: LucideIcons.Lock,
        Users: LucideIcons.Users,
        Radio: LucideIcons.Radio
    },

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
        });

        const getuser = async () => {
            const response = await axios.get("/user");
            userdata.value = response.data;
            company_type.value = userdata.value;
            console.log("User data:", userdata.value);
        };

        const getDashboardStats = async () => {
            try {
                const response = await axios.get("/admin/dashboard/stats");
                stats.value = response.data;
            } catch (error) {
                console.error("Failed to fetch dashboard stats", error);
            }
        };

        const getRoleNames = (roles) => {
            return Array.isArray(roles)
                ? roles.map((role) => role.name).join(", ")
                : "";
        };

        onMounted(() => {
            if (refresh == true) {
                window.location.href = "/admin/dashboard";
            }
            getuser();
            getDashboardStats();
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
/* same CSS as before */
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
.sidebar-name { color: #144f9f !important; }
.grey-text { color: #737272; }
.tweets-report-wrapper { color: #fff; }
.pending-companies { background-color: #f7a623; }
.system-users { background-color: #209cbe; }
.customer-feedback { background-color: #c51616; }
.company-requests { background-color: #93ad24; }
.tweets-value { font-size: 55px; font-weight: 700; }
.tweets-label { font-size: 20px; }
@media (max-width: 480px) {
    .tweets-label { font-size: 14px; }
    .tweets-value { font-size: 30px; font-weight: 500; }
}
.icon-button {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; text-decoration: none;
  background: #f9f9f9; border-radius: 12px;
  padding: 20px; transition: all 0.3s ease-in-out;
  cursor: pointer;
}
.icon-button:hover {
  background: #144f9f;
  transform: translateY(-5px);
}
.icon-img {
  width: 50px; height: 50px;
  stroke: #144f9f;
  transition: all 0.3s ease-in-out;
}
.icon-button:hover .icon-img { stroke: #fff; }
.icon-label {
  margin-top: 10px; font-size: 16px;
  font-weight: 600; color: #333;
}
.icon-button:hover .icon-label { color: #fff; }
</style>

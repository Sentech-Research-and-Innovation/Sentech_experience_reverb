<template>
    <div class="col-12">
        <h1>Dashboard KPIs</h1>

        <div class="justify-content-center col-12 text-center">
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
        </div>
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
</style>

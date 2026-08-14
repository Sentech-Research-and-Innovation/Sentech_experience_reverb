<template>
    <div class="col-12 shadow-s pt-3 activity-bg">
        <div class="col-12 mb-4">
            <div class="row justify-content-between">
                <div class="col-xl-3 col-lg-3 col-12 col-md-3 px-0 pt-4">
                    <el-input
                        v-model="searchActivity"
                        class="w-100 search-input"
                        placeholder="Serach by name"
                        :prefix-icon="Search"
                    />
                </div>
                <div
                    class="col-xl-8 col-lg-8 col-md-8 col-12 text-end px-0 pt-4"
                >
                    <div class="row px-0">
                        <div class="col-8 text-xl-end col-">
                            <el-date-picker
                                v-model="inputdate"
                                type="daterange"
                                range-separator="To"
                                start-placeholder="Start date"
                                end-placeholder="End date"
                                size="medium"
                            />
                        </div>
                        <div class="col-2 text-end px-0">
                            <el-button
                                type="primary"
                                class="w-100 search-bottons mx-0"
                                plain
                                @click="reset"
                                >Reset</el-button
                            >
                        </div>
                        <div class="col-2 text-end">
                            <el-button
                                type="primary"
                                class="w-100 search-bottons"
                                @click="getActivities"
                                >Apply</el-button
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive-mobile">
            <table class="table">
                <tbody v-if="logs">
                    <tr v-for="(log, index) in logs" :key="index">
                        <td>
                            <div class="d-flex py-2">
                                <div
                                    class="initials-background mt-0"
                                    ref="buttonRef"
                                    style="
                                        padding: 0px;
                                        width: 30px;
                                        height: 30px;
                                        margin-right: 15px;
                                        font-size: 16px;
                                    "
                                >
                                    {{
                                        log.user.first_name
                                            .charAt(0)
                                            .toUpperCase()
                                    }}
                                </div>
                                <div class="pt-2">
                                    <div>
                                        <span
                                            class="fs-6"
                                            style="font-weight: 400"
                                        >
                                            {{ log.user.first_name }}
                                            {{ log.user.last_name }} |
                                        </span>

                                        <span
                                            class="font-weight-light text-grey"
                                        >
                                            {{ log.user.roles[0].name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="font-weight-light text-grey">
                            {{ log.date }}
                        </td>
                        <td class="font-weight-light text-grey">
                            {{ log.time }}
                        </td>
                        <td class="font-weight-light text-grey">
                            {{ log.message }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <nav
                class="pt-3 mb-0"
                v-if="paginationLinks && paginationLinks.length > 3"
            >
                <ul class="pagination justify-content-center">
                    <li
                        class="page-item"
                        v-for="(link, p) in paginationLinks"
                        :key="p"
                    >
                        <a
                            class="page-link"
                            style="cursor: pointer"
                            :class="{ activePage: link.active }"
                            @click="getPagination(link.label)"
                            v-html="link.label"
                        ></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { Search } from "@element-plus/icons-vue";

export default defineComponent({
    name: "list-roles",

    components: {
        Head,
        Link,
    },

    setup() {
        const logs = ref([]);
        const paginationLinks = ref([]);

        const searchActivity = ref("");
        const inputdate = ref([]);

        const getActivities = async () => {
            logs.value = [];
            paginationLinks.value = [];
            const response = await axios.post("/admin/activities", {
                searchActivity: searchActivity.value,
                inputdate: inputdate.value,
            });
            logs.value = response.data.data;
            paginationLinks.value = response.data.links;
        };

        onMounted(async () => {
            await getActivities();
        });
        const getPagination = async (page) => {
            axios
                .post(`/admin/activities?page=${page}`, {
                    searchActivity: searchActivity.value,
                    inputdate: inputdate.value,
                })
                .then((res) => {
                    logs.value = res.data.data;
                    paginationLinks.value = res.data.links;
                });
        };

        const reset = () => {
            searchActivity.value = "";
            inputdate.value = [];
            getActivities();
        };
        return {
            logs,
            paginationLinks,
            Search,
            searchActivity,
            inputdate,
            getPagination,
            getActivities,
            reset,
        };
    },
});
</script>
<style>
.table td {
    padding: 5px 0px 0px 10px !important;
}
.activePage {
    background-color: #144f9f;
    color: #ffff !important;
}
.activity-bg {
    background-color: #ffff;
}
.search-bottons {
    background-color: #144f9f;
    color: #ffff !important;
    border: none;
    border-radius: 8px !important;
    height: 40px !important;
}
.activity-bg .el-input {
    height: 40px !important;
}

.activity-bg .el-date-editor {
    height: 40px !important;
    width: 100% !important;
}

.table-responsive-mobile {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
}

.table {
    width: 100%;
    min-width: 600px; /* Ensures table doesn't shrink too much */
}
</style>

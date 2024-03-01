<template>
    <div class="col-12 shadow-s pt-3 activity-bg">
        <div class="col-12 mb-4 mt-4">
            <div class="d-flex justify-content-between">
                <div class="col-3 px-0">
                    <el-input
                        v-model="searchActivity"
                        class="w-100"
                        placeholder="Serach by name"
                        :prefix-icon="Search"
                    />
                </div>
                <div class="col-8 text-end pr-0">
                    <div class="row">
                        <div class="col-8 text-end">
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
                                class="w-100 search-bottons-clear"
                                style="border-radius: 8px !important"
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
        <table class="table">
            <tbody v-if="logs">
                <tr v-for="(log, index) in logs" :key="index">
                    <td>
                        <div class="d-flex">
                            <div
                                class="initials-background mt-1"
                                ref="buttonRef"
                                style="
                                    padding: 0px;
                                    cursor: pointer;
                                    width: 30px;
                                    height: 30px;
                                    margin-right: 15px;
                                    font-size: 12px;
                                "
                            >
                                {{
                                    log.user.first_name.charAt(0).toUpperCase()
                                }}
                                {{ log.user.last_name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="pt-1">
                                <div class="fs-6 font-weight-bold">
                                    {{ log.user.first_name }}
                                    {{ log.user.last_name }}
                                </div>

                                <div>
                                    <p class="font-weight-light text-grey">
                                        {{ log.user.roles[0].name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="font-weight-light text-grey">
                        {{ log.IP_ADDRESS }}
                    </td>
                    <td class="font-weight-light text-grey">{{ log.date }}</td>
                    <td class="font-weight-light text-grey">{{ log.time }}</td>

                    <td class="font-weight-light text-grey">
                        {{ log.message }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12 py-4 activity-bg">
        <nav
            class=""
            aria-label="Page navigation example"
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
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
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
}
</style>

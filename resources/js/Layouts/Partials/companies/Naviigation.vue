<template>
    <div class="company-nav-header col-12 px-0">
        <nav
            class="navbar navbar-expand-lg navbar-light py-0 mb-0 justify-content-between"
        >
            <ul class="navbar-nav mr-auto pt-1">
                <li
                    class="nav-item mr-0"
                    :class="{
                        active: $page.url === '/organizantions/approved',
                    }"
                >
                    <Link class="nav-link" href="/organizantions/approved"
                        >Approved</Link
                    >
                </li>

                <li
                    class="nav-item mx-0"
                    :class="{
                        active: $page.url === '/organizantions/pending',
                    }"
                >
                    <Link class="nav-link" href="/organizantions/pending"
                        >Pending
                    </Link>
                </li>
                <li
                    class="nav-item mr-5"
                    :class="{
                        active: $page.url === '/organizantions/request',
                    }"
                >
                    <Link class="nav-link" href="/organizantions/request"
                        >Requests</Link
                    >
                </li>
            </ul>
            <ul>
                <!-- <div class="col-12 mt-4 d-flex px-0 mx-0">
                    <el-input
                        class="w-100 mr-2"
                        placeholder="Serach by name"
                        :prefix-icon="Search"
                    />
                    <el-button type="primary" class="w-30">Go</el-button>
                </div> -->
            </ul>
        </nav>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { Search } from "@element-plus/icons-vue";

export default defineComponent({
    components: { Link, Head },

    setup() {
        const active = ref(true);

        return {
            Search,
            active,
        };
    },
});
</script>

<style>
.form-control-error {
    border-radius: 1px solid #ff1744 !important;
}

.company-nav-header .active {
    border-bottom: 3px solid #144f9f !important;
}

.company-nav-header .nav-link {
    color: #dcdfe6 !important;
    font-size: 17px;
}

.company-nav-header .active .nav-link {
    color: #144f9f !important;
}
</style>

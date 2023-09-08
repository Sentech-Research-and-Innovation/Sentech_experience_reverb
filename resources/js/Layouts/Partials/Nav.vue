<script>
import { Link } from "@inertiajs/vue3";
import Login from "../../Pages/Auth/Login.vue";

export default {
    props: ["showing"],
    components: {
        Link,
        Login,
    },
    data: function () {
        return {};
    },
    methods: {
        showingModal(value) {
            this.showing = value;
            // this.$emit('modal', value);
        },
    },
    mounted() {},
};
</script>
<style scoped lang="scss">
.navbar {
    background: #144f9f;
    height: 100px;
    border-bottom: solid 1px #fff;
    .nav-link {
        color: #fff;

        .fa.fa-user {
            padding-right: 10px;
        }
    }

    .request-an-account-button {
        padding: 10px;
        border: solid 1px #fff;
        border-radius: 12px;
    }
}
</style>
<template>
    <nav class="navbar navbar-expand">
        <div class="container">
            <a class="navbar-brand pt-4" href="#"
                ><img src="../../assets/white-logo.png" width="200"
            /></a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#sentech-nav"
                aria-controls="navbarNavAltMarkup"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="sentech-nav">
                <div class="navbar-nav ms-auto">
                    <Login />
                    <Link
                        class="register nav-link"
                        aria-current="page"
                        href="/login"
                        ><span class="request-an-account-button"
                            >Request and Account</span
                        >
                    </Link>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
@media screen and (max-width: 600px) {
    .register {
        visibility: hidden;
        clear: both;
        float: left;
        margin: 10px auto 5px 20px;
        width: 28%;
        display: none;
    }
}
</style>

<template>
    <aside id="sidebarMenu" class="sidebar">
        <div class="cm-logo">
            <img src="/images/credit-mate-logo.svg" alt="CreditMate Logo">
        </div>

        <div class="dashboard-main">
            <h4>Dashboard</h4>
            <div class="dash-role">Super admin / {{$page.props.user_roles}}</div>
        </div>

        <div class="sidebar-links">
            <Link class="nav-link " aria-current="page" href="/dashboard"
                  :class="{ 'active': $page.url === '/dashboard' }">
                Dashboard home
            </Link>

            <Link class="nav-link " aria-current="page" href="/companies" :class="{ 'active': $page.url === '/companies' }">
                Companies
            </Link>
            <Link class="nav-link" href="/dashboard"
                  v-if="$page.props.user_roles === 'admin' || $page.props.user_roles === 'owner'">
                Referrals
            </Link>

            <Link class="nav-link" href="/users"  :class="{ 'active': $page.url === '/users' ||  $page.url === '/user'}">
                Users
            </Link>

            <Link class="nav-link" href="/roles" :class="{ 'active': $page.url === '/roles'}">
                Roles
            </Link>
            <Link class="nav-link" href="/system-settings" :class="{ 'active': $page.url === '/system-settings'}">
                System settings
            </Link>

<!--            <Link class="nav-link" href="/permissions" :class="{ 'active': $page.url === '/permissions'}">-->
<!--                Permission-->
<!--            </Link>-->
        </div>

    </aside>

</template>

<script>
    import {Link} from '@inertiajs/vue3';
    import arrow from '../../../../public/svg/arrow.svg';

    export default {
        components: {Link, arrow},
        mounted() {

        }
    }

</script>

<style lang="scss" scoped>
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 385px;
        min-height: 100vh;
        background-color: #F7F7F7;
        padding: 50px;
        // NEST
        .cm-logo {
            font-size: 30px;
            margin-bottom: 35px;
            // NEST
            span {
                display: block;
                font-weight: 600;
                line-height: 1.2;

                &:first-child {
                    font-size: 18px;
                }
            }
        }

        .dashboard-main {
            margin-bottom: 35px;
            // NEST
            h4 {
                font-size: 36px;
                font-weight: 600;
                margin-top: 0;
                margin-bottom: 25px;
            }

            .dash-role {
                padding: 10px 15px;
                background-color: #FA501E;
                border-radius: 35px;
                color: #fff;
                text-align: center;
                font-weight: 600;
            }
        }

        .sidebar-links {
            // NEST
            a {
                font-weight: 600;
                display: block;
                padding: 10px 0;
                border-bottom: 1px solid #707070;

                &.active {
                    color: #FA501E;
                }
            }
        }
    }
</style>

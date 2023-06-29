<template>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Sentech Total User Experience Platform</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle login-name" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $page.props.auth.user.name }} <span class="initials-background"><strong>{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</strong></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><Link href="/dashboard" method="post" as="link" class="nav-link px-3 ">Dashboard</Link></li>
                            <li><Link href="/profile" method="post" as="link" class="nav-link px-3 ">Profile</Link></li>
                            <li><Link href="/help" method="post" as="link" class="nav-link px-3 ">Help</Link></li>
                            <li><Link href="/logout" method="post" as="link" class="nav-link px-3 ">Logout</Link></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import arrow from '../../../../public/svg/arrow.svg';

export default {
    components: { Link, arrow },
    mounted() {

    }
}

</script>

<style lang="scss" scoped>

    .nav-link:hover, .nav-link:focus {
        color: #144f9f;
    }
    .initials-background{
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        width: 35px;
        height: 35px;
        background-color: #144f9f;
        color: #fff;
        border-radius: 100%;
    }

</style>

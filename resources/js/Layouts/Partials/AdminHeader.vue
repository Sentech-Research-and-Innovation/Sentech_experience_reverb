<template>
    <div class="admin-header">
        <nav class="navbar navbar-expand-lg mb-5 admin-nav">
          <div class="container">

            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mr-auto admin-dropdown" id="navbarNavDropdown">
              <ul class="navbar-nav ms-auto">

                <li class="nav-item dropdown">
<!--                    {{$page.props}}-->
                  <a class="nav-link dropdown-toggle login-name" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $page.props.auth.user.name }} <span class="firstChar"><strong>{{ $page.props.auth.user.name.charAt(0).toUpperCase() }}</strong></span>
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
        </nav>
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
  .admin-header{
    position: fixed;
    top: 0;
    right: 0;
    z-index: 99;
    width: calc(100% - 385px);
    // NEST
    .firstChar{
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        width: 35px;
        height: 35px;
        background-color: #982BAC;
        color: #fff;
        border-radius: 100%;
    }
  }
</style>

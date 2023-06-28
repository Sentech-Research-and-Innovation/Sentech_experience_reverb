<template>
   <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav float-end">
                    <li class="nav-item">
                        <Link href="/login" method="get" as="link" >Login</Link>
                    </li>
                    <li class="nav-item">
                        <Link href="/register" method="get" as="link">Register</Link>                        
                    </li>
                    <li class="nav-item" >                        
                        <Link href="/logout" method="post" as="link">Logout</Link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Company name</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
        <div class="navbar-nav">
            <div class="nav-item text-nowrap" v-if="$page.props.auth">
            <Link href="/logout" method="post" as="link" class="nav-link px-3">Sign out</Link>
            </div>
    </div>
</header> -->
</template>

<script>
import { Link } from '@inertiajs/vue3'
export default {
    components:{
        Link
    },

    mounted(){
       
    }
}
</script>

<style>

</style>


<template>
    <Component 
        v-for="link in links" 
        :is="link.url ? 'Link' : 'span'" 
        :href=" link.url" 
        v-html="link.label" 
        class="paginator"             
        :class="{'text-paginate': ! link.url, 'active-page': link.active}"
    />
    <div class="text-gray float-end ml-2"><strong><small>From</small></strong> {{ data.from }} <strong><small>to</small></strong> {{ data.to }}</div>
    <div class="text-gray float-end ml-2"><strong><small>Page</small></strong> {{ data.current_page }} <strong><small>of</small></strong> {{ data.last_page }}</div>                                          
                            
</template>

<script>
import {Link} from '@inertiajs/vue3';
export default{
props:['links' , 'data'],
components:{
    Link,
   
},
mounted(){
     console.log(this.links);
}
   
}
</script>
<style>
.paginator{
    margin-left: 1rem;
}
  .text-paginate{
        color: #9e9090;
    }

    .active-page{
        background-color: #FA501E;
        padding: 1rem;
        color: #fff;
        text-align: center;
        font-weight: 600;
    }
</style>
<template>
    <div class="wrapper" >
        <div v-if="isAuthenticated">

            <!-- <Preloader/> -->
            <Navbar/>
            <Sidebar/>
            <!-- content -->
            <div class="content-wrapper">
                <router-view></router-view>
            </div>
            <Footer/>
        </div>

        <div v-else>
            <Login/>
        </div>
    </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue'
import Navbar from './components/Navbar.vue'
import Preloader from './components/Preloader.vue'
import Footer from './components/Footer.vue'
import Login from '../components/Login.vue'
import {mapGetters, mapActions} from 'vuex'

export default {
    components: {
        Sidebar,
        Navbar,
        Preloader,
        Footer,
        Login
    },
    computed: mapGetters(['isAuthenticated']),
    created() {
        this.athenticated()
        
    },
    methods: {
        ...mapActions(['athenticated']),
    }
}
</script>


<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .item-product:hover{
    background: rgb(243, 241, 241);
  }
</style>
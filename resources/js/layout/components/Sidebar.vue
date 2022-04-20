<template>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <router-link to="/" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </router-link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{isUser.name}}</a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <ul class="nav nav-treeview">
              <li class="nav-item" @click="changeActiveClick('category')">
                <router-link to="/category" class="nav-link" v-bind:class="{ active: activeCategory }">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category</p>
                </router-link>
              </li>
              <li class="nav-item" @click="changeActiveClick('product')">
                <router-link to="/category/product" class="nav-link" v-bind:class="{ active: activeProduct }"> 
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product</p>
                </router-link>
              </li>
            </ul>
          </li>
        
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
import {mapActions, mapGetters} from 'vuex'
export default {
    data() {
      return {
        activeCategory: false,
        activeProduct: false
      }
    },
    computed: {...mapGetters(['isUser'])},
    methods: {
      ...mapActions(['getUser']),
      changeActiveClick(nameActive) {
        if(nameActive == 'category'){
          this.activeCategory = true
          this.activeProduct = false
        }
        if(nameActive == 'product'){
          this.activeCategory = false
          this.activeProduct = true
        }
      }
    },
    created(){
      this.getUser();
    }
}
</script>
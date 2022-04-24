<template>
  <div class="login-box">
    <div class="login-logo">
      <a href="">
        <b>Admin</b>LTE
      </a>  
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form @submit="onSubmit">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" v-model="form.email"/>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" v-model="form.password"/>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    data(){
        return{
            form:{
                email: '',
                password: ''
            }
        }
    },
    methods: {
      ...mapActions(['login']), 
      onSubmit(event) {
        event.preventDefault()
        this.login(this.form)
      }
    }
}
</script>


<style scoped>
    .login-box{
        margin: 35px auto;
    }
</style>

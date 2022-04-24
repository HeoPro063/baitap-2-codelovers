require('./bootstrap');
import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router';
import {routes} from './routes'
import store from './store'
import VueValidator from 'vuelidate'


Vue.use(VueValidator)
Vue.use(VueRouter)
const router = new VueRouter({
    mode: 'history',
    routes
});




new Vue({
    render: h => h(App) ,
    router,
    store,
}).$mount('#app')



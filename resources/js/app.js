require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Toasted from "vue-toasted";

Vue.use(Toasted, {
    position: "bottom-center",
    duration: 5000,
    type: "info"
}); 

import VueApexCharts from 'vue-apexcharts'
Vue.component('apexchart', VueApexCharts)

import VueSocketIO from 'vue-socket.io'
Vue.use(new VueSocketIO({
    debug: true,
    connection: 'http://178.62.59.40:8080'
}))

import { BPagination } from 'bootstrap-vue'
Vue.component('b-pagination', BPagination)

import store from './stores/global-store'

import Home from './components/home'
import User from './components/users'
//import UserEdit from './components/userEdit'
import UserRegister from './components/userRegister'
import OpAdminRegister from './components/OpAdminRegister'
import UserProfile from './components/userProfile'
import UserStatistics from './components/userStatistics'
import Login from './components/login'
import Logout from './components/logout'
import Movimento from './components/movements/movements'
import MovementStatistics from './components/movements/movementsStatistics'


const home = Vue.component("home", Home);
const user = Vue.component("users", User);
//const userEdit = Vue.component("usersEdit", UserEdit);
const userRegister = Vue.component("usersRegister", UserRegister);
const opAdminRegister = Vue.component("opAdminRegister", OpAdminRegister);
const userProfile = Vue.component("userProfile", UserProfile);
const userStatistics = Vue.component("userStatistics", UserStatistics);
const login = Vue.component("login", Login);
const logout = Vue.component("logout", Logout);
const movementsStatistics = Vue.component("movementsStatistics", MovementStatistics);


const routes = [
    { path: "/", component: Home},
    { path: "/users", component: User, name: "users" },
    //{ path: "/users/:id/edit", component: UserEdit, name: "usersEdit" }, 
    { path: "/users/newAccount", component: UserRegister, name: "usersRegister" },
    { path: "/users/OperadorAdmin", component: OpAdminRegister, name: "opAdminRegister" },
    { path: "/users/profile", component: UserProfile, name: "userProfile" },
    { path: "/users/statistics", component: UserStatistics, name: "userStatistics" },
    { path: "/login", component: Login, name: "login" },
    { path: "/logout", component: Logout, name: "logout" },
    { path: "/movements", component: Movimento, name: "movements" },
    { path: "/movements/statistics", component: MovementStatistics, name: "movementsStatistics" }

]

const router = new VueRouter({
    routes //equivale a routes:routes
})

const app = new Vue({
    el: '#app',
    router,
    store,
    created() {
        //console.log("-----");
        //console.log(this.$store.state.user);
        //this.$store.commit("loadDepartments");
        this.$store.commit("loadTokenAndUserFromSession");
        //console.log(this.$store.state.user);
        if(this.$store.state.user){
            this.$socket.emit('login', this.$store.state.user); //é necessário fazer isto aqui pois no caso de o utilizador dar um refresh à página, receber o valor do user e do socket e não um socket vazio
        }
    },
    sockets:{
        updateMovements(data){ 
            //console.log(data)
            //console.log(data.user.email)
            //console.log(data.aux)
            if(data.aux == 0){
                this.$toasted.show("You received an movement!");
            }else{
                //this.$toasted.show("Falhou!");
                axios.put('api/movements/email/' + data.user.email);
            }
        },

        updateValorMaximo(data){ 
            if(data.aux == 0){
                this.$toasted.show("Movement > 3000€ !");
            }
        },

        updateIncome(data){ 
            //console.log(data.user.emailIncome)
            //console.log(data.aux)
            if(data.aux == 0){
                this.$toasted.show("You received an income movement!");
            }else{
                //this.$toasted.show("Falhou!");
                axios.put('api/movements/email/' + data.user.emailIncome);
            }
        }            
    }
});

router.beforeEach((to, from, next) => {
    if (to.name == "logout" ) {
        if (!app.$store.state.user) {
            next("/login");
            return;
        }
    } 
    if(to.name == "users" ){
        if (!app.$store.state.user) {
            next("/login");
            return;
        }
    } 
    if(to.name == "users" ){
        if (app.$store.state.user.type != 'a') {
            next("/");
            return;
        }
    } 
    if(to.name == "usersEdit" ){
        if (!app.$store.state.user) {
            next("/login");
            return;
        }
    } 
    if(to.name == "movements" ){     
        if (!app.$store.state.user) {
            next("/login");
            //console.log(app.$store.state.user)
            return;
        }
    }
    if(to.name == "movementsStatistics" ){     
        if (!app.$store.state.user) {
            next("/login");
            //console.log(app.$store.state.user)
            return;
        }
    }
    if(to.name == "movementsStatistics" ){     
        if (app.$store.state.user.type != 'u') {
            next("/");
            return;
        }
    }
    if(to.name == "userProfile" ){
        if (!app.$store.state.user) {
            next("/login");
            return;
        }
    }
    if(to.name == "userStatistics" ){
        if (!app.$store.state.user) {
            next("/login");
            return;
        }
    }
    if(to.name == "opAdminRegister" ){
        if (!app.$store.state.user) {
            next("/login");
            return;
        }
    }
    if(to.name == "opAdminRegister" ){
        if (app.$store.state.user.type != 'a') {
            next("/");
            return;
        }
    }
    if(to.name == "login" ){
        if (app.$store.state.user) {
            next("/");
            return;
        }
    }

    next();
});



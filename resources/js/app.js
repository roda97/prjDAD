require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Toasted from "vue-toasted";

Vue.use(Toasted, {
    position: "bottom-center",
    duration: 5000,
    type: "info"
}); //VER O QUE FAZ ESTE Toasted

import { BPagination } from 'bootstrap-vue'
Vue.component('b-pagination', BPagination)

import store from './stores/global-store'

import Home from './components/home'
import User from './components/users'
import Login from './components/login'
import Logout from './components/logout'
import Movimento from './components/movements/movements'
import Wallet from './components/wallets'


const home = Vue.component("home", Home);
const user = Vue.component("users", User);
const login = Vue.component("login", Login);
const logout = Vue.component("logout", Logout);


const routes = [
    { path: "/", component: Home},
    { path: "/users", component: User, name: "users" },
    { path: "/login", component: Login, name: "login" },
    { path: "/logout", component: Logout, name: "logout" },
    { path: "/movements", component: Movimento, name: "movements" },
    { path: "/wallets", component: Wallet, name: "wallets"}


]

const router = new VueRouter({
    routes //equivale a routes:routes
})

router.beforeEach((to, from, next) => {
    if (to.name == "logout" ) {
        if (!store.state.user) {
            next("/login");
            return;
        }
    } else if((to.name == "users" )){
        if (!store.state.user) {
            next("/login");
            return;
        }
    } else if((to.name == "movements" )){
        if (!store.state.user) {
            next("/login");
            return;
        }
    }
    else if((to.name == "wallets" )){
        if (!store.state.user) {
            next("/login");
            return;
        }
    }
    /*else if((to.name == "login" )){
        if (store.state.user) {
            next("/");
            return;
        }
    }*/

    next();
});

const app = new Vue({
    el: '#app',
    router,
    store,
    created() {
        console.log("-----");
        console.log(this.$store.state.user);
        //this.$store.commit("loadDepartments");
        this.$store.commit("loadTokenAndUserFromSession");
        console.log(this.$store.state.user);
    }
});

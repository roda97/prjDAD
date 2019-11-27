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

import store from './stores/global-store'

import Home from './components/home'
import User from './components/users'
import Login from './components/login'
import Logout from './components/logout'

const home = Vue.component("home", Home);
const user = Vue.component("users", User);
const login = Vue.component("login", Login);
const logout = Vue.component("logout", Logout);

const routes = [
    { path: "/", component: Home, name: "home" },
    { path: "/users", component: User },
    { path: "/login", component: Login, name: "login" },
    { path: "/logout", component: Logout, name: "logout" }

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
    }
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

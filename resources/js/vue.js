require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter)

/*import Toasted from "vue-toasted";

Vue.use(Toasted, {
    position: "bottom-center",
    duration: 5000,
    type: "info"
});*/ //VER O QUE FAZ ESTE Toasted

import store from './stores/global-store'

import User from './components/user'
import Login from './components/login'
import Logout from './components/logout'

const user = Vue.component("user", User);
const login = Vue.component("login", Login);
const logout = Vue.component("logout", Logout);

const routes = [
    { path: "/", redirect: "/users" },
    { path: "/users", component: user },
    { path: "/login", component: login, name: "login" },
    { path: "/logout", component: logout, name: "logout" }

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

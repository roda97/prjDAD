require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Login from './components/login'

const routes = [
    {path:'/login', component:Login},
]

const router = new VueRouter({
    routes //equivale a routes:routes
})

const app = new Vue({
    el: '#app',
    router,
    data: {
    },
    methods: {
    },
    mounted() {
      
    }
});

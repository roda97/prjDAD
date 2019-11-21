<template>
<div>
    <div class="jumbotron">
      <h1 align="center">{{title}}</h1>
    </div>
  <div class = "container">
      <div class="signup-form">
        <div class="alert" :class="typeofmsg" v-if="showMessage">
            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
            <strong>{{ message }}</strong>
        </div>


          <div class="form-group">
      			<div class="input-group">
      				<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
      				<input type="type" class="form-control" name="text" placeholder="Email Address/Username" required="required" v-model="user.email">
      			</div>
          </div>
    		<div class="form-group">
    			<div class="input-group">
    				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
    				<input type="password" class="form-control" name="password" placeholder="Password" required="required" v-model="user.password">
    			</div>
        </div>
    	<div class="text-center">
        <a class="btn btn-primary btn-lg" v-on:click.prevent="login">Login</a>
      </div>
    </div>
  </div>
</div>    
</template>

<script>
export default {
    data: function(){
        return{
            user: {
                email:"",
                password:""
            },
            typeofmsg: "alert-success",
            showMessage: false,
            message: "",
            title:"Login"
        }
    },

    methods:{
        login() {
            this.showMessage = false;
            axios.post('api/login')//, this.user)
                .then(response => {
                    console.log(response)
                    //this.$store.commit('setToken',response.data.access_token);
                    //return axios.get('api/users');
                })/*
                .then(response => {
                    this.$store.commit('setUser',response.data.data);
                    this.typeofmsg = "alert-success";
                    this.message = "User authenticated correctly";
                    this.showMessage = true;

                    //Escutar tipos de mensagens

                   this.$socket.emit('user_enter', response.data.data);
                   // this.$socket.emit('user_enter_type', response.data.data);


                })*/
                .catch(error => {
                    //this.$store.commit('clearUserAndToken');
                    //this.typeofmsg = "alert-danger";
                    //this.message = "Invalid credentials";
                    //this.showMessage = true;
                    console.log(error);
                })
        }
    /*},
    state: { 
        token: "",
        user: null, 
        //departments: []       
    },  
    mutations: { 
        clearUserAndToken: (state) => {
            state.user = null;
            state.token = "";
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        clearUser: (state) => {
            state.user = null;
            sessionStorage.removeItem('user');
        },
        clearToken: (state) => {
            state.token = "";
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        setUser: (state, user) => {
            state.user =  user;
            sessionStorage.setItem('user', JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.token= token;
            sessionStorage.setItem('token', token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession: (state) => {
            state.token = "";
            state.user = null;
            let token = sessionStorage.getItem('token');
            let user = sessionStorage.getItem('user');
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = "Bearer " + token;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        },*/
        /*loadDepartments: (state) => {
            axios.get('api/departments')
                    .then(response => {
                        state.departments = response.data.data; 
                    });
        }*/
    } 
}
</script>
<style>
</style>
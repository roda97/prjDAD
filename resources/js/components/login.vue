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
        login: function() {
            //this.showMessage = false;
            /*axios.post('api/login')//, this.user)
                .then(response => {
                    console.log(response)
                    //return axios.get('api/users');
                })
                .catch(error => {
                    console.log(error);
                })*/
        }
    } 
}
</script>
<style>
</style>
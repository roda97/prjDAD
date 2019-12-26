<template>
    <div>
        <div class="alert" :class="typeofmsg" v-if="showMessage">
            <button
                type="button"
                class="close-btn"
                v-on:click="showMessage = false"
            >
                &times;
            </button>
            <strong>{{ message }}</strong>
        </div>
        <div class="jumbotron">
            <h2>Login</h2>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input
                    type="email"
                    class="form-control"
                    v-model.trim="user.email"
                    name="email"
                    id="inputEmail"
                    placeholder="Email address"
                />
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input
                    type="password"
                    class="form-control"
                    v-model="user.password"
                    name="password"
                    id="inputPassword"
                />
            </div>
            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="login">Login</a>
            </div>
        </div>
    </div> 
</template>

<script type="text/javascript">
export default {
    data: function() {
        return {
            user: {
                email: "",
                password: ""
            },
            typeofmsg: "alert-success",
            showMessage: false,
            message: ""
        };
    },
    methods: {
        login() {
            this.showMessage = false;
            axios
                .post("api/login", this.user)
                .then(response => {
                    this.$store.commit("setToken", response.data.access_token);
                    return axios.get("api/user"); //estava return axios.get("api/users"); e foi mudado para se ter o estado do user logado
                })
                .then(response => {
                    this.$store.commit("setUser", response.data);
                    //this.typeofmsg = "alert-success";
                    //this.message = "User authenticated correctly";
                    //this.showMessage = true;
                    console.log(response.data)
                    //console.log(response.data.data)
                    this.$router.push('/');
                    this.$toasted.show("User authenticated correctly");
                    this.$socket.emit('login', response.data);
                })
                .catch(error => {
                    this.$store.commit("clearUserAndToken");
                    this.typeofmsg = "alert-danger";
                    this.message = "Invalid credentials";
                    this.showMessage = true;
                    console.log(error);
                });
        }
    }
};
</script>
<style>
</style>
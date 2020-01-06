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
            <h2>Confirmar Logout</h2>
            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="logout"
                    >Logout</a
                >
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    data: function() {
        return {
            typeofmsg: "alert-success",
            showMessage: false,
            message: ""
        };
    },
    methods: {
        logout() {
            this.showMessage = false;
            this.$socket.emit('logout', this.$store.state.user); //tem de ser antes do axios porque eu quero saber quem o faz antes de fazer logout
            axios
                .post("api/logout")
                .then(response => {
                    this.$store.commit("clearUserAndToken");
                   // this.typeofmsg = "alert-success";
                    //this.message = "User has logged out correctly";
                   // this.showMessage = true;
                    this.$router.push('/');

                    this.$toasted.show("User logged out correctly");
                })
                .catch(error => {
                    this.$store.commit("clearUserAndToken");
                    this.typeofmsg = "alert-danger";
                    this.message =
                        "Logout incorrect. But local credentials were discarded";
                    this.showMessage = true;
                    //console.log(error);
                });
        }
    }
};
</script>

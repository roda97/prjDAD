<template>
  <div class="jumbotron">
    <h2>{{title}}</h2>

    
    <div class="form-group">
      <label for="inputName">Name</label>
      <input
        type="text"
        class="form-control"
        v-model="user.name"
        name="name"
        id="inputName"
        placeholder="Fullname"
      />
    </div>
    <div class="form-group">
      <label for="inputEmail">Email</label>
      <input
        type="email"
        class="form-control"
        v-model="user.email"
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
        placeholder="password"
      />
    </div>

    <div class="form-group">
      <label for="inputNif">Nif</label>
      <input
        type="number"
        class="form-control"
        v-model="user.nif"
        name="nif"
        id="inputNif"
        placeholder="NIF"
      />
    </div>

    <div class="form-group">
      <label for="inputPhoto">Photo</label>
      <input
        type="file"
        class="form-control"
        name="photo"
        id="inputPhoto"
        placeholder="upload Photo"
      />
    </div>

    
    <div class="form-group">
      <a class="btn btn-primary" v-on:click.prevent="registerUser()">Register</a>
      <a class="btn btn-danger" v-on:click.prevent="cancelRegister()">Cancel</a>

    </div>
    
 
  </div>
</template>


<script >
export default {
  data: function() {
    return {
      title: "Register",
      user: { name: "", email: "", password: "", nif: "", photo: "" },
      showSuccess: false,
      errors: [],
      successMessage: ""
    };
  },
  methods: {
      /*
     checkForm: function (e) {
      this.errors = [];

      if (!this.user.email) {
        this.errors.push('Email required.');
      } else if (!this.validEmail(this.user.email)) {
        this.errors.push('Valid email required.');
      }

      if (!this.errors.length) {
        return true;
      }

      e.preventDefault();
    },*/
    registerUser: function() {
      axios
        .post("api/users/register", this.user)
        .then(response => {
       
          Object.assign(this.user, response.data);
          this.$router.push('/');
          this.$toasted.show('User Created');

         
        })
        .catch((error) => {
          console.log(error);
        });
    },
    cancelRegister: function(){
      axios.get('api/users/total')
      .then(response => {
        this.$router.push('/');
      })
    },
    
    /*validEmail: function (email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }*/
  },

  mounted() {}
};
</script>
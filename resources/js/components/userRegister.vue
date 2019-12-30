<template>
  <div class="jumbotron">
    <h2>{{title}}</h2>

    <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert alert-danger" v-if="showError">
            <button type="button" class="close-btn" v-on:click="showError=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

    
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
      <div 
          class="invalid-feedback" v-if="badName"> Invalid name.
      </div>
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
      <div 
          class="invalid-feedback" v-if="badEmail"> Invalid email.
      </div>
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
      <div 
          class="invalid-feedback" v-if="badPassword"> Invalid password.
      </div>
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
      <div 
          class="invalid-feedback" v-if="badNif"> Invalid NIF.
      </div>
    </div>

    <div class="form-group">
      <label for="inputPhoto">{{ user.photo.name }}</label>
      <input
        type="file"
        class="form-control"
        name="photo"
        id="inputPhoto"
        placeholder="upload Photo"
        v-on:change="onImageChange"
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
      user: { name: "", email: "", password: "", nif: "", photo:{ name: "Insert Photo", base64: "",}, },
      showSuccess: false,
      showError: false,
      errors: [],
      successMessage: "",
      badName: false,
      badEmail: false,
      badPassword: false,
      badNif: false
    };
  },
  methods: {

    onImageChange: function(event){     //UPLOAD IMAGE METHODS
      let image = event.target.files[0];
      this.user.photo.name = image.name;
      this.createImage(image);
    },
    createImage: function(file){
      let reader = new FileReader();
      reader.onload = (e) => {
          this.user.photo.base64= e.target.result;
      };
      reader.readAsDataURL(file);
      
    },

    registerUser: function() {
      axios.post("api/users/register", this.user)
        .then(response => {
          Object.assign(this.user, response.data);
          this.$router.push('/');
          this.$toasted.show('User Created');

         
        })
        .catch(error => {
            console.error(error)
              if(error.response.data.errors.name){
                this.successMessage = error.response.data.errors.name[0];
                this.showError = true;
              }else if(error.response.data.errors.email){
                this.successMessage = error.response.data.errors.email[0];
                this.showError = true;
              }else if(error.response.data.errors.password){
                this.successMessage = error.response.data.errors.password[0];
                this.showError = true;
              }else if(error.response.data.errors.nif){
                this.successMessage = error.response.data.errors.nif[0];
                this.showError = true;
              }else if (error.response.data.errors.photo){
                this.successMessage = error.response.data.errors.photo[0];
                this.showError = true;
                   }
         })
    },
    cancelRegister: function(){
      axios.get('api/users/total')
      .then(response => {
        this.$router.push('/');
      })
    },
  },

  mounted() {}
};
</script>
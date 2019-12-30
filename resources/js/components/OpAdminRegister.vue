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
      <label for="inputType">Type:</label>
      <select name="type" id="type" class="form-control" v-model="user.type" required>
        <option disabled selected> -- select an option -- </option>
        <option value="a">Administrator</option>
        <option value="o">Operator</option>
    </select>
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
      <a class="btn btn-primary" v-on:click.prevent="createOpAdmin()">Register</a>
      <a class="btn btn-danger" v-on:click.prevent="cancelCreate()">Cancel</a>
    </div>
  </div>
</template>

<script>
    export default {
        data: function () {
            return {
                title: "Register an Operator/Admin",
                user: { name: "", email: "", password: "", nif: "", photo:{ name: "Insert Photo", base64: "",}, },
                showSuccess: false,
                showError: false,
                errors: [],
                successMessage: '',
                badName: false,
                badEmail: false,
                badPassword: false
            }
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
                this.user.photo.base64 = e.target.result;
            };
            reader.readAsDataURL(file);
            },

            createOpAdmin: function() {
                axios.post('api/users/OperatorAdmin', this.user)
                .then(response => {
                    Object.assign(this.user, response.data);
                    this.$router.push('/');
                    this.$toasted.show('User Created');
                })
                .catch(error => {
                    console.error(error);
                    if(error.response.data.errors.name){
                        this.successMessage = error.response.data.errors.name[0];
                        this.showError = true;
                    }else if(error.response.data.errors.email){
                        this.successMessage = error.response.data.errors.email[0];
                        this.showError = true;
                    }
                    else if(error.response.data.errors.password){
                        this.successMessage = error.response.data.errors.password[0];
                        this.showError = true;
                    }
                    else if(error.response.data.errors.type){
                        this.successMessage = error.response.data.errors.type[0];
                        this.showError = true;
                    }else if (error.response.data.errors.photo){
                        this.successMessage = error.response.data.errors.photo[0];
                        this.showError = true;
                    }
                });
            },
            cancelCreate: function(){
                this.$emit('create-canceled');
                this.$router.push('/');
            },
            /*
            onImageChange: function(event){     //UPLOAD IMAGE METHODS
                let image = event.target.files[0];
                this.user.photo = image.name;
                this.createImage(image);
            },
            createImage: function(file){
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.user.photoBase64 = e.target.result;
                };
                reader.readAsDataURL(file);
            }         
            */
        }
    }
</script>
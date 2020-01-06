<template>
    <div>
        <div class="jumbotron" >
            <h2>Edit Profile</h2>

            <div class="form-group">
                <label for="inputName">Name</label>
                <input
                    type="text" class="form-control" v-model="name"
                    name="name" id="inputName" 
                    placeholder="Fullname" value="" />
            </div>

         <div class="form-group">
                <label for="inputPhoto">Photo</label>
                <input
                type="file"
                class="form-control"
                name="photo"
                accept="image/x-png,image/gif,image/jpeg" 
                id="inputPhoto"
                placeholder="upload Photo"
                v-on:change="onImageChange"
                />
            </div>
            
            <div class="form-group" v-if="user.type == 'u'">
                <label for="inputNif">NIF</label>
                <input
                    type="text" class="form-control" v-model="nif"
                    name="Nif" id="inputNif"
                    placeholder="Nif" value=""/>
            </div>

            <div class="form-group">
                <label for="inputPassword">Old Password</label>
                <input
                    type="password" class="form-control" v-model="oldpassword"
                    name="oldpassword" id="inputOldPassword"
                    placeholder="Password" value=""/>
            </div>

            <div class="form-group">
                <label for="inputPassword">New Password</label>
                <input
                    type="password" class="form-control" v-model="password"
                    name="password" id="inputPassword"
                    placeholder="Password" value=""/>
            </div>

            <div class="form-group">
                <label for="inputPassword">Confirm Password</label>
                <input
                    type="password" class="form-control" v-model="confirmpassword"
                    name="confirmpassword" id="inputConfirmPassword"
                    placeholder="Password" value=""/>
            </div>

            <div class="form-group">
                <a class="btn btn-primary" v-on:click.prevent="saveProfile()">Save</a>
                <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
            </div>

            <!--<div class="alert alert-success" v-if="showSuccess">
                <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
                <strong>{{ successMessage }}</strong>
            </div>

            <div class="alert alert-danger" v-if="showFailure">
                <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
                <strong>{{ failMessage }}</strong>
            </div>-->
        </div>
    </div>  
</template>
<script>
export default {
    props: ['user'],
    data:function(){
        return{
            name:this.user.name,
            photo:this.user.photo,
            nif:this.user.nif,
            password:"",
            confirmpassword:"",
            oldpassword:"",
            //showSuccess: false,
            //showFailure: false,
            successMessage: '',
            failMessage: '',
            photoBase64:'',
            message:''
        }
    },
    methods:{

        onImageChange: function(event){
            let image = event.target.files[0];
            this.photo = image.name;
            this.createImage(image);
            //console.log(this.photo)
        },
        createImage: function(file){
            let reader = new FileReader();
            reader.onload = (e) => {
                this.photoBase64 = e.target.result;
                //console.log(this.photoBase64)
            };
            reader.readAsDataURL(file);
            
        },

        
        saveProfile: function(){

           if(this.password == ''){
                axios.patch('api/users/ProfilewithoutPass', {
                    'name': this.name,
                    'photo': this.photo,
                    'nif':this.nif,
                    'base64':this.photoBase64,
                    'userId': this.user.id
                }) 
                .then(response=>{	
                    this.$store.commit('setUser', response.data.data);
                    this.$emit('profile-modif');
                })
                .catch(error =>{
                    console.error(error)
                   if(error.response.data.errors.name){
                        this.$emit('profile-invalide-name');
                    }else if(error.response.data.errors.nif){
                        this.$emit('profile-invalide-nif');
                    }else if (error.response.data.errors.password){
                        this.$emit('profile-invalide-password');
                    }else if (error.response.data.errors.photo){
                        this.$emit('profile-invalide-image');
                }
                    this.$emit('profile-erro');
                })
           }else if(this.password == this.confirmpassword && this.password != this.oldpassword ){
                axios.patch('api/users/ProfilewithPass', {
                    'oldpassword': this.oldpassword,
                    'name': this.name,
                    'photo': this.photo,
                    'nif':this.nif,
                    'base64':this.photoBase64,
                    'password': this.password,
                    'userId': this.user.id,
                }) 
                .then(response=>{	
                    this.$store.commit('setUser', response.data.data);
                    this.$emit('profile-modif');
                })
                .catch(error =>{
                   if(error.response.data == "Old Password is incorrect !")
                        {
                            this.$emit('profile-erro-pass');
                        }
                    if(error.response.data.errors.name){
                        this.$emit('profile-invalide-name');
                    }else if(error.response.data.errors.nif){
                        this.$emit('profile-invalide-nif');
                    }else if (error.response.data.errors.password){
                        this.$emit('profile-invalide-password');
                    }else if (error.response.data.errors.image){
                        this.$emit('profile-invalide-image');
                }    
                    this.$emit('profile-erro');
                })
            }
            else {
                if(this.password != this.confirmpassword){
                    this.$emit('profile-erro-pass-diff');
                }else{
                    this.$emit('profile-erro-pass-equal'); 
                }
            }  
        },

        cancelEdit: function(){
            this.$emit('cancel-edit')
        },
    }
}
</script>
<style>

</style>
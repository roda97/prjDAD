<template>
  <div>
      <div class="jumbotron" >
        <h2>Edit Profile</h2>
    <div class="form-group">
        <label for="inputName">Name</label>
        <input
            type="text" class="form-control" v-model="$store.state.user.name"
            name="name" id="inputName" 
            placeholder="Fullname" value="" />
    </div>
    <div class="form-group">
        <label for="inputPhoto">Photo</label>
        <input
            type="text" class="form-control" v-model="$store.state.user.photo"
            name="photo" id="inputPhoto"
            placeholder="Photo" value=""/>
    </div>
    <div class="form-group">
        <label for="inputPassword">Password</label>
        <input
            type="text" class="form-control" v-model="$store.state.user.password"
            name="password" id="inputPassword"
            placeholder="Password" value=""/>
    </div>
    <div class="form-group">
        <label for="inputNif">NIF</label>
        <input
            type="text" class="form-control" v-model="$store.state.user.nif"
            name="Nif" id="inputNif"
            placeholder="Nif" value=""/>
    </div>

    <div class="form-group">
        <a class="btn btn-primary" v-on:click.prevent="saveProfile()">Save</a>
        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
    </div>
    </div>
  </div>  
</template>
<script>
export default {
    data:function(){
        return{
            
        }
    },
    methods:{
       saveProfile: function(){
         this.$emit('save-profile', $store.state.user)
        },
        cancelEdit: function(){
        this.$emit('cancel-edit')
        }
    }
}
</script>
<style>

</style>
<template>
    <div class="jumbotron"  >
        <h2>Edit User</h2>
        <div class="form-group">
            <label for="user_name">Name</label>
            <input id="user_name" v-model="user.name" />
        </div>
        <div class="form-group">
            <label for="user_email">Email</label>
            <input id="user_email" type="email" v-model="user.email" />
        </div>
        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
            <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
        </div>
    </div>
</template>

<script>



export default {



  data() {
    return {
      loaded: false,
      user: {
        id: null,
        name: "",
        email: ""
      }
    };
  },
    methods:{
        saveUser(){   
            this.$emit('save-user', this.user)
        },
        cancelEdit(){
            this.$emit('cancel-edit')
        }
    },
    mounted(){
        
    }

   
  }


</script>

<style>
</style>
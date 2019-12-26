<template> 
 <div>
    <div class="jumbotron">
            <h1>{{ title }}</h1>
    </div>
    <table class="table table-striped">
           <tr>
                <td><img v-bind:src="'storage/fotos/' + $store.state.user.photo" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td>{{ $store.state.user.name }}</td>
            </tr>
             <tr>
                <td>NIF:</td>
                <td>{{ $store.state.user.nif }}</td>
            </tr>
            <tr>
                <td>E-Mail:</td>
                <td>{{ $store.state.user.email }}</td>
            </tr>
            <a class="btn btn-sm btn-primary" v-on:click.prevent="profileEdit($store.state.user)">EditProfile</a>
    </table>
    <profile-edit v-if="editingProfile" v-bind:currentUser="currentUser" @save-user="saveUser" @cancel-edit="cancelEdit"></profile-edit>
    </div>
</template>
<script> 
import ProfileEdit from './profileEdit'
export default {
    data:function(){
        return{
            title: 'Profile',
            user: { 
                        id: this.$store.state.user.id,
                        name: this.$store.state.user.name,
                        nif: this.$store.state.user.nif,
                        photo: this.$store.state.user.photo,   
                },
            editingProfile: false
        }
    },
    methods:{
         profileEdit: function(user){
            this.$emit('profile-edit');
            this.editingProfile = true;
            this.currentUser = Object.assign({},user);
            this.showSuccess = false;
        },

        saveUser: function(){
            this.editingUser = false;            
            axios.put('api/users/' + this.user.id, this.user)
                .then(response=>{
                    this.showSuccess = true;
                    this.successMessage = 'User Saved';
                    Object.assign(this.user, response.data.data);
                    Object.assign(this.users.find(u=>u.id == response.data.data.id), response.data.data);
                    this.currentUser = null;
                    this.editingUser = false;
                });
        },
        cancelEdit: function(){
            this.editingProfile = false;
        },

    },
    components:{
        'profile-edit':ProfileEdit
    }
};
</script>

<style>
</style>
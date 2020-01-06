<template> 
    <div>
        <div class="jumbotron">
                <h1>{{ title }}</h1>
        </div>

        <table class="table table-striped">
            <tr>
                <td><img v-bind:src="'storage/fotos/' + user.photo" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td>{{ this.user.name }}</td>
            </tr>
            <tr v-if="user.type == 'u'">
                <td>NIF:</td>
                <td>{{ this.user.nif}}</td>
            </tr>
            <tr>
                <td>E-Mail:</td>
                <td>{{ this.user.email }}</td>
            </tr>
            <br>

            <a class="btn btn-sm btn-primary" v-on:click.prevent="profileEdit(user)">Edit</a>
        </table>
    
        <div class="alert alert-success" v-if="showSuccess">
                <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
                <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert alert-danger" v-if="showFailure">
                <button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
                <strong>{{ failMessage }}</strong>
        </div>

        <profile-edit :user="user" v-if="editingProfile" v-bind:currentUser="currentUser" @cancel-edit="cancelEdit" @profile-modif="profileModif" @profile-erro-pass-equal="profileErroPassEqual" @profile-erro-pass-diff="profileErroPassDiff" @profile-erro-pass="profileErroPass"></profile-edit>

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
                        email: this.$store.state.user.email,
                        type: this.$store.state.user.type
                },
            editingProfile: false,
            showSuccess: false,
            showFailure: false,
            successMessage: '',
            failMessage: ''
        }
    },
    methods:{
        profileEdit: function(user){
            this.currentUser = Object.assign({},user);
            this.showFailure = false;
            this.showSuccess = false;
            this.editingProfile = true;
        },
        profileModif: function(user){
            this.showSuccess = true;
            this.editingProfile = false;
            this.successMessage = 'User successfully modified';
        },
        profileErroPass: function(){
            this.showFailure = true;
            this.failMessage = 'Wrong Old Password'
        },
        profileErroPassEqual: function(){
            this.showFailure = true;
            this.failMessage = 'New Password and Old Password are same'
        },
        profileErroPassDiff: function(){
            this.showFailure = true;
            this.failMessage = 'Password and confirm password are different'
        },
        cancelEdit: function(){
            this.editingProfile = false;
        },
        
    },
    components:{
        'profile-edit':ProfileEdit
    },
    mounted() {
    }
};
</script>

<style>
</style>
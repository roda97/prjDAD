<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <user-list v-bind:users="users" v-on:edit-user="editUser" v-on:delete-user="deleteUser"></user-list> <!-- v-bind vai capturar o users que nao existia // em vez de v-on: podia ser apenas um @ -->
        <edit-list v-if="editingUser"  v-bind:currentUser="currentUser" v-on:save-user="saveUser" v-on:cancel-edit="cancelEdit"></edit-list>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
    </div>
</template>
<script type="text/javascript">
import UserList from "./userList.vue";
import UserEdit from "./userEdit.vue";

export default {
    data: function() {
        return {
        title: 'List Users',
        editingUser: false,
        showSuccess: false,
        showFailure: false,
        successMessage: '',
        failMessage: '',
        currentUser: null,
        users: []
        };
    },
    methods: {
        editUser: function(user){
            this.currentUser = Object.assign({},user); // antes estava " this.currentUser = user " e passou a ser como está pois da antiga forma quando se editava, o nome alterava logo sem se salvar e assim evita isso
            //this.currentUser = user; // (depois das alterações feitas la em aqui (Assinaladas) passou a dar => a opção de cima não funcionou pois perdia a referencia do user e não guardada as alteraçoes efetuadas
            this.editingUser = true;
            this.showSuccess = false;
        },
          
        deleteUser: function(user){
            axios.delete('api/users/'+user.id)
                .then(response => {
                    this.showSuccess = true;
                    this.successMessage = 'User Deleted';
                    this.getUsers();
                });
        },
        saveUser: function(user){
            this.editingUser = false;            
            axios.put('api/users/'+ user.id, user) //antes estava " userthis.currentUser.id,this.currentUser " em vez de " user.id, user " mas desta forma, previne a situação de editar e na lista não alterar até dar refresh (por não ter a mesma referencia por algum motivo)
                .then(response=>{
                    this.showSuccess = true;
                    this.successMessage = 'User Saved';
                    // Copies response.data.data properties to this.currentUser
                    // without changing this.currentUser reference
                    Object.assign(this.currentUser, response.data.data); //alterei aqui
                    Object.assign(this.users.find(u=>u.id == response.data.data.id), response.data.data);//e alterei aqui, ambas para tornar o editar a editar sem editar ao mesmo tempo que os que estão na lista
                    this.currentUser = null;
                    this.editingUser = false;
                });
        },
        cancelEdit: function(){
            this.showSuccess = false;
            this.editingUser = false;
            axios.get('api/users/'+this.currentUser.id)
                .then(response=>{
                    console.dir (this.currentUser);
                    // Copies response.data.data properties to this.currentUser
                    // without changing this.currentUser reference
                    Object.assign(this.currentUser, response.data.data); 
                    console.dir (this.currentUser);
                    this.currentUser = null;
                });
        },
        getUsers: function(){
            axios.get('api/users')
                .then(response=>{
                    console.log(response)
                    this.users = response.data.data;});
        }
        /*childMessage: function(message) {
            this.showSuccess = true;
            this.successMessage = message;
        }*/
    },
    components: {
        "user-list": UserList,
        "edit-list": UserEdit
    },
    mounted() {
        this.getUsers();
        axios.get('api/wallets')
            .then(response=>{this.wallets = response.data.data; });
    }
};
</script>

<style scoped></style>

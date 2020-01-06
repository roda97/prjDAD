<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody> 
                <tr>        
                    <td>
                        <input type="text" name="name" class="form-control"  placeholder="Search by user name" v-model="search.name">             
                    </td> 
                    <td>
                        <select name="type" class="form-control" v-model="search.type">
                            <option value='' selected> -- Type Of User -- </option>
                            <option value="a" >Administrator</option>
                            <option value="o" >Operator</option>
                            <option value="u" >Platform User</option>
                        </select>            
                    </td>              
                    <td>  
                        <input type="email" name="email" class="form-control" placeholder="Search by e-mail" v-model="search.email">             
                    </td> 
                    <td>
                        <select name="active" class="form-control" v-model="search.active">
                            <option value='' selected> -- Status Of Platform User -- </option>
                            <option value=1 >Active</option>
                            <option value=0 >Inactive</option>
                        </select>             
                    </td>            
                    <td>
                        <button type="submit" class="btn btn-primary" v-on:click="getResults()">Search</button>
                    </td>       
                </tr>
            </tbody>
        </table>

        <br>

        <user-list v-bind:users="users" v-on:edit-user="editUser" v-on:delete-user="deleteUser" v-on:activate-user="activateUser"></user-list> <!-- v-bind vai capturar o users que nao existia // em vez de v-on: podia ser apenas um @ -->
        <edit-list v-if="editingUser"  v-bind:currentUser="currentUser" v-on:save-user="saveUser" v-on:cancel-edit="cancelEdit"></edit-list>

        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>

        <div class="alert alert-danger" v-if="showFailure">			 
			<button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
			<strong>{{ failMessage }}</strong>
		</div>

        <div>
            <b-pagination  align="left" size="md-c"  v-model="page" :limit="10" :total-rows="total"  :per-page="10" @input="getResults(page)"></b-pagination>
            <br>
        </div>

    </div>
</template>
<script type="text/javascript">
import UserList from "./userList.vue";
//import UserEdit from "./userEdit.vue";
export default {
    data: function() {
        return {
        title: "Users' List",
        page:1,
        total:1,
        editingUser: false,
        showSuccess: false,
        showFailure: false,
        successMessage: '',
        failMessage: '',
        currentUser: null,
        listUsers: true,
        users: [],
        search:{
            name:'',
            type:'',
            email:'',
            active:'',
        }
        };
    },
    methods: {
        editUser: function(user){
            this.currentUser = Object.assign({},user); // antes estava " this.currentUser = user " e passou a ser como está pois da antiga forma quando se editava,
                                                     // o nome alterava logo sem se salvar e assim evita isso
            //this.currentUser = user; // (depois das alterações feitas la em aqui (Assinaladas) passou a dar => a opção de cima não funcionou pois perdia a referencia do user e não guardada as alteraçoes efetuadas
            this.editingUser = true;
            this.showSuccess = false;
        },
          
        deleteUser: function(user){
            axios.delete('api/users/destroy/'+user.id)
                .then(response => {
                    this.showSuccess = true;
                    this.successMessage = 'Deleted User with success';
                    this.getResults(1);
                });
        },
        saveUser: function(user){
            this.editingUser = false;            
            axios.put('api/users/'+ user.id, user) //antes estava " userthis.currentUser.id,this.currentUser " em vez de " user.id, user " mas desta forma, previne a situação de editar e na lista não alterar até dar refresh (por não ter a mesma referencia por algum motivo)
                .then(response=>{
                    this.showSuccess = true;
                    this.successMessage = 'Saved User with success';
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
        getResults(page){
            this.editingUser = false;
            this.showFailure = false;
            this.showSuccess = false;
            axios.post('api/users/filter?page='+page , this.search)
              .then(response=>{
                console.log(response.data.data)
                this.users = response.data.data;
                this.page = response.data.meta.current_page;
                //this.last = response.data.meta.last_page;
                this.total = response.data.meta.total;
            }).catch(error=>{
                this.failMessage = "Error, can't get the users!"//Não foi possivel ir buscar os users!'
                this.showFailure = true;
                this.showSuccess = false;
            });
        },
        getUsers: function(){
            axios.get('api/users')
                .then(response=>{
                    console.log(response)
                    this.users = response.data.data;});
        },
        activateUser: function(user){
            axios.put('api/users/activate/'+user.id)
                .then(response => {
                    this.showSuccess = true;
                    if(user.active == 0){
                        this.successMessage = ' User Active ';
                    }else{
                        this.successMessage = ' User Inactive';
                    }
                    this.getResults(1);
                });
        },
        /*childMessage: function(message) {
            this.showSuccess = true;
            this.successMessage = message;
        }*/
    },
    components: {
        "user-list": UserList,
        //"edit-list": UserEdit
    },
    mounted() {
        this.getResults(1);
        axios.get('api/wallets')
            .then(response=>{this.wallets = response.data.data; });
    }
};
</script>

<style scoped></style>
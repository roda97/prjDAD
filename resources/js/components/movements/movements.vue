<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
            <h3>Current balance: {{ balance }}$</h3>
        </div>

        <br>

        <div v-show="$store.state.user.type == 'u'">
            <a class="btn btn-primary" v-on:click.prevent="showAddDebit()">Add Debit</a>
        </div>

        <br>
        <br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Type of Payment</th>
                    <th>Transfer Email</th>
                    <th>Date Inf</th>
                    <th>Date Sup</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody> 
                <tr>        
                    <td>
                        <input type="text" name="id" class="form-control"  placeholder="ID" v-model="search.id">               
                    </td> 
                    <td>
                        <select name="type" class="form-control" v-model="search.type">
                            <option value="" selected> -- Type of Movement -- </option>
                            <option value="e" >Expense</option>
                            <option value="i" >Income</option>
                        </select>            
                    </td> 
                    <td>
                        <input type="text" name="category" class="form-control" placeholder="Category" v-model="search.category_name">               
                    </td>
                    <td>
                        <select name="type_payment" class="form-control" v-model="search.type_payment">
                            <option value="" selected> -- Type of Payment -- </option>
                            <option value="c" >Cash</option>
                            <option value="bt" >Bank Transfer</option>
                            <option value="mb" >MB Payment</option>
                        </select>            
                    </td>                
                    <td>
                        <input type="email" name="transfer_email" class="form-control" placeholder="Transfer email" v-model="search.email">               
                    </td> 
                    <td>
                        <input type="date" name="data_inf" class="form-control" v-model="search.data_inf">               
                    </td>   
                    <td>
                        <input type="date" name="data_sup" class="form-control" v-model="search.data_sup">               
                    </td>           
                    <td>
                        <button type="submit" class="btn btn-primary" v-on:click="filterMovements()">Search</button>
                    </td>       
                </tr>
            </tbody>
        </table>

        <movement-list v-if="listMovements" v-bind:movements="movements" v-on:edit-movement="editMovement" v-on:movement-details="movementDetails"></movement-list>
        <edit-list v-if="editingMovement"  v-bind:currentMovement="currentMovement" v-on:save-movement="saveMovement" v-on:cancel-edit="cancelEdit"></edit-list>
        <!--<movement-credit  v-if="showCredit" v-bind:currentMovement="currentMovement" v-on:add-credit="addCredit" v-on:email-error="emailError" v-on:cancel-credit="cancelCredit"></movement-credit>-->
        <movement-debit  v-if="showDebit" v-bind:movements="movements" v-bind:currentMovement="currentMovement" v-on:add-debit="addDebit" v-on:cancel-debit="cancelDebit"></movement-debit>
        <movement-details v-if="showDetails" v-bind:currentMovement="currentMovement" v-on:details-canceled="cancelMovementDetails" ></movement-details>

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

<script>
import MovementList from "./movementsList.vue";
import MovementEdit from "./movementEdit.vue";
//import MovementCredit from "./movementCredit.vue";
import MovementDebit from "./movementDebit.vue";
import MovementDetails from "./movementDetails.vue";

export default {
    data: function() {
        return {
            page:1,
            total:1,
            title: "Movements' List",
            balance: '',
            editingMovement: false,
            showDetails: false,
            currentMovement: {},
            showSuccess: false,
            showFailure: false,
            successMessage: '',
            failMessage: '',
            listMovements: true,
            //showCredit: false,
            showDebit: false,
            movements: [],
            search:{
                id: "",
                type: "",
                category_name: "",
                type_payment: "",
                email: "",
                data_inf: "",
                data_sup: "",
            }
        }
    },
    methods:{
        editMovement: function(movement){
            this.currentMovement = Object.assign({},movement);
            this.editingMovement = true;
            this.showCredit = false;
            this.showFailure = false;
            this.showSuccess = false;
            this.showDetails = false;
            this.showDebit = false;
        },
        saveMovement: function(movement){
            this.editingMovement = false;
            this.showDetails = false;   
            this.showCredit = false; 
            this.showDebit = false;        
            axios.put('api/movements/'+ movement.id, movement) 
                .then(response=>{
                    this.showFailure = false;
                    this.showSuccess = true;
                    this.successMessage = 'Movement saved with success';
                    // Copies response.data.data properties to this.currentMovement
                    // without changing this.currentMovement reference
                    Object.assign(this.currentMovement, response.data.data); 
                    Object.assign(this.movements.find(m=>m.id == response.data.data.id), response.data.data);
                    this.currentMovement = null;
                    this.editingMovement = false;
                }).catch(error => {                        
                    console.log(error);
                    this.editingMovement = true;
                    this.showSuccess = false;
                    this.showFailure = true;
                    if(error.response.data == "This type of movement can't have that category!"){
                        this.failMessage = error.response.data;
                    } 
                    //this.failMessage = "Error while editing the movement, please check the data values!"//"Erro ao editar o movimento, insira os dados corretamente!"; //VER SE CONSIGO MANDAR O ERRO ESPECIFICO
            });
        },
        cancelEdit: function(){
            this.showFailure = false;
            this.showSuccess = false;
            this.editingMovement = false;
            this.currentMovement = null;
            this.showDetails = false;
            this.showDebit = false;
            this.showCredit = false;
        },
        getResults(page){
            this.editingMovement = false;
            this.showCredit = false;
            this.showFailure = false;
            this.showSuccess = false;
            this.showDetails = false;
            this.showDebit = false;
            axios.post('api/movements/filter?page='+page , this.search).then(response=>{
                this.movements = response.data.data;
                this.page = response.data.meta.current_page;
                //this.last = response.data.meta.last_page;
                this.total = response.data.meta.total;
            }).catch(error=>{
                this.failMessage = "Error, can't get the movements!"//Não foi possivel ir buscar os movimentos!'
                this.showFailure = true;
                this.showSuccess = false;
            });
        },
        addDebit: function(movement){
            this.editingMovement = false;
            this.showCredit = false;
            this.showDetails = false;
            axios.post('api/movements/debit', movement)
            .then(response => {
                //console.log(movement);
                //this.addCredit(movement);
                console.log(response.data.data); 
                this.showFailure = false;
                //this.showSuccess = true;
                //this.successMessage = "Debit movement created with success";
                this.showDebit = false;
                this.$toasted.show("Debit movement created with success!");
                this.$socket.emit('updateMovements', response.data.data);
                //this.movements = response.data.data;
                //console.log(response.data.data)  
            }).catch(error => {                        
                console.log(error)
                this.showFailure = true;
                this.showSuccess = false;
                this.showDebit = true;
                if(error.response.status == 402){
                    if(error.response.data == "Email doesn't exist!"){
                        this.failMessage = error.response.data;
                    }  
                    if(error.response.data == "You can't transfer for your own wallet!"){
                        this.failMessage = error.response.data;
                    }  
                    if(error.response.data == "You don't have enought money!"){
                        this.failMessage = error.response.data;
                    }  
                }
                if(error.response.status == 422) {
                    if(error.response.data.errors.transfer){
                        this.failMessage = "The type of movement field is required.";
                    }
                    if (error.response.data.errors.type_payment){ 
                        this.failMessage = error.response.data.errors.type_payment[0];
                    }
                    if(error.response.data.errors.email){
                        this.failMessage = error.response.data.errors.email[0];
                    }
                    if (error.response.data.errors.source_description){
                        this.failMessage = error.response.data.errors.source_description[0];
                    }
                    if (error.response.data.errors.iban){
                        this.failMessage = error.response.data.errors.iban[0]+ " IBAN must have 2 capital letters followed by 23 digits." + '\n' + " Example: PT50002700000001234567833 ";
                    }
                    if (error.response.data.errors.mb_entity_code){
                        this.failMessage = error.response.data.errors.mb_entity_code[0];
                    }
                    if (error.response.data.errors.mb_payment_reference){
                        this.failMessage = error.response.data.errors.mb_payment_reference[0];
                    }
                    if (error.response.data.errors.value){
                        this.failMessage = error.response.data.errors.value[0]; //+ " Value must be between [0,01;5000]";
                    }
                    if (error.response.data.errors.category_name){
                        this.failMessage = error.response.data.errors.category_name[0];
                    }
                    if (error.response.data.errors.description){
                        this.failMessage = error.response.data.errors.description[0];
                    }
                    
                    //this.failMessage=error.response.data.errors;
                }                 
            })
        },
        showAddDebit: function(){
            this.currentMovement = {};
            this.showDebit = true;
            this.editingMovement = false;
            this.showSuccess = false;
            this.showFailure = false;
            this.showCredit = false;
            this.showDetails = false;
        },
        cancelDebit: function(){
            this.currentMovement = {};
            this.showDebit = false;
            this.editingMovement = false;
            this.showSuccess = false;
            this.showFailure = false;
            this.showCredit = false;
            this.showDetails = false;
        },
        movementDetails: function(movement){
            this.currentMovement = Object.assign({},movement);
            this.showDetails = true;
            this.editingMovement = false;
            this.showDebit = false;
            this.showSuccess = false;
            this.showFailure = false;
            this.showCredit = false;
            //this.currentMovement = movement;
        },           
        cancelMovementDetails: function(){
            this.showFailure = false;
            this.showSuccess = false;
            this.editingMovement = false;
            this.showDetails = false;
            this.currentMovement = null;
            this.showCredit = false;
            this.showDebit = false;
        },
        filterMovements: function(){
            this.showDetails = false;
            this.editingMovement = false;
            this.showDebit = false;
            this.showSuccess = false;
            this.showCredit = false;
            this.showDebit = false;
            axios.post('api/movements/filter', this.search)
                .then(response=>{
                    if(!Object.keys(response.data.data).length){ //if(typeof response.data == Object && !Object.keys(response.data.data).length){ //isto era se eu continuasse a mandar os erros no response
                        this.listMovements = false;
                        this.showFailure = true;
                        this.failMessage = "There are no movements with this data!";
                    /*}else if(response.data.data == "Email doesn't exist!"){
                        this.listMovements = false;
                        this.showFailure = true;
                        this.failMessage = response.data;
                    }else if(response.data.data == "Category doesn't exist!"){
                        this.listMovements = false;
                        this.showFailure = true;
                        this.failMessage = response.data;*/
                    }else{
                        this.listMovements = true;
                        this.showFailure = false;
                        console.log(response.data.data)
                        this.movements = response.data.data;
                        this.page = response.data.meta.current_page;
                        this.total = response.data.meta.total;
                    }                        
                })
                .catch(error => {                        
                    console.log(error.response.data);
                    if(error.response.data == "Email doesn't exist!"){
                        this.listMovements = false;
                        this.showFailure = true;
                        this.failMessage = error.response.data;
                    }
                    if(error.response.data == "Category doesn't exist!"){
                        this.listMovements = false;
                        this.showFailure = true;
                        this.failMessage = error.response.data;
                    }
                })
        },
        getBalance: function(){
            axios.get('api/movements/'+this.$store.state.user.id+'/balance')
                .then(response=>{
                    this.balance = response.data;
                })
                .catch(error => {                        
                    console.log(error);
                })
        },

        /*getMovements: function(){
            axios.get('api/movements')X
                .then(response=>{this.movements = response.data.data;});
        }*/
    },
    sockets:{
        updateMovements(data){ 
            console.log(data);
            console.log(data.user);
            //this.movements = this.movements + data;
            this.getResults(1);
            this.getBalance();
        },
        updateIncome(data){ 
            //console.log(data.user.emailIncome)
            console.log(data.auxiliar)
            console.log(data.user)
            //this.movements = this.movements + data.user;
            this.getResults(1);
            this.getBalance();
        }
    },
    components: {
        "movement-list": MovementList,
        "edit-list": MovementEdit,
        //"movement-credit": MovementCredit,
        "movement-debit": MovementDebit,
        "movement-details": MovementDetails,
    },
    mounted(){
        this.getResults(1);
        this.getBalance();
        //this.filterMovements();
        //this.getMovements();
    },
    /*computed:{
        isoperater(){
            return this.$store.state.user.type == 'o'; 
        }
    }*/
        
    };
</script>

<style>
</style>
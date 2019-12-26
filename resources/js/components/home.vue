<template>
    <div>
        <div>
                <h1>{{ title }}</h1>               
        </div>
         <h3>There are currently {{ wallets }} Virtual Wallets!</h3>

        <br>

        <div v-if="this.$store.state.user != null">
            <div v-if="$store.state.user.type == 'o'">
                <a class="btn btn-primary" v-on:click.prevent="showAddCredit()">Add Credit</a>
            </div>
        </div>

        <br>

        <movement-credit  v-if="showCredit" v-bind:currentMovement="currentMovement" v-on:add-credit="addCredit" v-on:cancel-credit="cancelCredit"></movement-credit>

        <div class="alert alert-danger" v-if="showFailure">			 
			<button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
			<strong>{{ failMessage }}</strong>
		</div>

    </div>
    
</template>

<script>

import MovementCredit from "./movements/movementCredit.vue";

    export default {
        data: function(){
            return{
                title: 'Virtual Wallet!',
                wallets: 0,
                showCredit: false,
                showFailure: false,
                currentMovement: {},
            }
        },
        methods:{
            addCredit: function(movement){
                axios.post('api/movements/credit', movement)
                .then(response => {
                    //console.log(response.data.data)
                    this.showFailure = false;
                    //this.showSuccess = true;
                    //this.successMessage = "Credit movement created with success";
                    this.showCredit = false;
                    this.$toasted.show("Credit movement created with success!");
                    //let auxiliar = 0;
                    this.$socket.emit('updateIncome', response.data.data);
                    //console.log(response.data)  
                }).catch(error => { 
                    console.log(error.response.data)
                    this.showFailure = false; //tenho de colocar primeiro a false e depois a true para atualizar o valor das mensagens
                    this.showFailure = true;
                    this.showCredit = true;
                    if(error.response.data == "Email doesn't exist!"){
                        this.failMessage = error.response.data;
                    }  
                    if(error.response.status == 422) {
                        if(error.response.data.errors.email){
                            this.failMessage = error.response.data.errors.email[0];
                        }
                        if (error.response.data.errors.value){
                            this.failMessage = error.response.data.errors.value[0];// + " Value must be between [0,01;5000]";
                        }
                        if (error.response.data.errors.type_payment){ 
                            this.failMessage = error.response.data.errors.type_payment[0];
                        }
                        if (error.response.data.errors.iban){
                            this.failMessage = error.response.data.errors.iban[0]+ " IBAN must have 2 capital letters followed by 23 digits." + '\n' + " Example: PT50002700000001234567833 ";
                        }
                        if (error.response.data.errors.source_description){
                            this.failMessage = error.response.data.errors.source_description[0];
                        }
                        //this.failMessage=error.response.data.errors;
                    }                    
                })
            },
            showAddCredit: function(){
                this.currentMovement = {};
                this.showCredit = true;
                this.showFailure = false;
            },
            cancelCredit: function(){
                this.currentMovement = {};
                this.showCredit = false;
                this.showFailure = false;
            },
        },
        components: {
            "movement-credit": MovementCredit,
        },
        mounted() {
            axios.get('api/home')
                .then(response=>{
                    this.wallets = response.data;
                });
        }
    }
</script>
<style scoped>

</style>

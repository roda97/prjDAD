<template>
    <div class="jumbotron">
        <h2>Register Credit</h2>

	    <div class="form-group">
	        <label for="inputEmail">Email to Credit:</label>
	        <input
	            type="email" class="form-control" v-model="currentMovement.emailIncome"
	            name="email" id="inputEmail" 
	            placeholder="Insert email of the account to receive the money" required/>
	    </div>

        <div class="form-group">
	        <label for="inputValue">Value to Credit:</label>
	        <input
	            type="text" class="form-control" v-model="currentMovement.value"
	            name="value" id="inputValue" 
	            placeholder="Insert value to credit" required/>
	    </div>

        <div class="form-group">
	       <label for="inputTypePayment">Type of Payment:</label>
            <select name="typePayment" id="InputTypePayment" class="form-control" v-model="currentMovement.type_payment" required>
                <option disabled selected> -- Select an option -- </option>
                <option value="c">Cash</option>
                <option value="bt">Bank Transfer</option>
                <option value="mb">MB Payment</option>
            </select>
	    </div>

        <div v-if="this.currentMovement.type_payment == 'bt'" >
            <div class="form-group">
                <label for="inputIBAN">IBAN:</label>
                <input
                    type="text" class="form-control" v-model="currentMovement.iban"
                    name="iban" id="inputIban" 
                    placeholder="Insert IBAN" required/>
	        </div>

            <div class="form-group">
                <label for="inputSourceDescription">Source Description:</label>
                <input
                    type="text" class="form-control" v-model="currentMovement.source_description"
                    name="sourceDescription" id="InputSourceDescription" 
                    placeholder="Insert a source description" required/>
            </div>
        </div>

        <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="addCredit()">Create Credit</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelCredit()">Cancel</a>
	    </div>

    </div>    
</template>

<script>
    export default {
        props:["currentMovement"],
        data: function(){
            return{
               /* movement:{
                    email: "",
                    value: "",
                    type_payment: "",
                    iban: null,
                    source_description: null,
                }*/

            }
        },

        methods: {
            addCredit: function(){
                if(this.currentMovement.value > 3000){
                    this.$socket.emit("updateValorMaximo", "Value > 3000", 0);
                }
                this.$emit('add-credit', this.currentMovement);
            },

            cancelCredit: function(){
                this.$emit('cancel-credit');
            },
        }
       
    }
</script>
<style scoped>

</style>
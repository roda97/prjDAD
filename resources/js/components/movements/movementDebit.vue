<template>
    <div class="jumbotron">
        <h2>Register Credit</h2>
	    <div class="form-group">
	        <label for="inputEmail">Email To Credit:</label>
	        <input
	            type="email" class="form-control" v-model="currentMovement.email"
	            name="email" id="inputEmail" 
	            placeholder="Insert email of the account to receive the money" required/>
	    </div>

        <div class="form-group">
	        <label for="inputValue">Value To Credit:</label>
	        <input
	            type="text" class="form-control" v-model="currentMovement.value"
	            name="value" id="inputValue" 
	            placeholder="Insert value to credit" required/>
	    </div>

        <div class="form-group">
	       <label for="inputTypePayment">Type Of Payment:</label>
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
	        <a class="btn btn-primary" v-on:click.prevent="addDebit(currentMovement)">Create Debit</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelDebit()">Cancel</a>
	    </div>

    </div>    
</template>

<script>
    export default {
        props:["currentMovement"],
        data: function(){
            return{

            }
        },

        methods: {
            addDebit: function(movement){
                this.$emit('add-debit', movement);
            },

            cancelDebit: function(){
                this.$emit('cancel-debit');
            },
        }
       
    }
</script>
<style scoped>

</style>
<template>
    <div class="jumbotron">
        <h2>Register Debit</h2>

        <div class="form-group">
	       <label for="inputTransfer">Type of Movement:</label>
            <select name="transfer" id="inputTransfer" class="form-control" v-model="currentMovement.transfer" required>
                <option disabled selected> -- Select an option -- </option>
                <option value="0">Payment to external entity</option>
                <option value="1">Transfer</option>
            </select>
	    </div>

        <div v-if="this.currentMovement.transfer == '0'" >
            <div class="form-group">
            <label for="inputTypePayment">Type of Payment:</label>
                <select name="typePayment" id="InputTypePayment" class="form-control" v-model="currentMovement.type_payment" required>
                    <option disabled selected> -- Select an option -- </option>
                    <option value="bt">Bank Transfer</option>
                    <option value="mb">MB Payment</option>
                </select>
            </div>
        </div>

        <div v-if="this.currentMovement.transfer == '1'" >
            <div class="form-group">
                <label for="inputEmail">Email to Debit:</label>
                <input
                    type="email" class="form-control" v-model="currentMovement.email"
                    name="email" id="inputEmail" 
                    placeholder="Insert email of the account to receive the money" required/>
            </div>

            <div class="form-group">
                <label for="inputSourceDescription">Source Description:</label>
                <input
                    type="text" class="form-control" v-model="currentMovement.source_description"
                    name="sourceDescription" id="InputSourceDescription" 
                    placeholder="Insert a source description" required/>
            </div>
        </div>   

        <div v-if="this.currentMovement.type_payment == 'bt'" > 
            <div class="form-group">
                <label for="inputIBAN">IBAN:</label>
                <input
                    type="text" class="form-control" v-model="currentMovement.iban"
                    name="iban" id="inputIban" 
                    placeholder="Insert IBAN" required/>
	        </div>
        </div>

        <div v-if="this.currentMovement.type_payment == 'mb'" > 
            <div class="form-group">
                <label for="inputMbEntityCode">MB entity code:</label>
                <input
                    type="text" class="form-control" v-model="currentMovement.mb_entity_code"
                    name="mbEntityCode" id="inputMbEntityCode" 
                    placeholder="Insert MB entity code (5 digits)" required/>
	        </div>
            
            <div class="form-group">
                <label for="inputMbPaymentReference">MB payment reference:</label>
                <input
                    type="text" class="form-control" v-model="currentMovement.mb_payment_reference"
                    name="mbPaymentReference" id="inputMbPaymentReference" 
                    placeholder="Insert MB payment reference (9 digits)" required/>
	        </div>
        </div>

        <div class="form-group">
	        <label for="inputValue">Value to Debit:</label>
	        <input
	            type="text" class="form-control" v-model="currentMovement.value"
	            name="value" id="inputValue" 
	            placeholder="Insert value to debit" required/>
	    </div>

        <!--<div class="form-group">
            <label for="InputCategoryName">Category name</label>
            <input
                type="text" class="form-control" v-model="currentMovement.category_name"
                name="categoryName" id="InputCategoryName" 
                placeholder="Insert a category name" required/>
        </div>-->

        <div class="form-group">
            <label for="InputCategoryName">Category name</label>
            <select v-model="currentMovement.category_name" class="form-control" name="categoryName" id="InputCategoryName" >
            <option disabled selected> -- Select an option -- </option>
            <!--<option  v-for="movement in movements"  :key="movement.id" :value="movement.category_id">{{ movement.category_name }}</option>-->
            <option  v-for="movement in category_name" :key="movement" :value="movement">{{ movement }}</option>
            </select>
        </div>

         <!--<select v-model="testVal">
        <option v-for="item in test" :value="item">{{item}}</option>
    </select>

    data(){
        return{
          test: ['one', 'two', 'three'],
          testVal: null
        }
    },-->

        <div class="form-group">
            <label for="inputDescription">Description:</label>
            <input
                type="text" class="form-control" v-model="currentMovement.description"
                name="Description" id="inputDescription" 
                placeholder="Insert a description" required/>
        </div>

        <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="addDebit()">Create Debit</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelDebit()">Cancel</a>
	    </div>

    </div>    
</template>

<script>
    export default {
        props:["currentMovement","movements"],
        data: function(){
            return{
                category_name:[
                    'groceries','restaurant','clothes','shoes','school','services', 'electricity', 'phone', 'fuel', 'mortgage payment', 
                    'car payment', 'entertainment', 'gadget', 'computer', 'vacation', 'hobby', 'loan repayment', 'loan', 'other expense'
                ]

            }
        },

        methods: {
            addDebit: function(){
                this.$emit('add-debit', this.currentMovement);
            },

            cancelDebit: function(){
                this.$emit('cancel-debit');
            },
        }
       
    }
</script>
<style scoped>

</style>
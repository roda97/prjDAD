<template>
     <div class="jumbotron">
        <h2>Movement {{ currentMovement.id }}</h2>

        <br>

        <div class="form-group"><strong>Photo:   </strong>
        <!--<div class="form-group" v-if="currentMovement.wallet"><strong>Photo:   </strong>-->
            <div class="col-md-10 col-md-offset-1" v-if="currentMovement.user_photo">            
                <td><img v-bind:src="'storage/fotos/' + currentMovement.user_photo" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
            </div>
            <div class="col-md-10 col-md-offset-1" v-if="!currentMovement.user_photo">            
                <td><img v-bind:src="'storage/fotos/noimage.jpg'" style="width:150px; height:150px; border-radius:50%; margin-bottom:25px; margin-right:25px; float:left;"></td>
            </div>
        </div>

        <div class="form-group">
	        <label v-if="currentMovement.description"><strong>Description:   </strong>{{ currentMovement.description}}</label>
	        <label v-if="!currentMovement.description"><strong>Description:   </strong>Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="currentMovement.source_description"><strong>Source Description:   </strong>{{ currentMovement.source_description}}</label>
	        <label v-if="!currentMovement.source_description"><strong>Source Description:   </strong>Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="currentMovement.iban"><strong>IBAN:   </strong>{{ currentMovement.iban}}</label>
	        <label v-if="!currentMovement.iban"><strong>IBAN:   </strong>Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="currentMovement.mb_entity_code"><strong>MB Entity Code:   </strong>{{ currentMovement.mb_entity_code}}</label>
	        <label v-if="!currentMovement.mb_entity_code"><strong>MB Entity Code:   </strong>Empty</label>
	    </div>

        <div class="form-group">
	        <label v-if="currentMovement.mb_payment_reference"><strong>MB Payment Reference:   </strong>{{ currentMovement.mb_payment_reference}}</label>
	        <label v-if="!currentMovement.mb_payment_reference"><strong>MB Payment Reference:   </strong>Empty</label>
	    </div>
       
       <button type="button" class="btn btn-light" v-on:click.prevent="cancelDetails()">Close</button>
    </div>
</template>

<script>
    export default {
        props: ['currentMovement'],

        methods: {
            cancelDetails: function(){
                this.$emit('details-canceled'); 
            },
           
        },
    }
</script>

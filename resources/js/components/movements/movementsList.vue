<template>
    <div>
        <br>
        <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Transfer Email</th>
                        <th>Type of Payment</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Start Balance</th>
                        <th>End Balance</th>
                        <th>Value</th>
                        <!--<th>Photo</th>-->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="movement in movements"  :key="movement.id" :class="{activerow: currentMovement === movement}">
                        <td>{{ movement.id }}</td>
                        <td>{{ movement.type }}</td>
                            <td v-if="movement.transfer_wallet_id != undefined">{{ movement.email }}</td>
                            <td v-if="movement.transfer_wallet_id == null"> - </td>
                        <td v-if="movement.type_payment == null"> - </td>
                        <td v-if="movement.type_payment != null"> {{movement.type_payment}}</td>
                            <td v-if="movement.category_id">{{ movement.category_name }}</td>
                            <td v-if="!movement.category_id"> - </td>
                        <td>{{ movement.date }}</td>
                        <td>{{ movement.start_balance }}</td>
                        <td>{{ movement.end_balance }}</td>
                        <td>{{ movement.value }}</td>
                        <!--ESTAS DUAS LINHAS A BAIXO DA FOTO SÃO PARA DESAPARECER, ESTÃO AQUI PARA DEBUG
                        <td v-if="movement.user_photo == null"> - </td>
                        <td v-if="movement.user_photo != null"> {{ movement.user_photo }}</td>-->
                        <td>
                            <div v-show="$store.state.user.id == movement.wallet_id ">
                                <a class="btn btn-sm btn-primary" v-on:click.prevent="editMovement(movement)">Edit</a>
                            </div>

                            <!--<div v-show="$store.state.user.id == movement.transfer_wallet_id ">
                                <a class="btn btn-sm btn-primary" v-on:click.prevent="editMovement(movement)">Edit</a>
                            </div>-->
                            
                            <div>
                                <a class="btn btn-sm btn-secondary" v-on:click.prevent="movementDetails(movement)">Details</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
        </table>

        <!--<div class="alert alert-success" v-if="showSuccess">			 
			<button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			<strong>{{ successMessage }}</strong>
		</div>

        <div class="alert alert-danger" v-if="showFailure">			 
			<button type="button" class="close-btn" v-on:click="showFailure=false">&times;</button>
			<strong>{{ failMessage }}</strong>
		</div>-->
    </div>    
</template>

<script>

export default {
    props:['movements'],
    data: function() {
        return {
            currentMovement: null,
            //showSuccess: false,
            //showFailure: false,

        }
    },
     methods:{
        editMovement: function(movement){
            this.currentMovement = movement // isto serve para ativar a linha de código da linha 13 que é o que faz com que clique em edit a linha fique a azul
            this.$emit('edit-movement', movement)
        },
        movementDetails: function(movement){
            this.currentMovement = movement;
            this.$emit('movement-details', movement)
        }, 

        /*getMovements: function(){
            axios.get('api/movements')
                .then(response=>{this.movements = response.data.data;});
        }*/
    }
        
    };
</script>

<style>
</style>
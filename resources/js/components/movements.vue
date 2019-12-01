<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>

        <movement-list v-bind:movements="movements" v-on:edit-movement="editMovement"></movement-list>
        <edit-list v-if="editingMovement"  v-bind:currentMovement="currentMovement" v-on:save-movement="saveMovement" v-on:cancel-edit="cancelEdit"></edit-list>

        <div>
            <b-pagination  align="left" size="md-c"  v-model="page" :limit="10" :total-rows="this.total"  :per-page="10" @input="getResults(page)"></b-pagination>
            <br>
        </div>
    </div>
</template>

<script>
import MovementList from "./movementsList.vue";
import MovementEdit from "./movementEdit.vue";

export default {
    data: function() {
        return {
            page:1,
            total:1,
            title: 'List Movements',
            editingMovement: false,
            currentMovement: null,
            movements: [],
        }
    },
     methods:{
        editMovement: function(movement){
            this.currentMovement = Object.assign({},movement);
            this.editingMovement = true;
        },
        saveMovement: function(movement){
            this.editingMovement = false;            
            axios.put('api/movements/'+ movement.id, movement) //antes estava " userthis.currentUser.id,this.currentUser " em vez de " user.id, user " mas desta forma, previne a situação de editar e na lista não alterar até dar refresh (por não ter a mesma referencia por algum motivo)
                .then(response=>{
                    //this.showSuccess = true;
                    //this.successMessage = 'User Saved';
                    // Copies response.data.data properties to this.currentUser
                    // without changing this.currentUser reference
                    Object.assign(this.currentMovement, response.data.data); //alterei aqui
                    Object.assign(this.movements.find(m=>m.id == response.data.data.id), response.data.data);//e alterei aqui, ambas para tornar o editar a editar sem editar ao mesmo tempo que os que estão na lista
                    this.currentMovement = null;
                    this.editingMovement = false;
                });
        },
        cancelEdit: function(){
            //this.showSuccess = false;
            this.editingMovement = false;
            this.currentMovement = null;
        },
        getResults(page)
        {
            axios.get('api/movements?page='+page).then(response=>{
            this.movements = response.data.data;
            this.page = response.data.meta.current_page;
            this.last = response.data.meta.last_page;
            this.total = response.data.meta.total;
            }).catch(error=>{
            this.failMessage = 'Não foi possivel ir buscar os itens!'
            this.showFailure = true;
            });
        },
        /*getMovements: function(){
            axios.get('api/movements')
                .then(response=>{this.movements = response.data.data;});
        }*/
    },
    components: {
        "movement-list": MovementList,
        "edit-list": MovementEdit
    },
    mounted(){
        this.getResults(1);
        //this.getMovements();
    },
        
    };
</script>

<style>
</style>
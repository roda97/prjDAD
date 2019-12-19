<template>
    <div class="jumbotron"  >
        <h2>Edit Movement {{ currentMovement.id }}</h2>
        <!--<div class="form-group">
            <label for="inputId">Id</label>
            <input
                type="text" class="form-control" v-model="currentMovement.id"
                name="id" id="inputId" 
                placeholder="Id" value="" />
        </div>-->
        <!--<div class="form-group">
            <label for="inputEmail">Tipo</label>
            <input
                type="text" class="form-control" v-model="currentMovement.type"
                name="tipo" id="inputTipo"
                placeholder="Tipo" value=""/>
        </div>-->
        <div class="form-group">
            <label for="inputCategoria">Categoria</label>
            <input
                type="text" class="form-control" v-model="currentMovement.category_name"
                name="categoria" id="inputCategoria"
                placeholder="categoria" value=""/>
        </div>
        <div class="form-group">
            <label for="inputDescricao">Descrição</label>
            <input
                type="text" class="form-control" v-model="currentMovement.description"
                name="descricao" id="inputDescricao"
                placeholder="Descrição" value=""/>
        </div>


        <div class="form-group">
            <a class="btn btn-primary" v-on:click.prevent="saveMovement()">Save</a>
            <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
        </div>
    </div>
</template>

<script>

export default{
    props:['currentMovement'],
    data: function(){
        return{
        }
    },
    methods:{
        saveMovement(){
            console.log(this.currentMovement)   
            this.$emit('save-movement', this.currentMovement)
        },
        cancelEdit(){
            this.$emit('cancel-edit')
        }
    },
    mounted(){

    }
};
</script>

<style>
</style>
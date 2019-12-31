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

        <div v-if="currentMovement.type == 'e'">
            <div class="form-group">
                <label for="InputCategoryName">Category name</label>
                <select v-model="currentMovement.category_name" class="form-control" name="categoryName" id="InputCategoryName" >
                <option disabled selected> -- Select an option -- </option>
                <!--<option  v-for="movement in movements"  :key="movement.id" :value="movement.category_id">{{ movement.category_name }}</option>-->
                <option  v-for="movement in category_name_e" :key="movement" :value="movement">{{ movement }}</option>
                </select>
            </div>
        </div>

        <div v-if="currentMovement.type == 'i'">
            <div class="form-group">
                <label for="InputCategoryName">Category name</label>
                <select v-model="currentMovement.category_name" class="form-control" name="categoryName" id="InputCategoryName" >
                <option disabled selected> -- Select an option -- </option>
                <!--<option  v-for="movement in movements"  :key="movement.id" :value="movement.category_id">{{ movement.category_name }}</option>-->
                <option  v-for="movement in category_name_i" :key="movement" :value="movement">{{ movement }}</option>
                </select>
            </div>
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
            category_name_e:[
                'groceries','restaurant','clothes','shoes','school','services', 'electricity', 'phone', 'fuel', 'mortgage payment', 
                'car payment', 'entertainment', 'gadget', 'computer', 'vacation', 'hobby', 'loan repayment', 'loan', 'other expense'
            ],
            category_name_i:[
                'salary', 'bonus', 'royalties', 'interests', 'gifts', 
                'dividends', 'sales', 'loan repayment', 'loan', 'other expense'
            ]
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
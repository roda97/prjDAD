<template>
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Tipo</th>
                    <th>Data</th>
                    <th>Iban</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="movement in movements"  :key="movement.id" :class="{active: currentMovement === movement}">
                    <td>{{ movement.id }}</td>
                    <td>{{ movement.type }}</td>
                    <td>{{ movement.date }}</td>
                    <td>{{ movement.iban }}</td>
                    <td>
                        <a class="btn btn-sm btn-primary" v-on:click.prevent="editMovement(movement)">Edit</a>
                    </td>
                </tr>
            </tbody>
    </table>
</template>

<script>

export default {
    props:['movements'],
    data: function() {
        return {
            currentMovement:null
        }
    },
     methods:{
        editMovement: function(movement){
            this.currentMovement = movement // isto serve para ativar a linha de código da linha 13 que é o que faz com que clique em edit a linha fique a azul
            this.$emit('edit-movement', movement)
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
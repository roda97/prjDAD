<template>
    <div>
        <div class="jumbotron">
            <h1>{{ title }}</h1>
        </div>
        <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tipo</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="movement in movements"  :key="movement.id" >
                        <td>{{ movement.id }}</td>
                        <td>{{ movement.type }}</td>
                        <td>{{ movement.date }}</td>
                    </tr>
                </tbody>
        </table>
    </div>
</template>

<script>

export default {
    data: function() {
        return {
        title: 'List Movements',
        movements: []
        }
    },
     methods:{
        getMovements: function(){
            axios.get('api/movements')
                .then(response=>{this.movements = response.data.data;});
        }
    },
    mounted(){
        this.getMovements();
    },
        
    };
</script>

<style>
</style>
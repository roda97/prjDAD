<template> 
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Email</th>
                <th>Active</th>
                <th>Balance</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users"  :key="user.id" :class="{active: currentUser === user}">
                <td v-if="user.photo"><img v-bind:src="'storage/fotos/' + user.photo" style="width:75px; height:75px; border-radius:50%;"></td>
                <td v-if="!user.photo"><img v-bind:src="'storage/fotos/unknown.jpg'" style="width:75px; height:75px; border-radius:50%;"></td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>

                <td v-if="user.type == 'u' && user.active==1">Active</td>
                <td v-if="user.type == 'u' && user.active==0">Inactive</td>
                <td v-if="user.type != 'u'"> </td>

                <td v-if="user.balance > 0">Has Money</td>  
                <td v-if="user.balance == 0">Empty</td> 
                <td v-if="user.balance == null">  </td> 

                <td v-if="user.type == 'u'">Plataform User</td>
                <td v-if="user.type == 'o'">Operator</td>
                <td v-if="user.type == 'a'">Administrator</td>    
                
                <td v-if="user.type == 'u' && user.balance != 0.00"> </td>
                <a class="btn btn-sm btn-secondary" v-if="user.type == 'u' && user.balance == 0.00 && user.active == 1" v-on:click.prevent="activateUser(user)">Desactive</a>
                <a class="btn btn-sm btn-primary" v-if="user.type == 'u' && user.balance == 0.00 && user.active == 0" v-on:click.prevent="activateUser(user)">Active</a>
                <a class="btn btn-sm btn-danger" v-if="user.type == 'a' || user.type == 'o'" v-on:click.prevent="deleteUser(user)">Delete</a>
            </tr>
        </tbody>
    </table>
</template>

<script> 
export default {
    props:['users'],
    data:function(){
        return{
            currentUser:null,
            wallets:[]
        }
    },
    methods:{
        editUser(user){
            this.currentUser = user // isto serve para ativar a linha de código da linha 13 que é o que faz com que clique em edit a linha fique a azul
            this.$emit('edit-user', user)
        },
        deleteUser(user){
            this.$emit('delete-user', user)
        },
        activateUser(user){
            this.$emit('activate-user', user)
        }
    }

};
</script>

<style>
</style>
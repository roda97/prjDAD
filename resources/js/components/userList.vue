<template> 
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in users"  :key="user.id" :class="{active: currentUser === user}">
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.balance }}</td>
                    <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
                    <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
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
    }

};
</script>

<style>
</style>
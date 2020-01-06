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
                <td v-if="!user.photo"><img v-bind:src="'storage/fotos/noimage.jpg'" style="width:75px; height:75px; border-radius:50%;"></td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>

                <td v-if="user.type == 'u' && user.active==1">Active</td>
                <td v-if="user.type == 'u' && user.active==0">Inactive</td>
                <td v-if="user.type != 'u'"> - </td>

                <td v-if="user.balance > 0">Has Money</td>  
                <td v-if="user.balance == 0">Empty</td> 
                <td v-if="!user.balance"> - </td> 

                <td v-if="user.type == 'u'">Plataform User</td>
                <td v-if="user.type == 'o'">Operator</td>
                <td v-if="user.type == 'a'">Administrator</td>    
                
                <td v-if="user.type == 'u' && user.balance != 0.00"> </td>
                <td>
                    <div>
                        <a class="btn btn-sm btn-primary" v-if="user.type == 'u' && user.balance == 0.00 && user.active == 1" v-on:click.prevent="activateUser(user)">Desactive</a>
                    </div>

                    <div>
                        <a class="btn btn-sm btn-primary" v-if="user.type == 'u' && user.balance == 0.00 && user.active == 0" v-on:click.prevent="activateUser(user)">Active</a>
                    </div>

                    <div v-show="user.type == 'a' || user.type == 'o'">
                        <a class="btn btn-sm btn-danger" v-if=" user.id != currentUser.id" v-on:click.prevent="deleteUser(user)">Delete</a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script> 
export default {
    props:['users'],
    data:function(){
        return{
            currentUser: this.$store.state.user,
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
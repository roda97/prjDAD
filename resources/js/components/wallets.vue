<template> 
    <div>
    <div class="jumbotron">
            <h1>{{ title }}</h1>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Email</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="wallet in wallets"  :key="wallet.id" >
                <td>{{ wallet.email }}</td>
                <td>{{ wallet.balance }}</td>
            </tr>
        </tbody>
    </table>
    </div>
</template>

<script>

export default {
    data: function() {
        return {
        title: 'List Wallets',
        wallets: []
        }
    },
     methods:{
        getWallets: function(){
            axios.get('api/wallets')
                .then(response=>{this.wallets = response.data.data;});
        }
    },
    mounted(){
        this.getWallets();
    },
        
    };
</script>

<style>
</style>
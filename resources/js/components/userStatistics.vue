<template>
    <div>

        <div v-if="statsTotalExpInc" class="container">
            <h4>Total Movements Income/Expenses:</h4>
           <all-income-expense :chartData="chartData1"/> 
        </div>   

        <br>
        
        <div v-if="statsInative" class="container">
            <h4>Total Users Inative/Active:</h4>
            <stats-inative :chartData="chartData2"/>
        </div>

        <div v-if="statsWallets" class="container">
            <stats-wallets-per-year/>
        </div>
    </div>    
</template>

<script>

import StatsTotalExpInc from './statsTotalExpInc.vue';
import StatsInative from './statsInative.vue';
import StatsWalletsPerYear from './statsWalletsPerYear.vue';

import { Bar, Line} from 'vue-chartjs';

export default {
    
    data:function(){
        return{
            userID: this.$store.state.user.id,
            chartData1: null,
            chartData2: null,
            statsTotalExpInc: false,
            statsInative: false,
            statsWallets: true
        }

    },
    methods:{
        getAllMovements: function(){
            axios.get('api/users/stats/all')
            .then(response =>{
                //console.log(response.data)
                this.chartData1 = response.data;
                this.statsTotalExpInc = true;
            })
        },  

        getAllUsersInatives: function(){
            axios.get('api/users/statsInative')
            .then(response =>{
                //console.log(response.data)
                this.chartData2 = response.data;
                this.statsInative = true;
            })
        },
    },
    mounted(){
        this.getAllMovements();
        this.getAllUsersInatives();
    },
    components:{
        'all-income-expense': StatsTotalExpInc,
        'stats-inative' : StatsInative,
        'stats-wallets-per-year': StatsWalletsPerYear
    }
}
</script>
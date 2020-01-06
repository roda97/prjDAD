<template>
    <div>

        <div v-if="statsTotalExpInc" class="container">
            <h4>Total Movements Income/Expenses:</h4>
            <all-income-expense :chartData="chartData1"/>
        </div>   

    </div>    
</template>

<script>

import StatsTotalExpInc from './statsTotalExpInc.vue';
import StatsLinearChart from './statsLinearChart.vue';

import { Bar, Line } from 'vue-chartjs';

export default {
    
    data:function(){
        return{
            userID: this.$store.state.user.id,
            chartData1: null,
            statsTotalExpInc: false,
        }

    },
    methods:{
        getAllMovements: function(){
            axios.get('api/users/stats/all')
            .then(response =>{
                console.log(response.data)
                this.chartData1 = response.data;
                this.statsTotalExpInc = true;
            })
        },

    },
    mounted(){
        this.getAllMovements();

    },
    components:{
        'all-income-expense': StatsTotalExpInc,
        'stats-line-chart': StatsLinearChart,

    }
}
</script>
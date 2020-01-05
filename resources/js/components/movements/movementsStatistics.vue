<template>
    <div>

        <div v-if="statsIncExpe" class="container">
            <h4>Total Income/Expenses (€):</h4>
            <totals-income-expense :chartData="chartData1"/>
        </div>   

    </div>    
</template>

<script>

import StatsTotalsIncExpe from './statsIncExpe.vue';
import StatsLineChart from './statsLineChart.vue';

import { Bar, Line } from 'vue-chartjs';

export default {
    
    data:function(){
        return{
            userID: this.$store.state.user.id,
            chartData1: null,
            statsIncExpe: false,
        }

    },
    methods:{
        getTotalsMovements: function(){
            axios.get('api/movements/stats/totals/'+ this.userID)
            .then(response =>{
                console.log(response.data)
                this.chartData1 = response.data;
                this.statsIncExpe = true;
            })
        },

    },
    mounted(){
        this.getTotalsMovements();

    },
    components:{
        'totals-income-expense': StatsTotalsIncExpe,
        'stats-line-chart': StatsLineChart,

    }
}
</script>
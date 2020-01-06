<template>
    <div>

        <div v-if="statsIncExpe" class="container">
            <h4>Total Income/Expenses (€):</h4>
            <totals-income-expense :chartData="chartData1"/>
        </div>  
    
        <br>

        <div v-if="statsTypePayment" class="container">
            <h4>Type of Payment:</h4>
            <stats-type-payment :chartData="chartData2"/>
        </div>

        <br> 

        <div v-if="statsCategoryIncome" class="container">
            <h4>Total Number of Incomes By Category:</h4>
            <stats-category-income :chartData="chartData3" :labels="labels"/>
        </div>  

        <br>

        <div v-if="statsCategoryExpense" class="container">
            <h4>Total Number of Expenses By Category:</h4>
            <stats-category-expense :chartData="chartData4" :labels="labels2"/>
        </div> 

        <br>

        <div v-if="statsCategoryIncome" class="container">
            <h4>Total Income By Category (€):</h4>
            <stats-category-income-money :chartData="chartData5" :labels="labels3"/>
        </div>  

        <br>

        <div v-if="statsCategoryExpense" class="container">
            <h4>Total Expenses By Category (€):</h4>
            <stats-category-expense-money :chartData="chartData6" :labels="labels4"/>
        </div> 
    
    </div>    
</template>

<script>

import StatsTotalsIncExpe from './statsIncExpe.vue';
import StatsLineChart from './statsLineChart.vue';
import StatsTypePayment from './statsTypePayment.vue';
import StatsCategoryIncome from './statsCategoryIncome.vue';
import StatsCategoryExpense from './statsCategoryExpense.vue';
import StatsCategoryIncomeMoney from './statsCategoryIncomeMoney.vue';
import StatsCategoryExpenseMoney from './statsCategoryExpenseMoney.vue';

import { Bar, Line } from 'vue-chartjs';

export default {
    
    data:function(){
        return{
            userID: this.$store.state.user.id,
            chartData1: null,
            chartData2: null,
            chartData3: null,
            chartData4: null,
            chartData5: null,
            chartData6: null,
            statsIncExpe: false,
            statsTypePayment: false,
            statsCategoryIncome: false,
            statsCategoryExpense: false,
            statsCategoryIncomeMoney: false,
            statsCategoryExpenseMoney: false,
            labels: null,
            labels2: null,
            labels4: null,
            labels5: null,
            rows: null,
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
        getTypePayment: function(){
            axios.get('api/movements/stats/typePayment/'+ this.userID)
            .then(response =>{
                console.log(response.data)
                this.chartData2 = response.data;
                this.statsTypePayment = true;
            })
        },
        getCategoryIncome: function(){
            axios.get('api/movements/stats/categoryIncome/'+ this.userID)
            .then(response =>{
                console.log(response.data)
                this.chartData3 = response.data.rows;
                this.labels = response.data.labels;
                this.statsCategoryIncome = true;
            })
        },
        getCategoryExpense: function(){
            axios.get('api/movements/stats/categoryExpense/'+ this.userID)
            .then(response =>{
                console.log(response.data)
                this.chartData4 = response.data.rows;
                this.labels2 = response.data.labels;
                this.statsCategoryExpense = true;
            })
        },
        getCategoryIncomeMoney: function(){
            axios.get('api/movements/stats/categoryIncomeMoney/'+ this.userID)
            .then(response =>{
                console.log(response.data)
                this.chartData5 = response.data.rows;
                this.labels3 = response.data.labels;
                this.statsCategoryIncomeMoney = true;
            })
        },
        getCategoryExpenseMoney: function(){
            axios.get('api/movements/stats/categoryExpenseMoney/'+ this.userID)
            .then(response =>{
                console.log(response.data)
                this.chartData6 = response.data.rows;
                this.labels4 = response.data.labels;
                this.statsCategoryExpenseMoney = true;
            })
        },

    },
    mounted(){
        this.getTotalsMovements();
        this.getTypePayment();
        this.getCategoryIncome();
        this.getCategoryExpense();
        this.getCategoryIncomeMoney();
        this.getCategoryExpenseMoney();
    },
    components:{
        'totals-income-expense': StatsTotalsIncExpe,
        'stats-line-chart': StatsLineChart,
        'stats-type-payment': StatsTypePayment,
        'stats-category-income': StatsCategoryIncome,
        'stats-category-expense': StatsCategoryExpense,
        'stats-category-income-money': StatsCategoryIncomeMoney,
        'stats-category-expense-money': StatsCategoryExpenseMoney,

    }
}
</script>
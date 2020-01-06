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
            <h6>There are {{this.text[0]}} uncategorized incomes with a total number of {{ this.text[1] }}
                that are not included in the chart.</h6>
            <br>
            <stats-category-income :chartData="chartData3" :labels="labels"/>
        </div>  

        <br>

        <div v-if="statsCategoryExpense" class="container">
            <h4>Total Number of Expenses By Category:</h4>
            <h6>There are {{this.text2[0]}} uncategorized expenses with a total number of {{ this.text2[1] }}
                that are not included in the chart.</h6>
            <br>
            <stats-category-expense :chartData="chartData4" :labels="labels2"/>
        </div> 

        <br>

        <div v-if="statsCategoryIncomeMoney" class="container">
            <h4>Total Income By Category (€):</h4>
            <h6> In a total value of {{ this.text3[1] }}€, 
                there are {{this.text3[0]}}€ uncategorized incomes that are not included in the chart.</h6>
            <br>
            <stats-category-income-money :chartData="chartData5" :labels="labels3"/>
        </div>  

        <br>

        <div v-if="statsCategoryExpenseMoney" class="container">
            <h4>Total Expenses By Category (€):</h4>
            <h6> In a total value of {{ this.text4[1] }}€, 
                there are {{this.text4[0]}}€ uncategorized incomes that are not included in the chart.</h6>
            <br>
            <stats-category-expense-money :chartData="chartData6" :labels="labels4"/>
        </div> 

        <br>

        <stats-balance-per-date :graphs="graphs" v-if="showGraphs"></stats-balance-per-date>
   
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
import StatsBalancePerDate from "./statsBalancePerDate.vue";

import { Bar, Line } from 'vue-chartjs';

export default {
    
    data:function(){
        return{
            graphs: [],
            showGraphs: false,
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
            labels3: null,
            labels4: null,
            labels5: null,
            rows: null,
            text: null,
            text2: null,
            text3: null,
            text4: null
        }

    },
    methods:{
        getTotalsMovements: function(){
            axios.get('api/movements/stats/totals/'+ this.userID)
            .then(response =>{
                //console.log(response.data)
                this.chartData1 = response.data;
                this.statsIncExpe = true;
            })
        },
        getTypePayment: function(){
            axios.get('api/movements/stats/typePayment/'+ this.userID)
            .then(response =>{
                //console.log(response.data)
                this.chartData2 = response.data;
                this.statsTypePayment = true;
            })
        },
        getCategoryIncome: function(){
            axios.get('api/movements/stats/categoryIncome/'+ this.userID)
            .then(response =>{
                //console.log(response.data)
                this.chartData3 = response.data.rows;
                this.labels = response.data.labels;
                this.text = response.data.totals;
                this.statsCategoryIncome = true;
            })
        },
        getCategoryExpense: function(){
            axios.get('api/movements/stats/categoryExpense/'+ this.userID)
            .then(response =>{
                //console.log(response.data)
                this.chartData4 = response.data.rows;
                this.labels2 = response.data.labels;
                this.text2 = response.data.totals;
                this.statsCategoryExpense = true;
            })
        },
        getCategoryIncomeMoney: function(){
            axios.get('api/movements/stats/categoryIncomeMoney/'+ this.userID)
            .then(response =>{
                //console.log(response.data)
                this.chartData5 = response.data.rows;
                this.labels3 = response.data.labels;
                this.text3 = response.data.totals;
                this.statsCategoryIncomeMoney = true;
            })
        },
        getCategoryExpenseMoney: function(){
            axios.get('api/movements/stats/categoryExpenseMoney/'+ this.userID)
            .then(response =>{
                //console.log(response.data)
                this.chartData6 = response.data.rows;
                this.labels4 = response.data.labels;
                this.text4 = response.data.totals;
                this.statsCategoryExpenseMoney = true;
            })
        },
        getBalancePerDate: function() {
            //GRÁFICO COM ZOOM
            let date = [];
            let endBalance = [];
            axios.get("api/movements/me")
                .then(response => {
                    this.movements = response.data.data;

                    this.movements.forEach((value, i) => {
                        endBalance.push(value.end_balance);
                        date.push(value.date);
                    });

                    let array = [];
                    date.forEach((v, i) => {
                        array[i] = [v, parseInt(endBalance[i])];
                    });

                    this.graphs = array;
                    //console.log(this.graphs)
                    this.showGraphs = true; //uso com v-if para prevenir o facto de os dados demorarem a carregar e o grafico não aparecer na página ou aparecer mas sem dados
                })
                .catch(error => {
                    this.$toasted.error("Error, can't get the movements!");
                });
        }

    },
    mounted(){

        this.getTotalsMovements();
        this.getTypePayment();
        this.getCategoryIncome();
        this.getCategoryExpense();
        this.getCategoryIncomeMoney();
        this.getCategoryExpenseMoney();
        this.getBalancePerDate();
    },
    components:{
        'totals-income-expense': StatsTotalsIncExpe,
        'stats-line-chart': StatsLineChart,
        'stats-type-payment': StatsTypePayment,
        'stats-category-income': StatsCategoryIncome,
        'stats-category-expense': StatsCategoryExpense,
        'stats-category-income-money': StatsCategoryIncomeMoney,
        'stats-category-expense-money': StatsCategoryExpenseMoney,
        'stats-balance-per-date': StatsBalancePerDate

    }
}
</script>
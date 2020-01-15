<template>
<div>
        <table class="table table-striped">
            <tbody>
                <br>
                <h4>Number of Wallets Created Per Year</h4>
                <br>
                <div class="app">
                    <apexcharts align="center" width="1000" height="500" type="bar" :options="chartOptions" :series="series"></apexcharts>
                </div>
            </tbody>
        </table>
    </div>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'

export default {
    data: function() {
        return {
            years: [],
            nWallets:[],
            chartOptions: {
                xaxis: {
                    categories: []
                },
            },
            series: [{
                name: 'Wallets',
                data: []
            }]
        }
    },
    methods: {
        updateChart() {
            axios.get("api/wallets")
                .then(response => {
                    this.wallets = response.data.data;
                    this.wallets.forEach(wallet => {
                        if(this.years.includes(wallet.created_at.substring(0, 4))){

                        }else{
                            this.years.push(wallet.created_at.substring(0, 4));
                        }

                    this.years = this.sortYears();
                    
                    let i = 0;
                    this.years.forEach(year => {
                        this.nWallets[i] = 0;
                        this.wallets.forEach(wallet => {
                            if(year == wallet.created_at.substring(0, 4)){
                                this.nWallets[i]++;
                            }
                        })
                        i++;
                    })

                    this.series[0].data = this.nWallets;
                    this.chartOptions.xaxis.categories = this.years;

                    const colors = ['#8A2BE2','#D2691E', '#FF8C00', '#B8860B', '#8B0000', '#483D8B', '#4169E1']

                    this.chartOptions = {
                        colors: [colors[Math.floor(Math.random()*colors.length)]],
                        xaxis: {
                            categories: this.years
                        },
                    };
                    
                })
            })
            .catch(error => {
                this.$toasted.error("Error, can't get the wallets!");
            });
        },
        sortYears(){
            let copy = this.years;
            return copy.sort();
        }
    },
    created() {
        this.updateChart();
    },
    components: {
      apexcharts: VueApexCharts,
    }
};
</script>
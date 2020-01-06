<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,

  props:['chartData'],

    mounted () {
        this.renderChart({
            labels: ['Total Expenses/Incomes (€)'],
            datasets: [
              {
                label: 'Total Expenses',
                backgroundColor: '#483D8B',
                data: [this.chartData[1]]
              },
              {
                label: 'Total Incomes',
                backgroundColor: '#4169E1',
                data: [this.chartData[0]]
              }
            ]
        }, 
        {
          responsive: true, 
          maintainAspectRatio: false
        })
    },
}

</script>

<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,

  props:['chartData'],

    mounted () {
        this.renderChart({
            labels: ['Type of Payment'],
            datasets: [
              {
                label: 'Cash',
                backgroundColor: '#483D8B',
                data: [this.chartData[0]]
              },
              {
                label: 'MB Payment',
                backgroundColor: '#6A5ACD',
                data: [this.chartData[1]]
              },
              {
                label: 'Bank Transfer',
                backgroundColor: '#4169E1',
                data: [this.chartData[2]]
              },
              {
                label: 'Transfer Email',
                backgroundColor: '#87CEEB',
                data: [this.chartData[3]]
              },
            ]
        }, 
        {
          responsive: true, 
          maintainAspectRatio: false
        })
    },
}

</script>
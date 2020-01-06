<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,

  props:['chartData', 'labels'],

    mounted () {
        this.renderChart({
            labels: this.labels,
            datasets: [
              {
                backgroundColor: '#483D8B',
                data: this.chartData
              },
            ]
        }, 
        {
            responsive: true, 
            maintainAspectRatio: false,
            legend:{ 
                display: false
            }
        })
    },
}

</script>

<script>
import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,

  props:['chartData'],

    mounted () {
        this.renderChart({
            labels: ['Total Inative/Active'],
            datasets: [
              {
                label: 'Total Inactive',
                backgroundColor: '#483D8B',
                data: [this.chartData[1]]
              },
              {
                label: 'Total Active',
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
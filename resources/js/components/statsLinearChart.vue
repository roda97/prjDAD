<script>
import { Line } from 'vue-chartjs'

export default {
    extends: Line,

    props:['chartData', 'labels', 'color'],

    mounted () {
        this.renderChart({
            labels: this.labels,
            datasets: [                
                {                    
                    borderColor: this.color, 
                    pointBackgroundColor: this.color,
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
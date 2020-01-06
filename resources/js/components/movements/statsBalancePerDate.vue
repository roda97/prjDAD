<template>
    <div align="center">
        <apexcharts
            width="1000"
            type="area"
            :options="chartOptions"
            :series="series"
        ></apexcharts>
        <p></p>
    </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";

export default {
    name: "Chart",
    props: ["graphs"],
    data: function() {
        return {
            series: [{ 
                name: "Balance", 
                data: this.graphs 
            }],

            chartOptions: {
                chart: {
                    type: "area",
                    stacked: false,
                    height: 350,
                    zoom: {
                        type: "x",
                        enabled: true,
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset:
                                true |
                                '<img src="/static/icons/reset.png" width="20">'
                        },
                        autoSelected: "zoom"
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0
                },
                title: {
                    text: "Balance per Date:",
                    align: "left",
                    style: {
                        fontSize: "25px",
                        color: "#212529",
                        fontWeight: "bold"
                    }
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    },
                    title: {
                        text: "Balance"
                    }
                },
                xaxis: {
                    type: "datetime"
                },
                tooltip: {
                    shared: false,
                    y: {
                        formatter: function(val) {
                            return val.toFixed(0);
                        }
                    }
                }
            }
        };
    },
    components: {
        apexcharts: VueApexCharts
    }
};
</script>

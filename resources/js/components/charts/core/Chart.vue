<script>
import {
    Chart,
    initTE,
} from "tw-elements";

initTE({ Chart });

export default {
    name: "Chart",
    props: {
        data: {
            type: Object,
            required: true
        },
        horizontal: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            chartData: {}
        }
    },
    mounted() {
        this.chartData = this.data

        const theme = localStorage.getItem('theme')
        let color = "#3a3838"
        let label_color = "#fff"


        if (this.chartData.type === 'line') {
            this.chartData.data.datasets[0].backgroundColor = [
                'rgba(54, 162, 235, 0.5)',
            ]
            this.chartData.data.datasets[0].borderColor = 'rgb(54, 162, 235)'
            this.chartData.data.datasets[0].borderWidth = 1
            label_color = 'rgba(0,0,0,0.78)'
        } else {
            this.chartData.data.datasets[0].backgroundColor = [
                'rgba(35,54,180,0.98)',
            ]
        }

        if (theme === 'dark') {
            color = "#fff"
            label_color = '#fff'
        }

        const barChart = this.$refs.chart;
        const options = {
            dataLabelsPlugin: true,
            options: {
                scales: {
                    y: {
                        ticks: {
                            color: color,
                        },
                    },
                    x: {
                        ticks: {
                            color: color,
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: color,
                            usePointStyle: true
                        },
                    },
                    datalabels: {
                        color: label_color,
                        labels: {
                            title: {
                                font: {
                                    size: '15',
                                },
                            },
                        },
                    },
                },
            },
        };

        if (this.horizontal !== false) {
            options.options.indexAxis = "y";
        }

        new Chart(barChart, this.$props.data, options);
    }
}
</script>

<template>
    <div class="w-full">
        <canvas ref="chart"></canvas>
    </div>
</template>

<style scoped lang="scss">
.w-full {
    background-color: #F3F4F6;
}

.dark {
    .w-full {
        background-color: #1f2937;
    }
}
</style>

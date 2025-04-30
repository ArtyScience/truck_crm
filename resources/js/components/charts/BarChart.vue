<script>
import {
    Chart,
    initTE,
} from "tw-elements";

initTE({ Chart });

export default {
    name: "BarChart",
    data() {
        return {
            dataBar: {
                type: 'bar',
                data: {
                    labels: ['Monday', 'Tuesday' , 'Wednesday' , 'Thursday' , 'Friday' , 'Saturday' , 'Sunday '],
                    datasets: [
                        {
                            label: 'Traffic',
                            data: [2112, 2343, 2545, 3423, 2365, 1985, 987],
                        },
                    ],
                },
            }
        }
    },
    mounted() {
        const barChart = this.$refs["bar-chart"];
        const optionsChartOptionsExample = {
            dataLabelsPlugin: true,
            options: {
                scales: {
                    y: {
                        ticks: {
                            color: "#fff",
                        },
                    },
                    x: {
                        ticks: {
                            color: "#fff",
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: "#F3F3F3",
                        },
                    },
                    datalabels: {
                        color: '#F3F3F3',
                        labels: {
                            title: {
                                font: {
                                    size: '13',
                                },
                            },
                        },
                    },
                },
            },
        };
        new Chart(barChart, this.dataBar, optionsChartOptionsExample);
    }
}
</script>

<template>
    <div class="w-[calc(100%-100px)]">
        <canvas ref="bar-chart"></canvas>
    </div>
</template>

<style scoped lang="scss">

</style>

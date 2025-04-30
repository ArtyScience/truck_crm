<script>
import {
    Chart,
    initTE,
} from "tw-elements";

initTE({ Chart });

export default {
    name: "Polar",
    data() {
        return {
             dataPolar : {
                type: 'pie',
                data: {
                    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday '],
                    datasets: [
                        {
                            label: 'Sales',
                            data: [2112, 2343, 2545, 3423, 2365, 1985, 987],
                            backgroundColor: [
                                'rgba(63, 81, 181, 0.5)',
                                'rgba(77, 182, 172, 0.5)',
                                'rgba(66, 133, 244, 0.5)',
                                'rgba(156, 39, 176, 0.5)',
                                'rgba(233, 30, 99, 0.5)',
                                'rgba(66, 73, 244, 0.4)',
                                'rgba(66, 133, 244, 0.2)',
                            ],
                        },
                    ],
                },
                fontColor: 'rgb(239,230,230)'
            }
        }
    },
    mounted() {
        const chart = this.$refs.canvas;
        const optionsChartOptionsExample = {
            dataLabelsPlugin: true,
            options: {
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
        new Chart(chart, this.dataPolar, optionsChartOptionsExample);
    }
}
</script>

<template>
    <div class="w-[calc(100%-100px)]">
        <canvas ref="canvas"></canvas>
    </div>
</template>

<style scoped lang="scss">

</style>

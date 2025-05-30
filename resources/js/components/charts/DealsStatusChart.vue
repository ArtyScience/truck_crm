<script>
import ApexCharts from "apexcharts";

export default {
  name: "DealsStatusChart",
  props: {
    activity_per_region: Array,
  },
  mounted() {
    var options = {
      series: [
        {
          data: this.activity_per_region,
        }
      ],
      legend: {
        show: false,
      },
      chart: {
        height: 350,
        type: 'treemap',

      },
      title: {
        text: 'LEADS BY STATE',
        style: {
          color: '#ffffff',
          fontWeight: 'bold',
        }
      },
      plotOptions: {
        treemap: {
          distributed: true,
          enableShades: true,
        }
      },
      colors: ['#637691']
    };

    var chart = new ApexCharts(document.querySelector("#deals_chart"), options);
    chart.render();
  }
}
</script>

<template>
  <div id="deals_chart"></div>
</template>

<style scoped lang="scss">

</style>
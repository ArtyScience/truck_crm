<script>
import StatisticManager from "../components/charts/StatisticManager.vue";
import Button from "../components/core/Button.vue";
import TextInput from "../components/core/form/TextInput.vue";
import ApexCharts from 'apexcharts'
import TaskList from "../components/common/TaskList.vue";
import AudioCard from "../components/common/cards/AudioCard.vue";
import Title from "../components/core/Title.vue";
import DealsStatusChart from "../components/charts/DealsStatusChart.vue";

export default {
    name: "Dashboard",
    components: {
      DealsStatusChart,
        Title,
        AudioCard,
        TaskList,
        StatisticManager,
        Button,
        TextInput
    },
    data() {
        return {
            is_loading: false,
            text_data: ''
        }
    },
    mounted() {
        var options = {
            chart: {
                type: "bar",
                height: 400,
                foreColor: '#cecdcd'
            },
            series: [
                {
                    name: "Added Leads",
                    data: this.leads_week_statistic
                }
            ],
            xaxis: {
                categories: ["Mon", "Tues", "Wed", "Thurs", "May", "Sat", "Sun"]
            },
            stroke: {
                curve: "smooth",
                width: 2
            },
            colors: ["#0DB6D4"]
        };

        var chart = new ApexCharts(document.querySelector("#leads_week_statistics"), options);
        chart.render();

        var options = {
            chart: {
                type: "bar",
                height: 400,
                foreColor: '#cecdcd'
            },
            series: [
                {
                    name: "Added Deals",
                    data: this.deals_week_statistic
                }
            ],
            xaxis: {
                categories: ["Mon", "Tues", "Wed", "Thurs", "May", "Sat", "Sun"]
            },
            stroke: {
                curve: "smooth",
                width: 2
            },
            colors: ["#0891B2"]
        };

        var chart = new ApexCharts(document.querySelector("#deals_week_statistics"), options);
        chart.render();

        const status_list = this.status_list.map((status) => {
            return status.replace('_', ' ');
        })

        var options2 = {
            series: this.deals_list,
            labels: status_list,
            colors: ['#15803D', '#22C55E','#64748B','#475569',
                '#65A30D', '#06B6D4', '#0891B2', '#f6b632'],
            chart: {
                type: 'donut',
              foreColor: '#cecdcd'

            },
            legend: {
                show: true,
                showForSingleSeries: true,
                customLegendItems: status_list,
                markers: {
                    fillColors: ['#15803D', '#22C55E','#64748B','#475569',
                        '#65A30D', '#06B6D4', '#0891B2', '#f6b632']
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options2);
        chart.render();

        var options3 = {
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
                type: 'treemap'
            },
            title: {
                text: 'LEADS BY STATE',
            },
            colors: ['#1a5d44']
        };

        var chart = new ApexCharts(document.querySelector("#deals_chart"), options3);
        chart.render();
    },
    props: {
        activity_per_region: Object,
        sales_statistics: Object,
        tasks_list: Object,
        status_list: Object,
        deals_list: Object,
        leads_week_statistic: Object,
        deals_week_statistic: Object,
        call_logs: Object,
        user_role: String
    },
}
</script>

<template>
    <div class="dashboard_wrapper">
       <div class="charts ">
          <div class="flex">
              <div class="w-1/3 chart_wrapper">
                 <Title title="Deals by status" />
                <div id="chart2" class=""></div>
              </div>
              <div class="w-1/3 chart_wrapper">
                 <Title title="Leads week statistics" />
                  <div id="leads_week_statistics" class=""></div>
              </div>
              <div class="w-1/3 chart_wrapper">
                 <Title title="Deals week statistics" />
                  <div id="deals_week_statistics" class=""></div>
              </div>
          </div>
           <div class="flex w-full">
               <div  class="w-1/3 mt-5">
                 <Title title="COUNTRY/CITY ACTIVITY" />
                 <div id="deals_chart"></div>
               </div>
               <div class="w-1/3 chart_wrapper mt-5">
                  <Title title="Tasks list" />
                   <TaskList :tasks="tasks_list" />
               </div>
               <div v-if="user_role === 'ADMIN'" class="w-1/3 ">
                   <div class="text-center mt-5 text-cyan-700">
                     <Title title="CALLS" />
                   </div>
                   <div class="p-2">

                       <AudioCard :audio="call_logs" />

                   </div>
               </div>
           </div>
       </div>
    </div>
</template>

<style scoped lang="scss">
.apexcharts-text:hover {
  fill: black !important;
  color: #000 !important;
}

.dashboard_wrapper {
    .chart_wrapper {
        margin-left: 20px;
        h1 {
            text-transform: uppercase;
            font-weight: bold;
        }
      #leads_week_statistics {
        //color: white !important;
      }
    }
}
</style>

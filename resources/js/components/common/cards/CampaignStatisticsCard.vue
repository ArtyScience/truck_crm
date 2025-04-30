<script>
import ApexCharts from 'apexcharts'
import {RefreshIcon} from "@heroicons/vue/solid";

const chart_settings = {
    chart: {
        type: 'bar'
    },
    series: []
};

export default {
    components: {RefreshIcon},
    props: {
        campaign_id: Number,
    },
    mounted() {
        this.is_loading = true
        try {
            this.loadStatistics()
        }
        catch (error) {
            return true;
        }
    },
    name: "CampaignStatisticsCard",
    data() {
        return {
            settings: {},
            statistics: [],
            is_loading: false,
        }
    },
    methods: {
        async loadStatistics() {
            try {
                this.is_loading = true;
                await axios.get('api/v1/campaign/get-statistics/'
                    + this.campaign_id
                ).then((r) => {
                    this.statistics = r.data.campaign_statistics
                    this.is_loading = false;
                }).catch((e) => {})

                this.settings = chart_settings;
                this.settings.series = [{ data: this.statistics }];

                const chartElement = document.querySelector(`#campaign_chart_${this.campaign_id}`);
                if (chartElement) {
                    const chart = new ApexCharts(chartElement, this.settings);
                    chart.render();
                }
            } catch (error) {
                return true;
            }
        },
    }
}
</script>

<template>
    <div class="preloading_wrapper">
        <div
            v-if="!is_loading"
            class="chart_wrapper">
            <div
                :id="'campaign_chart_' + this.campaign_id"></div>
        </div>
        <div v-else>
            <RefreshIcon class="btn_icon w-3 mr-2 animate-spin"></RefreshIcon>
        </div>
    </div>
</template>

<style scoped lang="scss">
.preloading_wrapper {
    position: relative;
    .chart_wrapper {
        max-width: 100%;
        min-height: 135px;
    }
    .btn_icon {
        width: 20px;
        color: lightgrey;
        position: absolute;
        top: 60px;
        left: 90px;
    }
}
</style>

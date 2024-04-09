<template>
  <e-charts
    ref="line"
    autoresize
    :options="line"
    theme="theme-color"
    auto-resize
  />
</template>

<script>
import ECharts from 'vue-echarts'
import { $themeColors } from '@themeConfig'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'
import 'echarts/lib/chart/line'
import theme from './theme.json'

ECharts.registerTheme('ovilia-green', theme)

const chartColors = {
  primaryColorShade: '#836AF9',
  yellowColor: '#ffe800',
  successColorShade: '#28dac6',
  warningColorShade: '#ffe802',
  warningLightColor: '#FDAC34',
  infoColorShade: '#299AFF',
  greyColor: '#4F5D70',
  blueColor: '#2c9aff',
  blueLightColor: '#84D0FF',
  greyLightColor: '#EDF1F4',
  tooltipShadow: 'rgba(0, 0, 0, 0.25)',
  lineChartPrimary: '#666ee8',
  lineChartDanger: '#ff4961',
  labelColor: '#6e6b7b',
  bluestar_blue_Color: '#2c338e',
  bluestar_orange_Color: '#f8a240',
  bluestar_green_Color: '#8cc540',
  grid_line_color: 'rgba(200, 200, 200, 0.2)',
}

export default {
  components: {
    ECharts,
  },
  props: {
    optionData: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      line: {
        // Make gradient line here
        visualMap: [{
          show: true,
          type: 'continuous',
          min: 0,
          max: 400,
        }],
        grid: {
          width: '96%',
          left: '30px',
          top: '10px',
          show: false,
        },
        tooltip: {
          trigger: 'axis',
        },
        xAxis: [{
          boundaryGap: false,
          data: this.optionData.xAxisData,
        }],
        yAxis: {
          type: 'value',
          splitLine: { show: true },
          ticks: {
              fontColor: chartColors.labelColor,
          },
        },
        chartOptions: {
          type: 'line',
          height: 350,
          stacked: true,
          // stackType: '100%'
        },
        series: [
          {
            type: 'line',
            name: 'Air',
            showSymbol: false,
            color: $themeColors.success,
            data: this.optionData.series,
          },
          {
            type: 'line',
            name: 'Railway',
            color: $themeColors.primary,
            showSymbol: false,
            data: ['500', '220', '290', '160', '200', '10', '10', '0', '130', '150', '160', '200', '150', '200', '300'],
          }
        ],
        // colors: [$themeColors.primary, $themeColors.secondary, $themeColors.danger, $themeColors.warning, chartColors.bar.series3, chartColors.bar.series2, chartColors.bar.series1],
      },
    }
  },
}
</script>

<style>
.echarts {
  width: 100% !important;
}
</style>

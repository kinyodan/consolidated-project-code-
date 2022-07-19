<template>
  <v-card class="rounded-lg pa-4 aptitudes-chart">
    <div class="chart full-width">
      <v-card-subtitle class="font-weight-bold text-h6 chart__title">Aptitudes</v-card-subtitle>
      <v-chart :option="option" autoresize/>
    </div>
  </v-card>
</template>

<script>
import {use} from "echarts/core";
import {SVGRenderer} from "echarts/renderers";
import {BarChart} from "echarts/charts";
import {
  GridComponent,
  TooltipComponent,
} from "echarts/components";
import VChart, {THEME_KEY} from "vue-echarts";

use([
  SVGRenderer,
  BarChart,
  GridComponent,
  TooltipComponent
]);

export default {
  name: "AptitudesChart",
  components: {
    VChart,
  },

  data() {
    return {
      option: {
        title: {
          show: false
        },
        tooltip: {
          trigger: "item",
          formatter: "{b} : <strong>{c}</strong>",
        },
        color: '#C2185B',
        xAxis: {
          type: 'category',
          axisLabel: { interval: 0, rotate: 30 },
          data: ['Acuteness', 'Critical Dissection', 'Numerical', 'Spatial', 'Verbal']
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            type: "bar",
            data: [120, 200, 150, 80, 70],
          },
        ],
      },
    };
  },
};
</script>

<style scoped>

</style>

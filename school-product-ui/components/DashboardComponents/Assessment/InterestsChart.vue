<template>
  <v-card class="rounded-lg pa-4 interests-chart">
    <div class="chart full-width">
      <v-card-subtitle class="font-weight-bold text-h6 chart__title">Interests</v-card-subtitle>
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
} from "echarts/components";
import VChart, {THEME_KEY} from "vue-echarts";

use([
  SVGRenderer,
  BarChart,
  GridComponent
]);

export default {
  name: "InterestsChart",
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
        color: '#00897B',
        xAxis: {
          type: 'category',
          axisLabel: { interval: 0, rotate: 30 },
          data: ['Realistic', 'Artistic', 'Investigative', 'Social', 'Enterprising', 'Conventional']
        },
        yAxis: {
          type: 'value'
        },
        series: [
          {
            type: "bar",
            data: [120, 200, 150, 80, 70, 50],
          },
        ],
      },
    };
  },
};
</script>

<style scoped>

</style>

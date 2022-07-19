<template>
  <v-card class="rounded-lg pa-4">
    <div class="chart">
      <div>
        <v-card-subtitle class="font-weight-bold text-h6 chart__title text-center">Licenses Purchased</v-card-subtitle>
        <v-chart :option="option" autoresize/>
      </div>
      <ul class="chart__legend">
        <li v-for="(legend, index) in option.legend.data" @click="toggleLegendItem(legend)">
          <div class="color-pill" :style="{background: option.color[index]}"></div>
          {{ legend }}
        </li>
      </ul>
      <div class="chart__total">
        <v-card-title class="text-h4 text-sm-h2 pa-0 font-weight-bold justify-center">2,562</v-card-title>
        <v-card-actions class="pt-4 pb-0 btn-view-details">
          <v-btn color="primary"
                 rounded
                 small
                 @click="showDetailsModal"
          >
            View Details
          </v-btn>
        </v-card-actions>
      </div>
    </div>
  </v-card>
</template>

<script>
import {use} from "echarts/core";
import {SVGRenderer} from "echarts/renderers";
import {PieChart} from "echarts/charts";
import {
  TitleComponent,
  TooltipComponent,
  LegendComponent,
} from "echarts/components";
import VChart, {THEME_KEY} from "vue-echarts";

use([
  SVGRenderer,
  PieChart,
  TitleComponent,
  TooltipComponent,
  LegendComponent,
]);

export default {
  name: "UsageChart",
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
          formatter: "{a} <br/>{b} : <strong>{c} ({d}%)</strong>",
        },
        color: ['#C5CAE9', '#7986CB', '#3949AB', '#283593', '#8C9EFF'],
        legend: {
          orient: 'vertical',
          left: "left",
          show: false,
          data: [
            "Form 1",
            "Form 2",
            "Form 3",
            "Form 4",
            "Past Students",
          ],
        },
        series: [
          {
            name: "Licenses Purchased",
            type: "pie",
            labelLine: false,
            radius: "92%",
            center: ["50%", "50%"],
            data: [
              {value: 135, name: "Form 1"},
              {value: 234, name: "Form 2"},
              {value: 310, name: "Form 3"},
              {value: 335, name: "Form 4"},
              {value: 1548, name: "Past Students"},
            ],
            emphasis: {
              itemStyle: {
                shadowBlur: 10,
                shadowOffsetX: 0,
                shadowColor: "rgba(0, 0, 0, 0.5)",
              },
            },
          },
        ],
      },
    };
  },
  methods: {
    toggleLegendItem(legend) {
      // this.$nextTick(() => {
      //   if (this.filterSelectAllStatuses) {
      //     this.filterSelectedStatuses = []
      //   } else {
      //     this.filterSelectedStatuses = this.filter_statuses.slice()
      //   }
      // })
    },
    showDetailsModal() {
      $('#show_licenses_details').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
  },
};
</script>

<style scoped>

</style>

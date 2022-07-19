<template>
  <v-card class="rounded-lg pa-4 career-pathways-chart">
    <div class="chart align-center">
      <div>
        <v-card-subtitle class="font-weight-bold text-h6 chart__title text-center">Most Popular Ideal Career Pathways for your
          students</v-card-subtitle>
        <v-chart :option="option" autoresize/>
      </div>

      <ul class="chart__legend">
        <li v-for="(legend, index) in option.legend.data" @click="toggleLegendItem(legend)">
          <div class="color-pill" :style="{background: option.color[index]}"></div>
          {{ legend }}
        </li>
      </ul>
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
import AssessmentAnalyticsFilter from "@/components/DashboardComponents/Assessment/AssessmentAnalyticsFilter";

use([
  SVGRenderer,
  PieChart,
  TitleComponent,
  TooltipComponent,
  LegendComponent,
]);

export default {
  name: "CareerPathwaysChart",
  components: {
    AssessmentAnalyticsFilter,
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
        color: ['#FF6D00', '#FF9100', '#FFAB40', '#FFD180', '#E65100', '#EF6C00', '#F57C00', '#FB8C00', '#FFA726', '#FFB74D', '#FFCC80', '#FFE0B2', '#FFECB3', '#FFE082', '#FFD54F'],
        legend: {
          orient: 'vertical',
          left: "left",
          show: false,
          data: [
            "Agriculture & Natural Resource",
            "Commerce & Accounts",
            "Defense & Military",
            "Designing & Art",
            "Education & Training",
            "Media & Entertainment",
            "Management & Marketing",
            "Law and Order",
            "Public Administration & Government",
            "Humanistic Studies",
            "Health Science",
            "Engineering and Technology",
            "Information Technology",
            "Science & Research",
            "Tourism & Hospitality",
          ],
        },
        series: [
          {
            name: "Career Pathways",
            type: "pie",
            labelLine: false,
            radius: "92%",
            center: ["50%", "50%"],
            data: [
              {value: 35, name: "Agriculture & Natural Resource"},
              {value: 43, name: "Commerce & Accounts"},
              {value: 62, name: "Defense & Military"},
              {value: 120, name: "Designing & Art"},
              {value: 59, name: "Education & Training"},
              {value: 83, name: "Media & Entertainment"},
              {value: 92, name: "Management & Marketing"},
              {value: 24, name: "Law and Order"},
              {value: 12, name: "Public Administration & Government"},
              {value: 5, name: "Humanistic Studies"},
              {value: 66, name: "Health Science"},
              {value: 134, name: "Engineering and Technology"},
              {value: 172, name: "Information Technology"},
              {value: 76, name: "Science & Research"},
              {value: 33, name: "Tourism & Hospitality"},
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

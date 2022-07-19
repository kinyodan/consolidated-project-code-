<template>
  <div>
    <apexchart type="pie" width="600" height="500" :options="plotOptions" :series="series"></apexchart>
  </div>
</template>
<script>
export default {
  name: "PieChart",
  data(){
    return{
      series: [20, 15, 20, 15, 30, 10, 5, 5, 10, 10],
      plotOptions: {
        labels: ['Service', 'Stability', 'Variety', 'Leadership', 'Respect', 'Challenge', 'Interest', 'Freedom', 'Leisure', 'Money'],
      },
    }
  },
}
</script>

<style>
.apexcharts-legend.position-bottom.apexcharts-align-left, .apexcharts-legend.position-top.apexcharts-align-left, .apexcharts-legend.position-right, .apexcharts-legend.position-left{
  text-align: left;
}
</style>

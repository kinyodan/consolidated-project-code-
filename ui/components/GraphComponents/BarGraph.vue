<template>
  <div>
    <apexchart type="bar" width="100%" height="500" :options="plotOptions" :series="series"></apexchart>
  </div>
</template>
<script>
export default {
  name: "BarGraph",
  props:['series','plotOptions'],
  data() {
    return {
      /*series: [{
        data: [44, 55, 41, 17, 15],
      }],
      plotOptions: {
        xaxis: {
          categories: ['Spatial', 'Acuteness', 'Verbal', 'Numerical', 'Critical Dissection']
        },
      },*/
    }
  },
}
</script>

<style scoped>

</style>

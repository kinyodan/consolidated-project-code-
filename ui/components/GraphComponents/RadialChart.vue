<template>
  <div>
    <apexchart type="radialBar" width="600" height="500" :options="plotOptions" :series="series"></apexchart>
  </div>
</template>
<script>
export default {
  name: "RadialChart",
  data(){
    return{
      series: [70],
      plotOptions: {
        labels: ['Aptitude Score'],
      },
    }
  },
}
</script>

<style scoped>

</style>

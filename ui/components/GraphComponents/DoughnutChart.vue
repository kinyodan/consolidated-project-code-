<template>
  <div>
    <apexchart type="donut" width="600" height="500" :options="plotOptions" :series="series"></apexchart>
  </div>
</template>
<script>
export default {
  name: "DoughnutChart",
  props:['series','plotOptions'],
  data(){
    return{
    }
  },
}
</script>

<style scoped>

</style>

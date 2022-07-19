<template>
  <div>
    <v-card-text><p>Get data on platform usage and detailed reports of students through their journey of being onboarded, doing the
      assessment, making applications and enrolling into their institutions</p></v-card-text>
    <div class="analytics">
      <usage-chart></usage-chart>
      <div class="analytics__stats">
        <assessments></assessments>
        <applications></applications>
        <enrollments></enrollments>
      </div>
    </div>
  </div>
</template>

<script>
import UsageChart from "@/components/DashboardComponents/Usage/UsageChart";
import Assessments from "@/components/DashboardComponents/Usage/Assessments";
import Applications from "@/components/DashboardComponents/Usage/Applications";
import Enrollments from "@/components/DashboardComponents/Usage/Enrollments";

export default {
  name: "UsageAnalytics",
  components: {Enrollments, Applications, Assessments, UsageChart}
}
</script>

<style scoped>

</style>

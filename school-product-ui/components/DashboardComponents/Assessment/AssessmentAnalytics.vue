<template>
  <div>
    <v-card-text><p>See an aggregate report breakdown from all students who have taken the Career Match Psychometric
      Assessment to better understand your students' profiles</p>
    </v-card-text>
    <div class="charts-layout">
      <div class="span-col-three">
        <assessment-analytics-filter></assessment-analytics-filter>
      </div>
      <career-pathways-chart></career-pathways-chart>
      <aptitudes-chart></aptitudes-chart>
      <interests-chart></interests-chart>
      <assessments-details></assessments-details>
    </div>
  </div>
</template>

<script>
import CareerPathwaysChart from "@/components/DashboardComponents/Assessment/CareerPathwaysChart";
import AssessmentAnalyticsFilter from "@/components/DashboardComponents/Assessment/AssessmentAnalyticsFilter";
import AptitudesChart from "@/components/DashboardComponents/Assessment/AptitudesChart";
import InterestsChart from "@/components/DashboardComponents/Assessment/InterestsChart";
import AssessmentsDetails from "@/components/DashboardComponents/Usage/AssessmentsDetails";

export default {
  name: "AssessmentAnalytics",
  components: {
    AssessmentsDetails,
    InterestsChart,
    AptitudesChart, AssessmentAnalyticsFilter, CareerPathwaysChart
  }
}
</script>

<style scoped>

</style>

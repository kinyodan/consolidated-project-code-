<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Hi {{ displayName }}! Welcome to the Craydel FutureShapers
        Platform Admin Center.</h2></div>
      <div class="app_header_right main-filter">
        <div class="styled-select">
          <client-only>
            <v-md-date-range-picker opens="right"></v-md-date-range-picker>
          </client-only>
        </div>
      </div>
    </div>
    <div class="dash_content_body">
      <v-app class="dashboard-app">
        <div class="dashboard-grid">
          <general-stats></general-stats>
          <v-tabs  class="transparent-bg">
            <v-tab>Usage Analytics</v-tab>
            <v-tab>Assessment Analytics</v-tab>
            <v-tab-item><usage-analytics></usage-analytics></v-tab-item>
            <v-tab-item><assessment-analytics></assessment-analytics></v-tab-item>
          </v-tabs>
        </div>
        <licenses-details></licenses-details>
        <licenses-filter></licenses-filter>
        <assessments-details></assessments-details>
        <assessments-filter></assessments-filter>
        <applications-details></applications-details>
        <applications-filter></applications-filter>
        <enrollments-details></enrollments-details>
        <enrollments-filter></enrollments-filter>
        <buy-licenses></buy-licenses>
      </v-app>
    </div>
  </div>
</template>

<script>
import jwt_decode from "jwt-decode";
import GeneralStats from "@/components/DashboardComponents/GeneralStats";
import UsageAnalytics from "@/components/DashboardComponents/Usage/UsageAnalytics";
import AssessmentsDetails from "@/components/DashboardComponents/Usage/AssessmentsDetails";
import AssessmentsFilter from "@/components/DashboardComponents/Usage/AssessmentsFilter";
import ApplicationsDetails from "@/components/DashboardComponents/Usage/ApplicationsDetails";
import ApplicationsFilter from "@/components/DashboardComponents/Usage/ApplicationsFilter";
import EnrollmentsDetails from "@/components/DashboardComponents/Usage/EnrollmentsDetails";
import EnrollmentsFilter from "@/components/DashboardComponents/Usage/EnrollmentsFilter";
import LicensesDetails from "@/components/DashboardComponents/Usage/LicensesDetails";
import LicensesFilter from "@/components/DashboardComponents/Usage/LicensesFilter";
import BuyLicenses from "@/components/BillingComponents/BuyLicenses";
import AssessmentAnalytics from "@/components/DashboardComponents/Assessment/AssessmentAnalytics";

export default {
  name: 'IndexPage',
  components: {
    AssessmentAnalytics,
    BuyLicenses,
    LicensesFilter,
    LicensesDetails,
    EnrollmentsFilter,
    EnrollmentsDetails,
    ApplicationsFilter,
    ApplicationsDetails, AssessmentsFilter, AssessmentsDetails, UsageAnalytics, GeneralStats
  },
  middleware: ['auth'],
  computed: {
    displayName() {
      try {
        let token = this.$cookies.get('_token');
        if (token) {
          let user = jwt_decode(token);
          if (user) {
            return user.first_name
          } else {
            return ""
          }
        } else {
          return ""
        }
      } catch (e) {
        return ""
      }
    },
  },
}
</script>

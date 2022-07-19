<template>
  <div class="general-stats">
    <v-card color="light-blue accent-2" class="rounded-lg">
      <div class="d-flex flex-no-wrap justify-space-between align-center">
        <v-card-subtitle>Available Licenses</v-card-subtitle>
        <div class="pa-2">
          <v-btn icon>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </div>
      </div>

      <v-card-title class="text-h4 py-0 font-weight-bold">4</v-card-title>
      <v-card-actions class="pa-4">
        <v-btn color="primary"
               rounded
               small
               @click="showBuyLicensesModal"
        >
          Buy More Licenses
        </v-btn>
      </v-card-actions>
    </v-card>

    <v-card color="cyan accent-2" class="rounded-lg">
      <div class="d-flex flex-no-wrap justify-space-between align-center">
        <v-card-subtitle>Users Onboarded</v-card-subtitle>
        <div class="pa-2">
          <v-btn icon>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </div>
      </div>

      <v-card-title class="text-h4 py-0 font-weight-bold">23</v-card-title>
      <v-card-actions class="pa-4">
        <v-btn color="primary"
               rounded
               small
               href="/manage-users"
               nuxt
        >
          Manage Users
        </v-btn>
      </v-card-actions>
    </v-card>

    <v-card color="teal accent-2" class="rounded-lg">
      <div class="d-flex flex-no-wrap justify-space-between align-center">
        <v-card-subtitle>Students Onboarded</v-card-subtitle>
        <div class="pa-2">
          <v-btn icon>
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </div>
      </div>

      <v-card-title class="text-h4 py-0 font-weight-bold">1,400</v-card-title>
      <v-card-actions class="pa-4">
        <v-btn color="primary"
               rounded
               small
               href="/manage-students"
               nuxt
        >
          Manage Students
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "GeneralStats",
  methods: {
    showBuyLicensesModal() {
      $('#buy_licenses').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
  },
}
</script>

<style scoped>

</style>

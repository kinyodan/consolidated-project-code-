<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Billing</h2></div>
      <div class="app_header_right">
        <a href="" class="add_new_user" data-toggle="modal" @click.prevent="showBuyLicensesModal">Buy More Licenses</a>
      </div>
    </div>
    <div class="dash_content_body">
      <v-app class="dashboard-app">
        <div class="dashboard-grid">
          <div class="general-stats">
            <v-card color="cyan accent-2 pb-2" class="rounded-lg">
              <div class="d-flex flex-no-wrap justify-space-between align-center">
                <v-card-subtitle>Total Licenses Purchased</v-card-subtitle>
              </div>

              <v-card-title class="text-h4 py-0 font-weight-bold">128</v-card-title>
            </v-card>
            <v-card color="light-blue accent-2 pb-2" class="rounded-lg">
              <div class="d-flex flex-no-wrap justify-space-between align-center">
                <v-card-subtitle>Available Licenses</v-card-subtitle>
              </div>

              <v-card-title class="text-h4 py-0 font-weight-bold">4</v-card-title>
            </v-card>
          </div>
          <transactions-table></transactions-table>
        </div>
        <buy-licenses></buy-licenses>
      </v-app>
    </div>
  </div>
</template>

<script>
import BuyLicenses from "@/components/BillingComponents/BuyLicenses";
import TransactionsTable from "@/components/BillingComponents/TransactionsTable";

export default {
  name: "billing",
  components: {TransactionsTable, BuyLicenses},
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

<template>
  <v-card color="amber lighten-5" class="rounded-lg">
    <div class="d-flex flex-no-wrap justify-space-between align-center">
      <v-card-subtitle class="font-weight-bold text-h6">Assessments Done</v-card-subtitle>
    </div>
    <v-card-title class="text-h4 text-sm-h2 py-0 font-weight-bold">213</v-card-title>
    <v-card-actions class="pa-4">
      <v-btn color="primary"
             rounded
             small
             @click="showDetailsModal"
      >
        View Details
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name: "Assessments",
  methods: {
    showDetailsModal() {
      $('#show_assessments_details').modal({
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

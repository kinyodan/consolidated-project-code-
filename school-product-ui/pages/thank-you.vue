<template>
  <div class="thank-you">
    <v-app>
      <v-card max-width="600" class="text-center pa-6 rounded-lg">
        <v-icon color="green">
          mdi-check-decagram
        </v-icon>
        <v-card-title class="text-h4 justify-center font-weight-bold">Thank you</v-card-title>
        <v-card-text class="text-h6 justify-center">You have successfully paid for the licenses</v-card-text>
        <v-card-actions class="justify-center">
          <a href="/" class="add_new_user">Back to Dashboard</a>
        </v-card-actions>
      </v-card>
    </v-app>
  </div>
</template>

<script>
export default {
  name: "thank-you",
  middleware:['auth'],
}
</script>

<style scoped>

</style>

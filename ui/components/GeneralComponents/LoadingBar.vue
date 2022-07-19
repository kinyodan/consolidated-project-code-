<template>
  <div v-if="loading" class="page_loading">
    <div class="preload_inner">
      <img src="~/assets/craydel.svg" alt="Craydel">
      <div class="loading_spinner"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "LoadingBar",
  data: () => ({
    loading: false
  }),
  methods: {
    start() {
      this.loading = true
    },
    finish() {
      this.loading = false
    }
  }
}
</script>

<style scoped>
</style>

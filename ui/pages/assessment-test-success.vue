<template>
  <div class="success_page_main">
    <div class="success_message_excerpt">
      <img src="~/assets/images/payment_success.svg" alt="Assessment success">
      <h4>Congratulations!</h4>
      <p>
        You have completed your assessment. We will generate you report shortly. Click on the home link to access it.
      </p>
      <div class="action_buttons">
        <a :href="`/?profile=${activeSlot}`" class="page_cta inverse">Back to home</a>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: 'assessment-test-success',
  middleware: 'auth',
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    }),
  },
  mounted() {
    if (!this.activeSlot) {
      this.$router.push('/my-packages')
    }
  }
}

</script>

<style>

</style>

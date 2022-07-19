<template>
  <section class="congrats">
    <div class="congrats__memoji">
      <img src="/images/memojis/lady.svg" width="169" height="169" alt="Memoji"/>
    </div>
    <div class="congrats__content">
      <div class="congrats__header">
        <h3 class="title">Congratulations {{ profile.first_name }}!</h3>
        <div class="confetti">
          <img src="/images/memojis/confetti1.svg" width="50" height="50" alt="Confetti Emoji"/>
        </div>
        <div class="confetti">
          <img src="/images/memojis/confetti2.svg" width="50" height="50" alt="Confetti Emoji"/>
        </div>
      </div>
      <p class="congrats__summary">You have taken the 1st step towards getting the confidence in selecting your higher education & career path!
        Your future is bright! And this assessment will take you closer to owning it!
      </p>
    </div>
  </section>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "OnboardingCongrats",
  computed:{
    ...mapState('profile',{
      profile: state => state.profile,
    })
  },
}
</script>

<style scoped>

</style>

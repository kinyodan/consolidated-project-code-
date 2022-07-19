<template>
  <section class="free-account-links">
    <!-- free-account-links-header -->
    <div class="recommendations-header">
      <h2 class="recommendations-header__title">You are currently on Craydel's free account. As part of the free account
        you can:</h2>
    </div>
    <!-- free-account-links-header end -->

    <ul class="free-account-links__listing">
      <li>
        <a :href="setSubjectChoiceURL" class="link">Explore Subject Requirements</a>
      </li>
      <li>
        <a href="https://craydel.com/top-courses" target="_blank" class="link">Explore Career Pathways</a>
      </li>
      <li>
        <a href="https://craydel.com/courses" target="_blank" class="link">Explore Courses</a>
      </li>
      <li>
        <a href="https://craydel.com/institutions" target="_blank" class="link">Explore Universities</a>
      </li>
    </ul>

  </section>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "FreeAccountLinks",
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    }),
    ...mapState('profile',{
      slots: state => state.slots
    }),
    setSubjectChoiceURL(){
      if(this.slots.length > 0){
        if(this.activeSlot){
          return '/subject-choice?q=true&profile='+this.activeSlot
        }else{
          return '/my-packages'
        }
      }else{
        return '/subject-choice?q=true'
      }
    },
  }
}
</script>

<style scoped>

</style>

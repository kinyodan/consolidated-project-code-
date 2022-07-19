<template>
<div>
  <onboarding-congrats></onboarding-congrats>
  <assessment-sections></assessment-sections>
  <speech-bubble></speech-bubble>
  <div class="quiz_action_btns">
    <a :href="assessment_url" class="quiz_next_btn">Start your assessment</a>
    <!--<a :href="assessment_url" class="quiz_next_btn">Start</a>-->
  </div>
</div>
</template>

<script>
import OnboardingCongrats from "@/components/OnboardingComponents/OnboardingCongrats";
import AssessmentSections from "@/components/OnboardingComponents/AssessmentSections";
import SpeechBubble from "@/components/OnboardingComponents/SpeechBubble";
import {mapState} from "vuex";
export default {
  name: "onboarding",
  components: {SpeechBubble, AssessmentSections, OnboardingCongrats},
  data() {
    return {
      slot: this.$route.query.profile,
      assessment_url: `assessment-test?profile=` + this.$route.query.profile
    }
  }
}
</script>

<style scoped>

</style>

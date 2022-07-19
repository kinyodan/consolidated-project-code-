<template>
  <section class="recommendations-section">
    <div class="recommendations-header">
<!--      <h2 class="recommendations-header__title">My Recommended Subject Requirements</h2>-->
<!--      <p>You currently have no recommended subject requirements.</p>-->

<!--      <p>Select your ideal career pathway to see its subject requirements</p>-->
    </div>
    <!-- recommendations-header end -->


    <div class="text_center">
     <!-- <p>Complete the Career Match Psychometric Assessment to unlock your ideal subject choices</p>-->
<!--      <p >&lt;!&ndash;v-if="profile.slots.attempts.is_complete==0"&ndash;&gt;-->
<!--       To unlock personalised recommendations, please complete your assessment.-->
<!--      </p>-->

      <!--<div v-if="profile.slots.length<0"></div>
      <form  v-else id="frm_guest_checkout_form" @submit.prevent="submitCheckoutForm">
        <div class="notification error" v-if="submitError">
          <template v-if="submitErrorText">{{ submitErrorText }}</template>
        </div>
        <div class="form_group procceed_to_checkout_btn">
          <button type="submit" class="page_cta">Upgrade to Premium</button>
        </div>
      </form>-->

     <!--  <div v-if="profile.slots<0 && profile.slots==1" class="">
         <a href="#" class="page_cta" v-if="profile.slots.attempts.length<0 && profile.is_complete==0">Start Assessment</a>
          <a href="#" class="page_cta" v-if="profile.slots.attempts.length==0">Compelete Your Assessment</a>
          <a href="#" class="page_cta" v-if="profile.slots.attempts.is_complete>1">View Assessment</a>
      </div> -->
 <!-- <div v-if="profile.slots<0 && profile.slots<1" class="page_cta">

          <a href="#" class="page_cta" v-if="profile.attempt.length<0 && profile.is_complete==0">Start Assessment</a>
          <a href="#" class="page_cta" v-if="profile.attempt.length==0">Compelete Your Assessment</a>
          <a href="#" class="page_cta" v-if="profile.attempt.is_complete>1">View Assessment</a>
      </div>-->

    </div>
  </section>
</template>

<script>
import CourseCategories from "@/components/SubjectChoice/CourseCategories";
import CurriculumSelector from "@/components/SubjectChoice/CurriculumSelector";

export default {
  name: "RecommendedCareerPathways",
  components: {CurriculumSelector, CourseCategories}
}
</script>

<style scoped>

</style>

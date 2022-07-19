<template>
  <div>
    <section class="progress-card">
      <div class="progress-card__pic">
        <img src="/images/memojis/lady.svg" width="163" height="163" alt="Lady memoji thumbs up"/>
      </div>
      <div class="progress-card__text">
        <h2 class="progress-card__title">My Career Match Assessment</h2>
        <p>Congratulations Anoushka on completing your Career Match Assessment</p>
      </div>

      <div class="progress-card__bar-wrapper complete">
        <div class="progress-card__bar" style="width:13%;"></div>
      </div>

      <div class="text_center progress-card__btn-wrapper">
        <a href="#" class="page_cta">Take The Assessment</a>
      </div>
    </section>

    <br/><br/><br/> 

    <!-- Show this when assessment is locked -->
    <section class="subject-choice-header">
      <div class="subject-choice-header__pic">
        <img src="/images/memojis/lady.svg" width="163" height="163" alt="Lady memoji thumbs up"/>
      </div>

      <div class="subject-choice-header__text">
        <h1 class="subject-choice-header__title">My Career Match Assessment</h1>
        <p>You currently can not take the Career Match Assessment.
          Take the assessment to get your top 3 ideal career options.</p>
      </div>
      <a href="#" class="page_cta">Upgrade to Premium</a>
    </section>

    <template>
      <!--recommendations-->
      <div class="recommendation-cards">
        <div class="course-card">
          <div class="course-card__pic">
            <img src="~/assets/images/courses/education.svg" width="119" height="119" alt="Career Pathway"/>
          </div>
          <h3 class="course-card__title">Education</h3>
          <div class="course-card__links">
            <a href="javascript:void(0)">Explore Subject Requirements</a>
            <a href="#" target="_blank">Explore Career Pathways</a>
            <a href="#" target="_blank">Explore Courses</a>
          </div>
        </div>
      </div>
      <!--recommendations end -->
    </template>

    <applications-card></applications-card>
  </div>
</template>

<script>

import ApplicationsCard from "@/components/DashboardComponents/ApplicationsCard";
export default {
  name: "dashboard",
  components: {ApplicationsCard},
}
</script>

<style scoped>

</style>

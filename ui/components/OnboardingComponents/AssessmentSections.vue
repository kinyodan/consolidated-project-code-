<template>
  <section class="assessment-sections">
    <p class="assessment-sections__summary">The assessment has 182 questions spread across 7 sections.</p>
    <!-- assessment section cards -->
    <div class="assessment-sections__cards">
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/assessment-section-pic.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 1</span>
          <h3 class="title-name">Self Identification</h3>
          <p class="info">This section looks at your behavior and how you react towards different scenarios, in an effort to understand what defines you as an individual.</p>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/work-situations.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 2</span>
          <h3 class="title-name">Work Situations</h3>
          <p class="info">This section asks you to imagine the future of your career and select broad work situations that are appealing to you.</p>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/incomplete-sequence.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 3</span>
          <h3 class="title-name">Incomplete Sequence</h3>
          <p class="info">This section consists of engaging visual puzzles which where you have to select the option you think completes the sequence.</p>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/identical-codes.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 4</span>
          <h3 class="title-name">Identical Codes</h3>
          <p class="info">This section requires you to keenly look at multiple codes in order to identify the identical ones.</p>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/logical-scenarios.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 5</span>
          <h3 class="title-name">Logical scenarios</h3>
          <p class="info">This section involves deductive reasoning, arithmetic, and comprehension-based questions that assess various aptitudes.</p>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/important-factors.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 6</span>
          <h3 class="title-name">Importance Factors</h3>
          <p class="info">This section considers your top priorities and motivations against different factors related to your best suited career.</p>
        </div>
      </div>
      <div class="section-card">
        <div class="section-card__pic">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/situational-exploration.jpg" width="230" height="212" alt="Section Pic" />
        </div>
        <div class="section-card__content">
          <span class="title">Section 7</span>
          <h3 class="title-name">Situational Exploration</h3>
          <p class="info">This section takes you through an exercise to understand your personality through an array of questions you are required to answer intuitively.</p>
        </div>
      </div>
    </div>
    <!-- users taken assessment -->
    <div class="users-taken-assessment">
      <div class="avatars">
        <div class="avatar-pic">
          <img src="~/assets/images/avatars/avatar-1.png" width="39" height="39" alt="Avatar"/>
        </div>
        <div class="avatar-pic">
          <img src="~/assets/images/avatars/avatar-2.png" width="39" height="39" alt="Avatar"/>
        </div>
        <div class="avatar-pic">
          <img src="~/assets/images/avatars/avatar-3.png" width="39" height="39" alt="Avatar"/>
        </div>
        <div class="avatar-pic">
          <img src="~/assets/images/avatars/avatar-4.png" width="39" height="39" alt="Avatar"/>
        </div>
      </div>

      <span class="counter-statement">
          <span class="counter">2,324</span> have taken the Psychometric Assessment
        </span>
    </div>
    <!-- users taken assessment end -->
  </section>
</template>

<script>
export default {
  name: "AssessmentSections"
}
</script>

<style scoped>

</style>

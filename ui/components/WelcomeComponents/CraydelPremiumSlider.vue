<template>
  <section class="craydel-premium-slider custom">
    <client-only>
      <splide :options="options">
        <splide-slide>
          <div class="item text_center">
            <img src="~/assets/images/welcome/craydel-premium-slide-1.png" width="760" height="507"
                 alt="Craydel Premium">
            <div class="welcome-cma">
              <h2 class="welcome-cma__title">Career Match Assessment</h2>
              <p>Get your top 3 Ideal Careers based on your your apttitudes, motivators, etc.</p>
              <a href="https://workspace.craydel.com/report-preview" class="page_cta" target="_blank">See Sample Report</a>
            </div>
          </div>
        </splide-slide>
        <splide-slide>
          <div class="item text_center">
            <img src="~/assets/images/welcome/craydel-premium-slide-2.png" width="760" height="507"
                 alt="Craydel Premium">
            <div class="welcome-cma">
              <h2 class="welcome-cma__title">Dashboard</h2>
              <p>Personalised recommendations on Courses, Career Pathways & Subject Requirements</p>
              <a href="https://workspace.craydel.com/report-preview" class="page_cta" target="_blank">See Sample Dashboard</a>
            </div>
          </div>
        </splide-slide>
      </splide>
    </client-only>
  </section>
</template>

<script>
export default {
  name: "CraydelPremiumSlider",
  data() {
    return {
      options: {
        type: 'loop',
        autoplay: true,
        perPage: 1,
        perMove: 1,
        pagination: false,
      },
    }
  }
}
</script>

<style scoped>

</style>

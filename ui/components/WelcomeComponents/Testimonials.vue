<template>
  <section class="testimonials-section">
    <div class="text_center">
      <p class="summary">Here's what our students from Kenya have to say!</p>
    </div>

    <client-only>
      <splide :options="options" class="testimonials">
        <splide-slide v-for="testimony in testimonials" :key="testimony.id" class="testimonial">
          <div class="testimonial__header">
            <span class="initials">{{testimony.initials}}</span>
            <div class="info">
              <h3 class="testimonial__author">{{testimony.student_name}}</h3>
              <span class="testimonial__designation">{{ testimony.student_title }}</span>
              <div class="student_rating rating-5"></div>
            </div>
          </div>
          <p class="testimonial__text" v-html="testimony.quote"></p>
        </splide-slide>
      </splide>
    </client-only>
  </section>
</template>

<script>
import CampaignsService from "@/helpers/CampaignsService";

export default {
  name: "Testimonials",
  data() {
    return {
      testimonials:[],
      options: {
        type: 'loop',
        autoplay: true,
        perPage: 3,
        perMove: 1,
        gap: '1.2rem',
        pagination: false,
        breakpoints: {
          '1280': {
            gap: '2rem',
            perPage: 2,
          },
          '960': {
            gap: '2rem',
          },
          '768': {
            gap: '1rem',
            perPage: 1,
          },
          '560': {
            gap: '1rem',
            perPage: 1,
          },
        },
      },
    }
  },
  async fetch() {
    this.testimonials = await CampaignsService.getTestimonials(this.testimonial_type).then(response => response.data)
  }
}
</script>

<style scoped>

</style>

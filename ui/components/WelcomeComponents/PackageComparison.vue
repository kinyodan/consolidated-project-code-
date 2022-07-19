<template>
  <section class="package-comparison">
    <div class="package-comparison__block package-basic">
      <h3 class="package-title">Craydel Basic</h3>

      <ul class="package-features">
        <li><span>Access to Career Pathways</span></li>
        <li><span>Subject Requirements Resource</span></li>
        <li><span>University Application Support and Tracking</span></li>
        <li><span>Study Abroad Visa Support</span></li>
        <li class="not-included"><span>Career Match Psychometric Assessment which gives you personalised recommendations on:</span>
          <ul>
            <li>Ideal career options</li>
            <li>Subject requirements</li>
            <li>Courses</li>
            <li>Career pathways</li>
          </ul>
        </li>
      </ul>

      <a href="/welcome-confirm" class="page_cta white package-btn">Sign up for free</a>
    </div>
    <div class="package-comparison__block package-premium">
      <span class="package-tag">Most Popular</span>
      <h3 class="package-title">Craydel Premium</h3>
      <p>(when you need scientific, personalised recommendations for your higher education)</p>

      <ul class="package-features">
        <li><span>Career Match Psychometric Assessment</span></li>
        <li><span>Top 3 Ideal Career Options</span></li>
        <li><span>Personalised recommendations on your university courses</span></li>
        <li><span>Personalised Career Pathways</span></li>
        <li><span>Personalised recommendations on subject requirements</span></li>
        <li><span>University Application Support and Tracking</span></li>
        <li><span>Study Abroad Visa Support</span></li>
      </ul>

      <span class="package-price">
        <span class="value">$30</span> one time fee
      </span>

      <form id="frm_guest_checkout_form" class="text_center" @submit.prevent="submitCheckoutForm">
        <div class="notification error" v-if="submitError">
          <template v-if="submitErrorText">{{ submitErrorText }}</template>
        </div>
          <button type="submit" class="page_cta white package-btn">Upgrade to Premium</button>
      </form>

    </div>
  </section>
</template>

<script>
import Vue from 'vue';
import RedirectToPayementGatewayService from "@/helpers/RedirectToPayementGatewayService";
import {mapActions} from 'vuex';
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

export default {
  name: "PackageComparison",
  props: ['current_profile'],
  data(){
    return {
      amountText:"",
      login_link:"",
      is_logged_in:false,
      student_full_names:"",
      student_email:"",
      submitError:false,
      submitErrorText:"",
      user:null,
      country_code: "KE",
      product_quantity: 1,
      discount_price:0,
      discount_price_amount:0,
      product_code: 8195726434
    }
  },
  mounted() {
    //get the profile with slots
  },
  methods:{
    //map sctions from the profile store
    ...mapActions('profile',['getProfile','getApplications']),

    //process upgarade to premium button click
    async submitCheckoutForm(){
      this.student_email=this.current_profile.email;
      this.student_full_names=this.current_profile.display_name;

      let self = this
      this.$nuxt.$loading.start()
      let response = await RedirectToPayementGatewayService.reDirectToPaymentGateway(this.current_profile,this.product_code,this.product_quantity);
    },
  }
}
</script>

<style scoped>

</style>

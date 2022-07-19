<template>
  <div v-if="attempt">
    <section class="progress-card" v-if="progress === 100">
      <div class="progress-card__pic">
        <img src="/images/memojis/lady.svg" width="163" height="163" alt="Lady memoji thumbs up"/>
      </div>
      <div class="progress-card__text">
        <h2 class="progress-card__title">My {{ assessmentType.name }}</h2>
        <p>Congratulations on completing your Psychometric Assessment</p>
      </div>
      <div class="progress-card__bar-wrapper complete">
        <div class="progress-card__bar" :style="`width:${progress}%;`"></div>
      </div>

      <div class="text_center progress-card__btn-wrapper">
        <span v-if="not_started_assesment">
          <nuxt-link class="page_cta" :to="`/onboarding?profile=${activeSlot}`">Start Assessment
          </nuxt-link>
        </span>
        <span v-else>
          <p v-if="attempt.has_results === 0">Please wait as we process your report. Refresh the page after 10 min</p>
          <nuxt-link class="page_cta" v-else :to="`/ideal-report?profile=${activeSlot}`">View Assessment Report
          </nuxt-link>
        </span>
      </div>
    </section>
    <section class="progress-card" v-else>
      <div class="progress-card__pic">
        <img src="/images/memojis/lady.svg" width="163" height="163" alt="Lady memoji thumbs up"/>
      </div>
      <div class="progress-card__text">
        <h2 class="progress-card__title">My {{ assessmentType.name }}</h2>
        <p>Your assessment is {{ progress }}% complete</p>
        <p>Complete your assessment to get personalised recommendations on your ideal career pathways, courses and subjects.</p>
      </div>
      <div class="progress-card__bar-wrapper">
        <div class="progress-card__bar" :style="`width:${progress}%;`"></div>
      </div>
      <div class="text_center progress-card__btn-wrapper">
        <a class="page_cta" :href="`/assessment-test?profile=${activeSlot}`">Complete
          Assessment
        </a>
      </div>
    </section>
  </div>
  <div v-else>
    <div class="notification error" v-if="startError">
      <template v-if="startErrorText">{{ startErrorText }}</template>
    </div>
    <section class="subject-choice-header">
      <div class="subject-choice-header__pic">
        <img src="/images/memojis/lady.svg" width="163" height="163" alt="Lady memoji thumbs up"/>
      </div>
      <div v-if="activeSlot" class="subject-choice-header__text">
        <h1 class="subject-choice-header__title">My Career Match Assessment</h1>
        <p>Start your assessment to get personalised recommendations on your ideal career pathways, courses and subjects</p>
      </div>

      <div v-else class="subject-choice-header__text">
        <h1 class="subject-choice-header__title">My Career Match Assessment</h1>
        <p>Start your assessment to get personalised recommendations on your ideal career pathways, courses and subjects</p>
      </div>
      <div v-if="activeSlot" class="text_center progress-card__btn-wrapper">
        <a class="page_cta" :href="`/onboarding?profile=${activeSlot}`">
          Start Assessment
        </a>
      </div>

      <form v-else @submit.prevent="submitCheckoutForm">
        <button type="submit" class="page_cta">Upgrade to Premium</button>
      </form>
    </section>
  </div>
</template>

<script>
import Vue from 'vue';
import DashboardService from "@/helpers/assessments/DashboardService";
import {mapActions, mapState} from "vuex";
import jwt_decode from "jwt-decode";
import RedirectToPayementGatewayService from "@/helpers/RedirectToPayementGatewayService";
import Vuelidate from 'vuelidate'
import {email, maxLength, minLength, required} from "vuelidate/lib/validators";
Vue.use(Vuelidate)

export default {
  name: "ProgressCard",
  props: ['assessmentType', 'attempt', 'activeSlot', 'progress','not_started_assesment'],
  data() {
    return {
      startError: false,
      startErrorText: "",
      free_account:false,
      current_profile:"",
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
  validations: {
    student_full_names: {
      required,
      minLength: minLength(3),
      maxLength: maxLength(100),
    },
    student_email: {
      required,
      email,
      minLength: minLength(3),
      maxLength: maxLength(100),
    }
  },
  mounted() {
    //get the profile with slots
    this.getUserProfile();
  },
  computed:{
    ...mapState('profile',{
      profile: state => state.profile,
    },),
    updated() {
      if (process.client) {
        let token = this.$cookies.get('_token');
        if (token) {
          this.is_logged_in = true
          let user = jwt_decode(token);
          if (user) {
            this.user = user
            this.student_full_names = user.hasOwnProperty('display_name') ? user.display_name : ""
            this.student_email = user.hasOwnProperty('email') ? user.email : ""
          }
        }
      }
    }
  },
  methods: {
    //map sctions from the profile store
    ...mapActions('profile',['getProfile','getApplications']),
    async startAssessment() {
      //start loading
      this.$nuxt.$loading.start()

      //submit start assessment request
      let response = await DashboardService.startAssessment(this, this.activeSlot);
      if (response.data.status) {
        //start loading
        this.$nuxt.$loading.finish()

        this.$router.push(`/onboarding?profile=${this.activeSlot}`)
      } else {
        //start loading
        this.$nuxt.$loading.finish()
         this.startError = true;
         this.startErrorText = response.data.message
      }
    },
    //method to get the logged in user profile
    getUserProfile(){
      //get the user profile from the store method
      this.getProfile({app:this}).then(response =>{
        this.current_profile=this.profile;
      });
    },
    reDirectToCheckoutPage(){
      this.$router.push({
        path: '/my-packages?code=8195726434'
      })
    },
    async submitCheckoutForm(){
      this.$v.$touch()
      this.student_email=this.current_profile.email;
      this.student_full_names=this.current_profile.display_name;

      let self = this
      this.$nuxt.$loading.start()
      let response = await RedirectToPayementGatewayService.reDirectToPaymentGateway(this.current_profile,this.product_code,this.product_quantity);
    },
  },

}
</script>

<style scoped>

</style>

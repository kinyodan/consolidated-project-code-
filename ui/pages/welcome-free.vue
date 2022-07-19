<template>
  <div class="welcome-section">
    <section class="welcome-header">
      <div class="welcome-header__pic bounceIn">
        <img src="/images/memojis/lady.svg" width="262" height="262" alt="Memoji showing thumbs up"/>
      </div>
      <div class="welcome-header__content">
        <h2 class="welcome-header__title">Hi {{current_profile.first_name}}!</h2>
      </div>
    </section>

    <section class="welcome-summary">
      <p>Welcome to your Craydel dashboard where you can get personalised recommendations on subject requirements,
        career pathways and courses for the next step in your education journey!</p>
      <span class="note">You are currently on your Craydel free account</span>
    </section>

    <package-comparison :current_profile="current_profile"></package-comparison>

    <section class="welcome-footer">
      <form id="frm_guest_checkout_form" @submit.prevent="submitCheckoutForm">
        <div class="notification error" v-if="submitError">
          <template v-if="submitErrorText">{{ submitErrorText }}</template>
        </div>
        <div class="form_group procceed_to_checkout_btn">
          <button type="submit" class="page_cta">Upgrade to Premium</button>
        </div>
      </form>
    </section>

    <welcome-footer></welcome-footer>

  </div>
</template>

<script>
import Vue from 'vue';
import PackageComparison from "@/components/WelcomeComponents/PackageComparison";
import WelcomeFooter from "@/components/WelcomeComponents/WelcomeFooter";

import {mapState, mapActions} from 'vuex'
import LazyHydrate from 'vue-lazy-hydration';
import {required, minLength, maxLength, email} from 'vuelidate/lib/validators'

import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import jwt_decode from "jwt-decode";
import CoursesService from "@/helpers/CoursesService";
import PaymentService from "@/helpers/PaymentService";
import RedirectToPayementGatewayService from "@/helpers/RedirectToPayementGatewayService";

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

export default {
  name: "welcome",
  components: {WelcomeFooter, PackageComparison},
  data(){
    return {
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
    this.getUserProfile()

    let login_service_provider = process.env.LOGINREDIRECTURL
    this.login_link = login_service_provider + '/?redirect=' + window.location.href + '/?auto_open_user_form=yes'

    let auto_open_user_form = this.$route.query.auto_open_user_form;

    if(auto_open_user_form === 'yes'){
      document.body.classList.toggle('guest_checkout_form_open')

      let v = this;
      setTimeout(function(){
        const el = v.$el.querySelector(".sign_up_or_login_in_to_buy");
        if (el) {
          el.scrollIntoView(true);
        }
      }, 1000);
    }
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
  methods:{
    //map sctions from the profile store
    ...mapActions('profile',['getProfile','getApplications']),

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
  }
}
</script>

<style scoped>

</style>


<template>
  <div class="welcome-section">
    <section class="welcome-header">
      <div class="welcome-header__pic bounceIn">
        <img src="/images/memojis/lady.svg" width="262" height="262" alt="Memoji showing thumbs up"/>
      </div>
      <div class="welcome-header__content">
        <h2 class="welcome-header__title">Are you sure you don't want to upgrade to Craydel Premium?</h2>
        <p>Here's what you'll get</p>
      </div>
    </section>

    <craydel-premium-slider></craydel-premium-slider>

    <section class="welcome-cma text_center">
      <blockquote class="welcome-cma__title">
        Globally used by 100,000 plus students to get personalised recommendations for their higher education journey
      </blockquote>
    </section>

    <testimonials></testimonials>
    <welcome-footer></welcome-footer>
  </div>
</template>

<script>
import WelcomeFooter from "@/components/WelcomeComponents/WelcomeFooter";
import CraydelPremiumSlider from "@/components/WelcomeComponents/CraydelPremiumSlider";
import Testimonials from "@/components/WelcomeComponents/Testimonials";
import CampaignsService from "@/helpers/CampaignsService";
import {mapState, mapActions} from 'vuex'

export default {
  name: "welcome2",
  components: {Testimonials, CraydelPremiumSlider, WelcomeFooter},
  data(){
    return {
      current_profile:"",
      testimonial_type:"assessment",
    }
  },
  mounted() {
    //get the profile with slots
    this.getUserProfile()
  },
  computed:{
    ...mapState('profile',{
      profile: state => state.profile,
    },)
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
    async fetch() {
      this.testimonials = await CampaignsService.getTestimonials(this.testimonial_type).then(response => response.data)
    }
  }
}
</script>

<style scoped>

</style>

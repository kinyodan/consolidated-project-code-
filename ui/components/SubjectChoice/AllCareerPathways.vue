<template>
      <div class="lower_reccomendations">
        <course-categories :education_type_id="selected_education_type" :show_helper_for="show_helper_for"  id="pathwayCategoriesSection" :loading="loading"> </course-categories>
      </div>
</template>
<script>
import CourseCategories from "@/components/SubjectChoice/CourseCategories";
import RecommendationsService from "@/helpers/RecommendationsService";
import CourseSubjects from "@/components/SubjectChoice/CourseSubjects";
import axios from "axios";

import {mapActions, mapState} from "vuex";

export default {
  name: "AllCareerPathways",
  components: {CourseCategories,CourseSubjects},
  props: ['education_type_id', 'show_helper_for'],
  data() {
    return {
      // Parameters that change depending on the type of dialogue
      pathways: [],
      selected_pathway:"0",
      loading:false,
      selected_education_type: this.education_type_id
    }
  },
  created() {
    this.getCareerpathways()
  },
  mounted() {
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
  methods: {
    ...mapActions('recommendations', ['togglePathwayCategories', 'getPathwayCourseCategories', 'setSelectedPathwayName', 'togglePathwayCategorySubjects']),
    async getCareerpathways() {
      await axios.post('https://api.craydel.online/courses/rpc/list-courses-pathways').then(response => {
        let data = response.data
        if (data.status) {
          this.pathways = data.data.items
        }
      });
    },
    showPathwayCourseCategories(pathwayName) {
      this.loading = true
      this.togglePathwayCategorySubjects(false)

      let pathwayData = new FormData()
      pathwayData.append('name', pathwayName)
      //set the state
      this.setSelectedPathwayName(pathwayName)
      this.getPathwayCourseCategories({app: this, pathwayData: pathwayData}).then(() => {
        //scroll to div
        document.getElementById("pathwayCategoriesSection").scrollIntoView({
          block: "start",
          behavior: "smooth",
        });
        this.loading = false
      })
      this.togglePathwayCategories(true)

    },
    scrollToTargetAdjusted() {
      let element = document.getElementById('pathwayCategoriesSection');
      let headerOffset = 45;
      let elementPosition = element.getBoundingClientRect().top;
      let offsetPosition = elementPosition + window.pageYOffset - headerOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: "smooth"
      });
    },
    onChange(event){
      this.showPathwayCourseCategories(event)

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
  watch: {
    education_type_id: {
      immediate: true,
      handler (val, oldVal) {
        this.selected_education_type = val
      }
    }
  }
}
</script>
<style scoped>
</style>

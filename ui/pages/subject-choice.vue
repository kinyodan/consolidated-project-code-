<template>
  <div>
    <subject-choice-header></subject-choice-header>

    <!--recommendations-->
    <recommendations
      @hideOtherPathwayCourseCategory="hideOtherPathwayCourseCategory"
      @updatedEducationSystemSelection="updatedEducationSystemSelection"
      :show_pathway_course_category="show_pathway_course_category" :attempt="attempts[0]" :recommended-pathways="recommendedPathways" :active-slot="activeSlot" :show_helper_for="show_helper_for"></recommendations>
    <!--recommendations-->
    <section class="all-career-pathways">
    <h2 class="all-career-pathways__title">Subject Requirements For All Pathways</h2>
      <div class="custom-select">
        <client-only>
        <select
        v-model="selected_pathway"
        id="all_pathways"
        @change="onChange(selected_pathway)"
        >
          <option disabled :value="selected_pathway">{{selected_pathway}}</option>
          <option v-for="pathway in pathways" :value="pathway.career_pathway_name" :key="pathway.id" :selected="selected_pathway === pathway.career_pathway_name">
            {{pathway.career_pathway_name}}
          </option>
        </select>
        </client-only>
      </div>

    <recommended-career-pathways :profile=profile></recommended-career-pathways>
  </section>
    <all-career-pathways :education_type_id="current_education_system_id" v-if="show_other_pathway_course_category" :show_helper_for="show_helper_for"></all-career-pathways>
    <subject-help-popup :show_info_box="show_info_box"  ></subject-help-popup>
  </div>
</template>

<script>
import SubjectChoiceHeader from "@/components/SubjectChoice/SubjectChoiceHeader";
import RecommendedCareerPathways from "@/components/SubjectChoice/RecommendedCareerPathways";
import Recommendations from "@/components/DashboardComponents/Recommendations";
import AllCareerPathways from "@/components/SubjectChoice/AllCareerPathways";
import SubjectHelpPopup from "@/components/SubjectChoice/SubjectHelpPopup";
import axios from "axios";
import {mapState, mapActions} from 'vuex'
import RedirectToPayementGatewayService from "@/helpers/RedirectToPayementGatewayService";
import LoadingBar from "@/components/GeneralComponents/LoadingBar";

export default {
  name: "subject-choice",
  data(){
    return {
      show_pathway_course_category: true,
      show_other_pathway_course_category: false,
      pathways: [],
      selected_pathway:"-- Select Career Pathway --",
      loading:false,
      show_info_box: true,
      current_education_system_id: this.education_type_id,
      product_code: '8195726434',
      product_quantity: 1,
      show_helper_for:null,
    }
  },
  components: {
    LoadingBar,
    SubjectHelpPopup, AllCareerPathways, RecommendedCareerPathways, SubjectChoiceHeader,Recommendations},
  computed:{
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
      assessment: state => state.assessment,
      assessmentType: state => state.assessmentType,
      attempts: state => state.attempts,
      recommendedPathways: state => state.recommendedPathways
    }),
    ...mapState('profile',{
      profile: state => state.profile,
      slots: state => state.slots,
      education_type_id: state => state.currentEducationID
    })
  },
  mounted(){
    if(this.slots.length > 0){
      if (this.activeSlot.length===0) {
        this.$router.push('/my-packages')
      }
    }
    this.setHelperFor()

  },
  created() {
    this.getCareerpathways()
    if(this.slots.length > 0){
      if (this.activeSlot.length===0) {
        this.$router.push('/my-packages')
      }
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
    hideOtherPathwayCourseCategory(){
        this.show_pathway_course_category = true
        this.show_other_pathway_course_category = false
    },
    updatedEducationSystemSelection(education_system_id){
      this.current_education_system_id = education_system_id
      if(education_system_id !== 11){
        this.show_info_box = false
      }
      this.setHelperFor(education_system_id)
    },
    setHelperFor(education_system_id){

      if(this.show_helper_for === null && !education_system_id){
        if(localStorage.getItem('country_code')==='KE'){
          this.show_helper_for= "KCSE"
        }

        if(localStorage.getItem('country_code')==='NG'){
          this.show_helper_for="Nigerian SSCE"
        }
      }

      if(education_system_id && education_system_id === '11'){
        this.show_helper_for= "KCSE"
      }

      if(education_system_id && education_system_id === '12'){
        this.show_helper_for= "IGCSE"
      }
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
      this.show_other_pathway_course_category=true;
      this.show_pathway_course_category=false;
      this.selected_pathway=event
      const values_for = this.pathways.findIndex(item => item.career_pathway_name === event);
      console.log(values_for);
      this.selected_pathway= this.pathways[values_for].career_pathway_name;
      console.log(event)
    },
    reDirectToCheckoutPage(){
      this.$router.push({
        path: '/my-packages?code=8195726434'
      })
    },
    async submitCheckoutForm(){
      this.$v.$touch()
      this.student_email=this.profile.email;
      this.student_full_names=this.profile.display_name;
      this.$nuxt.$loading.start();
      await RedirectToPayementGatewayService.reDirectToPaymentGateway(this.profile,this.product_code,this.product_quantity);
    },
  }

}
</script>

<style scoped>

</style>

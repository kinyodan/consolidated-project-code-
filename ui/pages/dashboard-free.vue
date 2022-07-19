<template>
  <div>
    <div v-if="loading" class="page_loading">
      <div class="preload_inner">
        <img src="~/assets/craydel.svg" alt="Craydel">
        <div class="loading_spinner"></div>
      </div>
    </div>
    <!--Progress Card-->
    <progress-card :assessment-type="assessmentType" :attempt="attempts[0]" :active-slot="activeSlot" :progress="progressPercent" v-if="assessmentType"></progress-card>
    <!--Progress Card-->

    <!-- free-account-links -->
    <free-account-links></free-account-links>
    <!-- free-account-links end -->

    <!--recommendations-->
    <recommendations :show_pathway_course_category="show_pathway_course_category" :attempt="attempts[0]" :recommended-pathways="recommendedPathways" :active-slot="activeSlot"></recommendations>
    <!--recommendations-->

    <!--course applications-->
    <applications-card :active-slot="activeSlot" :attempt="attempts[0]" :profile="profile" :progress="progressPercent"></applications-card>
    <!--Course applications-->

  </div>
</template>

<script>
import Vue from 'vue';
import {mapState} from "vuex";
import ProgressCard from "@/components/DashboardComponents/ProgressCard";
import Recommendations from "@/components/DashboardComponents/Recommendations";
import ApplicationsCard from "@/components/DashboardComponents/ApplicationsCard";
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import FreeAccountLinks from "@/components/DashboardComponents/FreeAccountLinks";

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
export default {
  middleware: 'auth',
  name: "index",
  components: {FreeAccountLinks, ApplicationsCard, Recommendations, ProgressCard},
  data() {
    return {
      startError: false,
      startErrorText: "",
      show_pathway_course_category: true,
      show_other_pathway_course_category: true,
      loading:false,
      free_account:false,
    }
  },
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
      assessment: state => state.assessment,
      assessmentType: state => state.assessmentType,
      attempts: state => state.attempts,
      recommendedPathways: state => state.recommendedPathways
    }),
    ...mapState('profile',{
      profile: state => state.profile,
      slots: state => state.slots
    }),
    progressPercent(){
      if(this.attempts && this.attempts.length === 0){
        return 0
      }else{
        let progress = Math.round((this.attempts[0].number_of_questions_answered / this.attempts[0].total_number_of_questions) * 100)
        return progress <= 100 ? progress : 100;
      }

    },
  },
  mounted() {

    if(this.slots.length > 0){
      if(this.activeSlot){
        return "/?profile="+this.activeSlot;
      }else{
        return '/my-packages'
      }
    }else{

    }
  },
  methods: {
    hideOtherPathwayCourseCategory(){
      this.show_pathway_course_category = true
      this.show_other_pathway_course_category = false
    },
    updatedEducationSystemSelection(education_system_id){
      this.current_education_system_id = education_system_id

      if(education_system_id !== 11){
        this.show_info_box = false
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
    check_if_free(){
      if(this.slots.length == 0){
        this.free_account=true;
      }else{
        this.free_account=false;
      }
    }

  },
  created() {

    if(this.slots.length > 0){
      if(this.activeSlot){
        return "/?profile="+this.activeSlot
      }else{
        return '/my-packages'
      }
    }else{

    }
  }
}
</script>

<style>

</style>

<template>
  <section class="recommendations-section">

    <!-- recommendations-header -->
    <div class="recommendations-header">
      <h2 class="recommendations-header__title">My Personalised Recommendations</h2>
      <p v-if="recommendedPathways.length > 0">Explore your recommended career pathways, courses and subjects based on your top 3 ideal careers.</p>
      <p v-else>You currently have no recommended career pathways, courses and subjects.</p>
    </div>
    <!-- recommendations-header end -->
    <div class="curriculum-selector" v-if="!free_account">
      <span class="curriculum-selector__label">Currently showing subject requirements for:</span>
      <div class="custom-select">
        <select id="all_pathways" @change="updateEducationTypeId($event)" v-model="education_type_id" name="all_pathways">
          <option selected value="">-- Select Curriculum --</option>
          <option v-for="system in education_systems" :value="`${system.id}`">{{ system.education_type_name }}</option>
        </select>
      </div>
    </div>
    <!-- recommendation-cards -->
    <template v-if="recommendedPathways.length > 0">
      <!--recommendations-->
      <div class="recommendation-cards">
        <div class="course-card" v-for="pathway in recommendedPathways" v-if="pathway" :key="pathway.career_pathway_name">
          <div class="course-card__pic">
            <img :src="pathway.image" width="119" height="119" :alt="pathway.career_pathway_name"/>
          </div>
          <h3 class="course-card__title">{{ pathway.career_pathway_name }}</h3>
          <div class="course-card__links">
            <a :href="`${marketplaceUrl}/top-courses?category=${pathway.career_pathway_name.replaceAll('&','and')}`"
               target="_blank">Explore Career Pathways</a>
               <a :href="`${marketplaceUrl}/courses?search_term=${pathway.career_pathway_name.split('&').join(',')}`"
               target="_blank">Explore Courses</a>
            <a href="javascript:void(0)" @click.prevent="showPathwayCourseCategories(pathway.career_pathway_name)">Explore Subject Requirements</a>

          </div>
        </div>
      </div>
      <!--recommendations-->
      <course-categories v-if="show_pathway_course_category" :country_code="profile.country_code" :education_type_id="education_type_id" id="pathwayCategoriesSection" :loading="loading" :show_helper_for="show_helper_for">
      </course-categories>
    </template>
    <template v-else>
      <div class="recommendation-cards">
        <div class="course-card" v-for="i in 3" :key="i">
          <h3 class="course-card__title grey">My Ideal Career</h3>
          <div class="course-card__pic">
            <img src="~/assets/images/courses/blank.svg" width="120" height="154" alt="No recommendation"/>
          </div>
          <div class="course-card__blank-content">
            <div class="blank-line"></div>
            <div class="blank-line"></div>
            <div class="blank-line short"></div>
          </div>
        </div>
      </div>
      <div class="text_center" v-if="attempt">
        <p>To unlock your personalised recommendations, please complete your assessment</p>
        <a class="page_cta" :href="`/assessment-test?profile=${activeSlot}`">Complete Assessment
        </a>
      </div>
    </template>
    <!-- recommendation-cards end -->
  </section>
</template>

<script>
import CourseCategories from "@/components/SubjectChoice/CourseCategories";
import CurriculumSelector from "@/components/SubjectChoice/CurriculumSelector";
import {mapActions, mapState} from "vuex";
import CoursesService from "@/helpers/CoursesService";
import RecommendationsService from "@/helpers/RecommendationsService";

export default {
  name: "Recommendations",
  props: ['attempt', 'recommendedPathways', 'activeSlot', 'show_pathway_course_category','show_helper_for'],
  components: {
    CourseCategories,CurriculumSelector
  },
  data() {
    return {
      loading:true,
      education_type_id:"",
      free_account:false,
      country_code:null,
    }
  },
  computed: {
    ...mapState('dashboard', {
      assessment: state => state.assessment,
      assessmentType: state => state.assessmentType,
      attempts: state => state.attempts,
    }),
    ...mapState('profile',{
      profile: state => state.profile,
      slots: state => state.slots,
      education_systems: state => state.education_systems,
    }),
    marketplaceUrl() {
      return process.env.MARKETPLACE_URL
    }
  },
  created() {
    let source=this.$nuxt.$route.path
    if(source.includes("dashboard-free")){
     this.free_account=true;
    }
  },
  methods: {
    ...mapActions('recommendations', ['togglePathwayCategories', 'getPathwayCourseCategories', 'setSelectedPathwayName', 'togglePathwayCategorySubjects']),
    ...mapActions('profile', ['changeCurrentEducationSystemID']),
    showPathwayCourseCategories(pathwayName) {
      this.loading = true
      this.togglePathwayCategorySubjects(false)
      this.$emit('hideOtherPathwayCourseCategory')

      let pathwayData = new FormData()
      pathwayData.append('name', pathwayName)
      this.setSelectedPathwayName(pathwayName)
      this.getPathwayCourseCategories({app: this, pathwayData: pathwayData}).then(() => {
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
    updateEducationTypeId(event){
      this.education_type_id = event.target.value
      this.$emit('updatedEducationSystemSelection', event.target.value)

      this.changeCurrentEducationSystemID(event.target.value)

      let get_pathwayCategoriesSection_class = document.getElementById("pathwayCategoriesSection");

      if(get_pathwayCategoriesSection_class!==null){
        get_pathwayCategoriesSection_class.scrollIntoView({
        block: "start",
        behavior: "smooth",
        });
      }
    },
  }
}
</script>

<style scoped>

</style>

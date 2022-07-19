<template>
  <div class="course-categories" :class="{'course-categories--show':showCategories}">

    <div class="course-categories__header">
      <a href="javascript:void(0)" @click.prevent="hideCategories" class="course-categories__close icon-close"
         title="Close"></a>
      <h2 class="course-categories__title" v-if="selectedPathway">{{ selectedPathway }}</h2>
      <p class="summary">Select a course category to see subject requirements</p>
    </div>
    <div class="loading_spinner" v-if="loading"></div>
    <ul class="course-categories__listing" v-if="pathwayCourseCategories && !loading">
      <li v-for="category in pathwayCourseCategories">
        <a href="javascript:void(0)" @click.prevent="showCategorySubjects(category)" class="category">{{ category.discipline_name }}</a>
      </li>
    </ul>
    <course-subjects :show_info_icon="this.show_info_icon" :show_helper_for="show_helper_for"  :subjects-loading="subjectsLoading"></course-subjects>
  </div>
</template>

<script>
import CourseSubjects from "@/components/SubjectChoice/CourseSubjects";
import {mapActions, mapState} from "vuex";

export default {
  name: "CourseCategories",
  props: ['loading', 'education_type_id', "country_code", 'show_helper_for'],
  data(){
    return {
      subjectsLoading: true,
      selected_education_type_id: this.education_type_id,
      show_info_icon: true,
      selected_category: null
    }
  },
  components: {CourseSubjects},
  computed: {
    ...mapState('recommendations', {
      showCategories: state => state.showPathwayCategories,
      pathwayCourseCategories: state => state.pathwayCourseCategories,
      selectedPathway: state => state.selectedPathway
    }),
    ...mapState('profile', {
      currentEducationID: state => state.currentEducationID
    }),

  },
  created() {
    if(localStorage.getItem('country_code')==='KE' ){
      this.show_info_icon = true
    }else if(localStorage.getItem('country_code')==='NG'){
      this.show_info_icon = true
    }else{
      this.show_info_icon = false;
    }

  },
  methods: {
    ...mapActions('recommendations', ['togglePathwayCategories','togglePathwayCategorySubjects','setSelectedCategoryName','getPathwayCategorySubjects','getPathwayCategoryOtherSubjects']),
    hideCategories() {
      this.togglePathwayCategories(false)
    },
    showCategorySubjects(category){
      this.selected_category = category
      this.subjectsLoading = true
      this.setSelectedCategoryName(category.discipline_name)
      let categoryData = new FormData()

      this.getPathwayCategorySubjects({app:this,discipline:category.discipline_id, categoryData:categoryData, education_type: this.currentEducationID,country_code: this.country_code}).then(()=>{
        this.subjectsLoading =false
      })
      this.getPathwayCategoryOtherSubjects({app:this,discipline:category.discipline_id,categoryData:categoryData}).then(()=>{})
      this.togglePathwayCategorySubjects(true)
    },
  },
  watch: {
    education_type_id: {
      immediate: true,
      handler (val, oldVal) {
        if(this.selected_category){
          let categoryData = new FormData()
          let set_country = this.country_code;
          if(this.country_code==undefined){
            set_country = localStorage.getItem("country_code");
          }
          this.getPathwayCategorySubjects({app:this,discipline:this.selected_category.discipline_id,categoryData:categoryData, education_type: val,country_code: set_country}).then(()=>{
            this.subjectsLoading =false
          })
        }

        if(val !== undefined){
          if(parseInt(val) === 11 || parseInt(val) === 12){
            this.show_info_icon = true
          }else{
            this.show_info_icon = false
          }
        }else{
          this.show_info_icon = true
        }
      }
    }
  }
}
</script>

<style scoped>

</style>

<template>
  <div class="course-subjects"
       :class="{'is-hidden':!showPathwayCategorySubjects, 'move-out':showPathwayCategorySubjects}">

    <div class="course-subjects__header">
      <a href="javascript:void(0)" @click.prevent="hideCategorySubjects" class="go-back">Back</a>
      <a href="javascript:void(0)" @click.prevent="showPathwayCategorySubjectsHelp" v-if="can_show_info_icon"><i
        class="icon-info"></i></a>
    </div>

    <h3 class="course-subjects__title">Subject Requirements for {{ selectedCategory }}</h3>

    <!-- course-subjects__listing -->
    <h4 class="course-subjects__sub-title" v-if="subjectsLoading"></h4>
    <div class="loading_spinner" v-if="subjectsLoading"></div>
    <ul class="course-subjects__listing" v-if="pathwayCategorySubjects && !subjectsLoading">
      <li v-for="(subject, index) in pathwayCategorySubjects" :key="index">
          <strong>{{ subject.subject_title }}:</strong> {{ subject.subject_title_description }}      </li>
    </ul>

    <!--Help Popup-->
    <subject-help-popup :show_helper_for="show_helper_for"></subject-help-popup>
    <!--Help Popup-->

  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import SubjectHelpPopup from "@/components/SubjectChoice/SubjectHelpPopup";

export default {
  name: "CourseSubjects",
  props: ['subjectsLoading', 'show_info_icon','show_helper_for'],
  components: {
    SubjectHelpPopup
  },
  data(){
    return {
      can_show_info_icon: true,
    }
  },
  computed: {
    ...mapState('recommendations', {
      showPathwayCategorySubjects: state => state.showPathwayCategorySubjects,
      selectedCategory: state => state.selectedCategory,
      pathwayCategorySubjects: state => state.pathwayCategorySubjects,
      pathwayCategoryOtherSubjects: state => state.pathwayCategoryOtherSubjects
    })
  },
  methods: {
    ...mapActions('recommendations', ['togglePathwayCategorySubjects', 'togglePathwayCategorySubjectsHelp']),
    hideCategorySubjects() {
      this.togglePathwayCategorySubjects(false)
    },
    showPathwayCategorySubjectsHelp() {
      this.togglePathwayCategorySubjectsHelp(true)
    }
  },
  watch: {
    show_info_icon: {
      immediate: true,
      handler (val, oldVal) {
        console.log("Show info ico : " + val)
        // this.can_show_info_icon = val
      }
    }
  }

}
</script>

<style scoped>

</style>

<template>
<div class="subject-help" :class="{'subject-help--show':showPathwayCategorySubjectsHelp}">
  <div class="subject-help__popup">
    <a href="javascript:void(0)" @click.prevent="hidePathwayCategorySubjectsHelp" title="Close" class="subject-help__close icon-close"></a>
    <KCSEHelp v-if="show_helper_for==='KCSE'" ></KCSEHelp>
    <IGCSEHelp v-if="show_helper_for==='IGCSE'"></IGCSEHelp>
  </div>
</div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import KCSEHelp from "@/components/SubjectChoice/KCSEHelp";
import IGCSEHelp from "@/components/SubjectChoice/IGCSEHelp";
export default {
  name: "SubjectHelpPopup",
  components: {IGCSEHelp, KCSEHelp},
  props: ['show_info_box','show_helper_for'],
  data(){
    return {
      hide_info_box: this.show_info_box
    }
  },
  computed:{
    ...mapState('recommendations',{
      showPathwayCategorySubjectsHelp : state => state.showPathwayCategorySubjectsHelp
    })
  },
  methods:{
    ...mapActions('recommendations', ['togglePathwayCategorySubjectsHelp']),
    hidePathwayCategorySubjectsHelp(){
      this.togglePathwayCategorySubjectsHelp(false)
    }
  },
  watch: {
    show_info_box: {
      immediate: true,
      handler (val, oldVal) {
        this.hide_info_box = val
      }
    }
  }
}
</script>

<style scoped>

</style>

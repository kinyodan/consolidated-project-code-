<template>
  <div>
    <loading-bar v-if="loading"></loading-bar>

    <div class="app_primary_nav">
      <ul class="nav_menu">
        <li>
          <a :href="setDashboardURL"  :class="{current:activeRoute === '/'}" data-tooltip="Dashboard">
            <svg class="menu_svg_item">
              <use xlink:href="#dashboard_icon"></use>
            </svg>
            <span class="menu_item_label">Dashboard</span>
          </a>
        </li>
        <li>
          <a :href="`/assessment-test?profile=${activeSlot}`" :class="{current:activeRoute.includes('assessment-test')}" data-tooltip="My Assessments">
            <svg class="menu_svg_item">
              <use xlink:href="#session_icon"></use>
            </svg>
            <span class="menu_item_label">My Assessments</span>
          </a>
        </li>
        <li>
          <a href="https://craydel.com/courses" data-tooltip="My Courses" target="_blank">
            <svg class="menu_svg_item">
              <use xlink:href="#mycourses_icon"></use>
            </svg>
            <span class="menu_item_label">My Courses</span>
          </a>
        </li>
        <li>
          <a href="https://craydel.com/top-courses" data-tooltip="Career Resources" target="_blank">
            <svg class="menu_svg_item">
              <use xlink:href="#mycourses_icon"></use>
            </svg>
            <span class="menu_item_label">Career Resources</span>
          </a>
        </li>
        <li>
          <a href="https://craydel.com/institutions" data-tooltip="Institutions" target="_blank">
            <svg class="menu_svg_item">
              <use xlink:href="#myapplication_icon"></use>
            </svg>
            <span class="menu_item_label">Institutions</span>
          </a>
        </li>
        <li>
          <a :href="setSubjectChoiceURL" data-tooltip="Subject Choice">
            <svg class="menu_svg_item">
              <use xlink:href="#mycourses_icon"></use>
            </svg>
            <span class="menu_item_label">Subject Resource</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="quick_utility_menu">
      <ul class="nav_menu">
      </ul>
    </div>
  </div>
</template>

<script>
import LoadingBar from "@/components/GeneralComponents/LoadingBar";
import {mapActions, mapState} from "vuex";

export default {
  name: "MenuComponent",
  data(){
    return {
      slotCode: null,
      loading:false,
    }
  },
  components: {
    LoadingBar,
  },
  computed:{
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    }),
    ...mapState('profile',{
      profile: state => state.profile,
      slots: state => state.slots
    }),
    activeRoute(){
      return this.$route.path
    },
    setSubjectChoiceURL(){
      if(this.slots.length > 0){
        if(this.activeSlot){
          return '/subject-choice?q=true&profile='+this.activeSlot;
        }else{
          return '/my-packages'
        }
      }else{
        return '/subject-choice?q=true'
      }
    },
    setDashboardURL(){
      if(this.slots.length > 0){
        if(this.activeSlot){
          return '/?profile='+this.activeSlot
        }else{
          return '/my-packages'
        }
      }else{
        return '/welcome-free'
      }
    }
  },
  created(){
    if(!this.activeSlot){
      let slotCode = this.$route.query.profile
      if(slotCode) {
        //set the active slot code
        this.setActiveSlot(slotCode)
      }
    }
  },
  methods:{
    ...mapActions('dashboard',[
      'setActiveSlot',
      'getDashboard'
    ]),
    trigger_preloader(){
      console.log("loading..global")
       this.loading=true;
    }
  }
}
</script>

<style scoped>

</style>

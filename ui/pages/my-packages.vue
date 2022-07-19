<template>
  <div class="my_packages_section">
    <div v-if="loading" class="page_loading">
      <div class="preload_inner">
        <img src="~/assets/craydel.svg" alt="Craydel">
        <div class="loading_spinner"></div>
      </div>
    </div>
    <div v-if="pageDataLoaded">
      <template v-if="slots && slots.length > 0">
        <h2 class="text_title">Your Profiles</h2>
        <ul class="user_profiles">
          <li v-for="slot in slots" :class="{empty:slot.student}">
            <template v-if="slot.student">
              <div class="user_profile_avatar" >
                <img src="~/assets/images/default_avatar.png" alt="assessment image">
              </div>
              <h3>{{ slot.student.full_names}} - {{ slot.assessment.name }}</h3>
              <a :href="`/?profile=${slot.slot_code}`" class="page_cta inverse">Access Assessment</a>
            </template>
            <template v-else>
              <div class="user_profile_avatar">&nbsp;</div>
              <h3>{{ slot.assessment.name }}</h3>
              <a href=""
                 data-toggle="modal" :data-target="`#${slot.slot_code}`"
                 class="page_cta">Activate Assessment</a>
              <!--Registration Form Modal-->
              <registration-modal :slotCode="slot.slot_code"></registration-modal>
            </template>
          </li>
        </ul>
      </template>

      <template v-if="!slots">
        <div class="text_center">
          <img src="~/assets/images/failed_icon.svg" alt="craydel payment failed">
          <h2 class="text_title">You have not purchased any assessments</h2>
          <a href="https://craydel.com/career-guidance/career-match-assessment-student" target="_blank" class="page_cta">Purchase Assessment</a>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import RegistrationModal from "@/components/ProfileComponents/RegistrationModal";
import SubjectChoiceHeader from "@/components/SubjectChoice/SubjectChoiceHeader";
import RecommendedCareerPathways from "@/components/SubjectChoice/RecommendedCareerPathways";
import AllCareerPathways from "@/components/SubjectChoice/AllCareerPathways";
import SubjectHelpPopup from "@/components/SubjectChoice/SubjectHelpPopup";

import {mapState, mapActions} from 'vuex'
export default {
  name:'my-packages',
  middleware: 'auth',
  components:{
    RegistrationModal,
    SubjectHelpPopup, AllCareerPathways, RecommendedCareerPathways, SubjectChoiceHeader
  },
  computed:{
    ...mapState('profile',{
      profile: state => state.profile,
      slots: state => state.slots
    })
  },
  data(){
    return{
      pageDataLoaded:false,
      direct_out:true,
      loading:false,
    }
  },
  mounted() {
    //set the page has loaded
    this.pageDataLoaded = true
    this.loading=false;
  },
  created() {
    this.check_package();
    this.set_q_on_url();
    this.loading=false;
  },
  methods:{
    ...mapActions('profile',['getProfile','getApplications']),
    check_package(){
      //this.loading=true;
      this.getProfile({app:this}).then(response =>{
        this.loading=false;

        if(this.profile.slots.length==0){
          this.$router.push('/welcome-free');
        }else{
          //this.$router.push('/welcome-premium')
        }
      });
    },
    create_param_q(){
      localStorage.setItem('q', true)
      localStorage.setItem('q_count', 0)
    },
    set_q_on_url(){
      if(process.client){
        let param_q = localStorage.getItem('q');
        if(parseInt(localStorage.getItem('q_count'))>2){
          //localStorage.setItem('q', false) ;
        }

        if(param_q){
          this.direct_out=false;
          if(parseInt(localStorage.getItem('q_count'))<1){
            localStorage.setItem('q', false) ;
            this.check_package();
            this.create_param_q();
          }

          if(parseInt(localStorage.getItem('q_count'))>3){
            localStorage.setItem('q', false) ;
            this.check_package();
            this.create_param_q();

          }else{
            var increment_q_count = parseInt(localStorage.getItem('q_count'))+1;
            localStorage.setItem('q_count', increment_q_count);
          }
        }else{
          this.check_package();
          this.create_param_q();
        }
      }
    }
  }
}
</script>

<style>

</style>

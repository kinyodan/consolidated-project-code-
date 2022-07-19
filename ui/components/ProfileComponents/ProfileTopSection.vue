 <template>
  <div class="app_header">
    <div class="app_header_content">
      <div class="app_header_left">
        <div class="menu_toggle">
          <span class="menu_toggle_icon">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div class="future_shaper_logo" v-if="school_logo">
          <img :src="school_logo" width="568" height="103" alt="FutureShaper logo"/>
        </div>

        <div class="salutation" v-else>
          <h4>Hi {{ profile.first_name }}!</h4>
          <p>Welcome to Craydel</p>
<!--          <img src="https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/aliance-girls-wings-to-fly-logo.png">-->
        </div>
      </div>
      <div class="app_header_right">
        <div class="salutation" v-if="school_logo">
          <h4>Hi {{ profile.first_name }}!</h4>
        </div>
        <div class="user_modal">
          <div class="user_modal_trigger" data-toggle="dropdown" aria-expanded="false">{{ profile.acronym }}</div>
          <ul class="dropdown-menu">
            <li><nuxt-link to="/my-packages">Switch Profile</nuxt-link></li>
            <li><nuxt-link to="/logout">Sign out</nuxt-link></li>
          </ul>
        </div>
      </div>
      <h2 class="app_header_title" v-if="show_the_assessment_title">Discover your ideal career</h2>
    </div>
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import SchoolService from "@/helpers/SchoolService";
export default {
  name: "ProfileTopSection",
  props: {
    show_the_assessment_title: ""
  },
  computed:{
    ...mapState('profile',{
      profile: state => state.profile,
    },),
    ...mapState('dashboard', {
      assessment: state => state.assessment,
    }),
  },
  data(){
    return {
      school_logo: null,
      craydel_logo: null
    }
  },
  mounted() {
    //start loading
    this.$nextTick(() => {
      this.$nuxt.$loading.start()
    });

    //get the profile with slots
    this.getUserProfile()
    this.getSchoolDetails()

    let slotCode = this.$route.query.profile
    if(slotCode) {
      //get the Dashboard for the slot
      this.getDashboard({app: this, slotCode: slotCode}).then(()=>{

        //get tye student applications
        if(this.assessment){
          let formData = new FormData()
          formData.append('linked_lead_owner_email', this.assessment.email)
          this.getApplications({app:this, formData:formData}).then(()=>{

            //end loading
            this.$nextTick(() => {
              this.$nuxt.$loading.finish()
            });

          })
        }
      });
    }else{
      //end loading
      this.$nextTick(() => {
        this.$nuxt.$loading.finish()
      });
    }
  },
  created() {
    this.getSchoolDetails()
  },
  methods:{
    //map sctions from the profile store
    ...mapActions('profile',['getProfile','getApplications']),
    ...mapActions('dashboard',['getDashboard']),

    //method to get the logged in user profile
    getUserProfile(){
      //get the user profile from the store method
      this.getProfile({app:this}).then(response =>{
        this.getSchoolDetails(this.profile['email'])
      });
    },
    async getSchoolDetails(student_email){
      if(student_email !== undefined){
        let response = await SchoolService.getStudentDetails(student_email)

        if (response.status && response.data.student && response.data.student.school.school_inverse_logo_url !== undefined){
          if(response.data.student.school.school_inverse_logo_url &&response.data.student.school.school_inverse_logo_url.length > 0){
            this.school_logo = response.data.student.school.school_inverse_logo_url
          }
        }
      }
    }
  }
}
</script>

<style scoped>

</style>

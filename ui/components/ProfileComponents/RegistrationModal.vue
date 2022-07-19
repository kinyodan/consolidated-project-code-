<template>
  <div class="modal fade" :id="slotCode" tabindex="-1" role="dialog" :aria-labelledby="slotCode" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close_modal" data-dismiss="modal" aria-label="Close">Close</button>
        </div>
        <form @submit.prevent="registerProfile">
          <div class="modal-body">
            <div class="notification error" v-if="submitError">
              <template v-if="submitErrorText">{{ submitErrorText }}</template>
              <template v-else>Please check the highlighted fields and submit again</template>
            </div>
            <div class="form_sheet" v-if="showStart">
              <h2>1. Select the profile type</h2>
              <label class="custom_radio">I am a parent
                <input type="radio" v-model="profileType" @change="checkProfileType" value="parent" name="profileType">
                <span class="checkmark"></span>
              </label>

              <label class="custom_radio">I am a student / working professional
                <input type="radio" name="profileType" @change="checkProfileType" v-model="profileType" value="student">
                <span class="checkmark"></span>
              </label>
            </div>
            <!--Step three form starts-->
            <div class="form_sheet" v-if="showMainForm">
              <h2>2. Provide us with details to activate the profile</h2>
              <div class="form_group" :class="{ 'has_error': $v.name.$error }">
                <input type="text" placeholder="Full Name" name="name" v-model="$v.name.$model"/>
                <label class="error" v-if="!$v.name.required && $v.name.$error">Name is required</label>
                <label class="error" v-if="!$v.name.minLength && $v.name.$error">Name must have at least
                  {{ $v.name.$params.minLength.min }} letters.
                </label>
              </div>
              <div class="form_group" :class="{ 'has_error': $v.email.$error }">
                <input type="email" placeholder="Email" name="email" v-model="$v.email.$model"/>
                <label class="error" v-if="!$v.email.required && $v.email.$error">Email is required</label>
                <label class="error" v-if="!$v.email.email && $v.email.$error">Please provide a valid email</label>
              </div>
              <div class="form_group" :class="{ 'has_error': $v.educationLevel.$error }">
                <div class="styled_select" >
                  <select name="educationLevel" id="educationLevel" v-model="$v.educationLevel.$model">
                    <option value="">Select Education Level</option>
                    <option v-for="level in educationLevels" :value="level.id" :key="level.id">{{ level.name }}</option>
                  </select>
                </div>
                <label class="error" v-if="!$v.educationLevel.required && $v.educationLevel.$error">Education Level is required</label>
              </div>
              <div class="form_group" :class="{ 'has_error': $v.yearOfBirth.$error }">
                <div class="styled_select" >
                  <select name="yearOfBirth" id="yearOfBirth" v-model="$v.yearOfBirth.$model">
                    <option value="">Select Year of Birth</option>
                    <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
                  </select>
                </div>
                <label class="error" v-if="!$v.yearOfBirth.required && $v.yearOfBirth.$error">Year of birth is required</label>
              </div>
            </div>
            <!--Step three form ends-->
          </div>
          <input type="hidden" name="slot_code" :value="slotCode" />
          <div class="modal-footer">
            <button type="button" @click.prevent="showStart=true;showMainForm=false;" class="page_cta inverse">Back</button>
            <button type="submit" class="page_cta">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import {required, minLength, maxLength, email} from 'vuelidate/lib/validators'
import {mapActions, mapState} from "vuex";
import ProfileService from "@/helpers/assessments/ProfileService";
export default {
  name: "RegistrationModal",
  props: ['slotCode'],
  data(){
    return{
      profileType:"",
      showStart:true,
      showMainForm:false,
      name:"",
      email:"",
      educationLevel:"",
      yearOfBirth:"",
      submitError:false,
      submitErrorText:false,
    }
  },
  validations: {
    name:{
      required,
      minLength:minLength(2),
      maxLength:maxLength(50)
    },
    email:{
      required,
      email,
    },
    educationLevel:{
      required
    },
    yearOfBirth:{
      required
    }
  },
  mounted() {
    //get the dropdown options
    this.getProfileBuild({app:this});
  },
  computed:{
    ...mapState('profile',{
      profile : state => state.profile,
      educationLevels : state => state.educationLevels,
    }),
    years(){
      let current_year = new Date().getFullYear();
      let years = [];
      let stopYear = current_year-100
      for(let i = current_year; i > stopYear; i--){
        years.push(i);
      }
      return years;
    }
  },
  methods:{
    ...mapActions('profile',[
      'getProfileBuild'
    ]),
    //check the profile type to show the correct form
    checkProfileType(){
      //if this is the student prefill the form details
      if(this.profileType === 'student'){
          this.name= this.profile.full_name;
          this.email = this.profile.email;
      }

      //show the next form and hide the first step
      this.showStart = false
      this.showMainForm = true
    },

    //method to submit the registration details
    async registerProfile(){
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.submitError = true
      } else {

        //start loading
        this.$nuxt.$loading.start()

        //create the form data for submission
        let formData = new FormData();
        formData.append('slot_number',this.slotCode);
        formData.append('email_address',this.email);
        formData.append('education_level',parseInt(this.educationLevel));
        formData.append('year_of_birth',parseInt(this.yearOfBirth));
        formData.append('full_names',this.name);

        //Register the profile
        let response = await ProfileService.submitProfile(this,formData);
        if(response.status === 200){


          let responseData = response.data;
          if (responseData.status) {

            //finish loading
            this.$nuxt.$loading.finish()

            //reload the page
            location.reload()
          }else{
            this.submitError = true;
            this.submitErrorText = responseData.message;
          }
        }else{
          //finish loading
          this.$nuxt.$loading.finish()

          this.submitError = true;
          this.submitErrorText = "Details could not be submitted! Try again";
        }
      }
    }
  }
}
</script>

<style scoped>

</style>

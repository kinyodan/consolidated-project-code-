import ProfileService from "@/helpers/assessments/ProfileService";
import ApplicationsService from "@/helpers/ApplicationsService";
import RecommendationsService from "@/helpers/RecommendationsService";

//the profile state
export const state = () =>({
  profile:{},
  slots:[],
  educationLevels:[],
  activeSlot:"",
  applications:[],
  application:"",
  currentEducationID: "",
  education_systems:[],
})

//set the mutations
export const mutations ={
  SET_PROFILE(state, profile) {
    state.profile = profile
  },
  SET_SLOTS(state, slots) {
    state.slots = slots
  },
  SET_EDUCATION_LEVELS(state, educationLevels) {
    state.educationLevels = educationLevels
  },
  SET_APPLICATIONS(state, applications) {
    state.applications = applications
  },
  SET_APPLICATION(state, application) {
    state.application = application
  },
  SET_ACTIVE_SLOT(state, slotCode) {
    state.activeSlot = slotCode
  },
  SET_CURRENT_EDUCATION_SYSTEM_ID(state, education_system_id) {
    state.currentEducationID = education_system_id
  },
  SET_EDUCATION_SYSTEMS(state, education_systems){
    state.education_systems =  education_systems
  }
}

//set the methods
export const actions ={
  //get the user profile
  async getProfile({commit}, {app}){
    try {
      let response = await ProfileService.getProfile(app);
      //check if we got the profile
      if (response.data.status) {
        let data = response.data.data
        commit('SET_PROFILE', data.profile)

        //Get Education Systems based on country code from profile
        if(data.profile){
          let country_code = data.profile.country_code;
          let education_system_response = await RecommendationsService.getEducationSystems(country_code)
          commit('SET_EDUCATION_SYSTEMS',education_system_response.data.data)
          localStorage.setItem("country_code", country_code)
        }
        commit('SET_SLOTS', data.profile.slots)
      }
    }catch (e) {
      //console.log(e)
      //TODO:Log the get profile  execution error
    }
  },
  async getApplications({commit}, {app,formData}){
      let applicationsResponse = await ApplicationsService.getStudentApplications(app, formData).then(response=>response.data)
      if(applicationsResponse){
        commit('SET_APPLICATIONS',applicationsResponse.data)
      }
  },
  //get the user profile
  async getProfileBuild({commit}, {app}){
    try {
      let response = await ProfileService.getProfileBuild(app);
      //check if we got the profile
      if (response.data.status) {
        let data = response.data.data
        commit('SET_EDUCATION_LEVELS', data.education_level)
      }
    }catch (e) {
      //console.log(e)
      //TODO:Log the get profile build execution error
    }
  },
  //set the active slot for the profile
  setActiveSlot({commit},slotCode){
    commit('SET_ACTIVE_SLOT', slotCode)
  },
  getApplication({state,commit},opportunity_id){
    let appDetails = state.applications.find(application => application.opportunity_id === opportunity_id)
    commit('SET_APPLICATION', appDetails)
  },
  changeCurrentEducationSystemID({state,commit}, education_system_id){
    console.log("Education system ID: " + education_system_id)
    commit('SET_CURRENT_EDUCATION_SYSTEM_ID', education_system_id)
  }
}

//getters
export const getters = {
}

import DashboardService from "@/helpers/assessments/DashboardService";

//the profile state
export const state = () =>({
  activeSlot:"",
  assessment:[],
  assessmentType:[],
  attempts:[],
  report:[],
  recommendedPathways:[]
})

//set the mutations
export const mutations ={
  SET_ACTIVE_SLOT(state, slotCode) {
    state.activeSlot = slotCode
  },
  SET_ASSESSMENT(state, assessment) {
    state.assessment = assessment
  },
  SET_REPORT(state, report) {
    state.report = report
  },
  SET_ASSESSMENT_TYPE(state, assessmentType) {
    state.assessmentType = assessmentType
  },
  SET_ASSESSMENT_ATTEMPTS(state, attempts) {
    state.attempts = attempts
  },
  SET_RECOMMENDED_PATHWAYS(state, pathways) {
    state.recommendedPathways = pathways
  },
}

//set the methods
export const actions ={
  //get the user profile
  async getDashboard({commit}, {app,slotCode}){
    try {
      let response = await DashboardService.getDashboard(app,slotCode);
      //check if we got the profile
      if (response.data.status) {
        let data = response.data.data
        commit('SET_ASSESSMENT_ATTEMPTS', data.assessment.linked_assessments[0].attempt)
        commit('SET_ASSESSMENT', data.assessment)
        commit('SET_ASSESSMENT_TYPE', data.assessment.linked_assessments[0].assessment_type)
        commit('SET_REPORT', data.assessment.linked_assessments[0].attempt[0].results)

        //set recommended pathways
        let pathwaysData = []
        if(data.assessment.linked_assessments[0].attempt && data.assessment.linked_assessments[0].attempt.length > 0){
          if(data.assessment.linked_assessments[0].attempt[0] && data.assessment.linked_assessments[0].attempt[0].recommended_career_paths){
            if(typeof data.assessment.linked_assessments[0].attempt[0].recommended_career_paths === "string"){
              let recArray = data.assessment.linked_assessments[0].attempt[0].recommended_career_paths.substring(1).slice(0,-1).split('"').join('').split(",")
              if(recArray.length > 0){
                for (const item of recArray) {
                  //get the details and pass them to main array
                  let pathwayData = new FormData()
                  pathwayData.append('name', item)
                  let innerResponse = await DashboardService.getRecommendedPathwayDetails(app, pathwayData).then(response=>response.data);
                  if(innerResponse.status){
                    let responseData = innerResponse.data
                    if(responseData){
                      pathwaysData.push(responseData.items[0])
                    }
                  }
                }
              }

            }
          }
        }
        commit('SET_RECOMMENDED_PATHWAYS', pathwaysData)
        //set recommended pathways
      }
    }catch (e) {
      //console.log(e)
      //TODO:Log the get profile  execution error
    }
  },
  //set the active slot for the profile
  setActiveSlot({commit},slotCode){
    commit('SET_ACTIVE_SLOT', slotCode)
  }
}

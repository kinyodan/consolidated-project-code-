//the recommendations state
import RecommendationsService from "@/helpers/RecommendationsService";

export const state = () => ({
  showPathwayCategories: false,
  showPathwayCategorySubjects: false,
  showPathwayCategorySubjectsHelp: false,
  pathwayCourseCategories: [],
  selectedPathway:"",
  selectedCategory:"",
  pathwayCategorySubjects: [],
  pathwayCategoryOtherSubjects: [],
})

//set the mutations
export const mutations = {
  SET_SHOW_PATHWAY_CATEGORIES_STATUS(state, status) {
    state.showPathwayCategories = status
  },
  SET_TOGGLE_PATHWAY_CATEGORY_SUBJECTS_STATUS(state, status) {
    state.showPathwayCategorySubjects = status
  },
  SET_TOGGLE_PATHWAY_CATEGORY_SUBJECTS_HELP_STATUS(state, status) {
    state.showPathwayCategorySubjectsHelp = status
  },
  SET_PATHWAY_COURSE_CATEGORIES(state, pathways) {
    state.pathwayCourseCategories = pathways
  },
  SET_PATHWAY_PRIMARY_CATEGORY_SUBJECTS(state, subjects) {
    state.pathwayCategorySubjects = subjects
  },
  SET_PATHWAY_OTHER_CATEGORY_SUBJECTS(state, subjects) {
    state.pathwayCategoryOtherSubjects = subjects
  },
  SET_SELECTED_PATHWAY_NAME(state, name) {
    state.selectedPathway = name
  },
  SET_SELECTED_CATEGORY_NAME(state, name) {
    state.selectedCategory = name
  }
}

//set the methods
export const actions = {
  //show and hide the pathway course categories section
  togglePathwayCategories({commit}, status) {
    commit('SET_SHOW_PATHWAY_CATEGORIES_STATUS', status)
  },
  togglePathwayCategorySubjects({commit}, status) {
    commit('SET_TOGGLE_PATHWAY_CATEGORY_SUBJECTS_STATUS', status)
  },
  togglePathwayCategorySubjectsHelp({commit}, status) {
    commit('SET_TOGGLE_PATHWAY_CATEGORY_SUBJECTS_HELP_STATUS', status)
  },
  setSelectedPathwayName({commit},name){
    commit('SET_SELECTED_PATHWAY_NAME', name)
  },
  setSelectedCategoryName({commit},name){
    commit('SET_SELECTED_CATEGORY_NAME', name)
  },
  //get the pathway course categories
  async getPathwayCourseCategories({commit}, {app, pathwayData}) {
    try {
      let response = await RecommendationsService.getPathwayCourseCategories(app, pathwayData).then(response=>response.data);
      if(response.status){
        let responseData = response.data
        if(responseData){
          commit('SET_PATHWAY_COURSE_CATEGORIES', responseData.items)
        }
      }
    } catch (e) {
    }
  },
  //get the pathway course categories
 async getPathwayCategorySubjects({commit}, {app, discipline, categoryData, education_type,country_code}) {
     let set_country = country_code;
     if(country_code==undefined){
       set_country = localStorage.getItem("country_code");
     }
    try {
      //get primary subjects
      categoryData.append('is_primary',1)
      categoryData.append('country_code',set_country)
      categoryData.append('education_type_id', education_type)
      let response = await RecommendationsService.getPathwayCategorySubjects(app, discipline, categoryData).then(response=>response.data);
        let responseData = response.data
      if(response.status){
        if(responseData){
          if(responseData.items && responseData.items.length > 0){
            commit('SET_PATHWAY_PRIMARY_CATEGORY_SUBJECTS', responseData)
          }else{
            commit('SET_PATHWAY_PRIMARY_CATEGORY_SUBJECTS', responseData)
          }
        }
      }

    } catch (e) {
    }
  },
  getPathwayCategoryOtherSubjects(){

  }
}

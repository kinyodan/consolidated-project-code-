import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  addSchool(data){
    return apiPostClient.post(`/api/schools/add-school-details`, data)
  },

  listSchools(){
    return listApiPostClient.post(`/api/schools/list-schools`)
  },

  updateSchool(school_id,data){
  return apiPostClient.post(`/api/schools/${school_id}/update-school-details`, data)
  },

  deleteSchool(school_id){
    return apiPostClient.post(`/api/schools/${school_id}/delete-school-details`)
  },

  showSchool(school_id){
    return apiPostClient.post(`/api/schools/${school_id}/show-school-details`)
  },
}

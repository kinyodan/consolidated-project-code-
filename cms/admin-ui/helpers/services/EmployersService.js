import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getlist(employer_type_id,params) {
    return apiPostClient.get( `/pathways/admin/employers`)
  },

  buildEmployer(employer_id,params) {
    return apiPostClient.get( `/pathways/admin/employers/build`)
  },
    
  getEmployerCountries(employer_id,params) {
    return apiPostClient.get( `/pathways/admin/employer-countiries//${employer_id}`)
  },

  addEemployers(params){
    return apiPostClient.post('/pathways/admin/employers/add', params)
  },

  updateEmployers(Specialization_id, params){
    return apiPostClient.post(`/pathways/admin/employers/update/${employer_id}`, params)
  },

  delete(Specialization_id, params){
    return apiPostClient.post(`/pathways/admin/employers/${employer_id}/delete`, params)
  },
  
}

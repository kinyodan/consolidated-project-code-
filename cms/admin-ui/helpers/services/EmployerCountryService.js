import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getlist(employer_type_id,params) {
    return apiPostClient.get( `/pathways/admin/employer-countries`)
  },

  buildEmployerCountry(employer_id,params) {
    return apiPostClient.get( `/pathways/admin/employer-countries/build`)
  },
    
  getEmployerCountry(employer_id,params) {
    return apiPostClient.get( `/pathways/admin/employer-countries/${employer_id}`)
  },

  addEmployerCountry(params){
    return apiPostClient.post('/pathways/admin/employers/add', params)
  },

  updateEmployerCountry(Specialization_id, params){
    return apiPostClient.post(`/pathways/admin/employers/update/${Specialization_id}`, params)
  },


  
}

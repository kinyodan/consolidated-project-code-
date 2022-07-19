import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getlist() {
    return apiPostClient.get( `/pathways/admin/eligibility-types`)
  },

  postEligibiltyType(params){
    return apiPostClient.post('/pathways/admin/eligibility-types', params)
  },

  getEligibiltyTypes(params){
    return apiPostClient.get('/pathways/admin/eligibility-types')
  },

  addEligibilityType(eligibilty_id, params){
    return apiPostClient.post(`/pathways/admin/eligibility-types/update/${eligibiltytype_id}`, params)
  },

  updateEligibiltyType(eligibilty_id, params){
    return apiPostClient.post(`/pathways/admin/eligibility-types/update/${eligibiltytype_id}`, params)
  },

  getlist_filtered(eligibilty_id, params){
    return apiPostClient.get(`/pathways/admin/eligibility-types/update/${eligibiltytype_id}`, params)
  },

  

}

import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getlist() {
    return apiPostClient.get( `/pathways/admin/eligibility`)
  },

  getpage(page,params) {
      return apiPostClient.get( `/pathways/admin/eligibility?page=${page}`,params)
  },

  postEligibilty(params){
    return apiPostClient.post('/pathways/admin/employer-types/add', params)
  },

  buildEligibilty(params){
    return apiPostClient.get('/pathways/admin/eligibility/build')
  },

  getEligibilty(eligibilty_id, params){
    return apiPostClient.get(`/pathways/admin/eligibility/${eligibilty_id}`)
  },

  getEligibiltyTypes(params){
    return apiPostClient.get('/pathways/admin/eligibility-types')
  },


  addEligibility(params){
    return apiPostClient.post(`/pathways/admin/eligibility/add`, params)
  },

  updateEligibilty(eligibilty_id, params){
    return apiPostClient.post(`/pathways/admin/employer-types/${eligibilty_id}/update`, params)
  },

  deleteligibilty(eligibilty_id, params){
    return apiPostClient.post(`/pathways/admin/eligibility/${eligibilty_id}/delete`, params)
  },

}

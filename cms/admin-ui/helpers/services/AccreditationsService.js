import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  addaccreditations(institution_code, params){
    return apiPostClient.post(`/institutions/admin/${institution_code}/accreditations/add`,params)
  },
 

  updateaccreditation(institution_code,accreditation_id, params){
    return apiPostClient.post(`/institutions/admin/${institution_code}/accreditations/${accreditation_id}/update`,params)
  },


  buildAccreditations(pathway_id){
    return apiPostClient.post(`/pathways/admin/build-request`)
  },

  getAccreditationsList(institution_code){
    return apiPostClient.get(`/institutions/admin/${institution_code}/accreditations`)
  },
 
  getAccreditationsListPage(institution_code,page,params) {
      return apiPostClient.get(`/institutions/admin/${institution_code}/accreditations?page=${page}`,params)
  },

  getAccreditation(institution_code,accreditation_id){
    return apiPostClient.get(`/institutions/admin/${institution_code}/accreditations/${accreditation_id}`)
  },

  deleteAccreditation(institution_code,accreditation_id){
    return apiPostClient.post(`/institutions/admin/${institution_code}/accreditations/${accreditation_id}/delete`)
  },

   

}

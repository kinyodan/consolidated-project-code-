import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  getlist(page,params) {
    return apiPostClient.get(`/pathways/admin/specializations`,params)
  },

  getpage(page,params) {
    if(page) {
      return apiPostClient.get(`/pathways/admin/specializations?page=${page}`,params)
    }else{
      return apiPostClient.get(`/pathways/admin/specializations`,params)
    }
  },

  getPathwaySpecialization(pathway_id,params) {
    return apiPostClient.get( `/pathways/admin/${pathway_id}/specializations`)
  },


  getSpecializationEligibilities(specialization_id,params) {
    return apiPostClient.get( `/pathways/admin/specializations/${pathway_id}`)
  },

  getSpecializationEmployers(specialization_id,params) {
    return apiPostClient.get( `/pathways/admin/specializations/${pathway_id}`)
  },

  getSpecialization(specialization_id,params) {
    return apiPostClient.get( `/pathways/admin/specializations/${specialization_id}`)
  },

  buildSpecialization() {
    return apiPostClient.get(`/pathways/admin/specializations/build/1`)
  },

  buildSpecializationPathwaysList() {
    return apiPostClient.get(`/pathways/admin/eligibility/build`)
  },

  addSpecialization(params){
    return apiPostClient.post('/pathways/admin/specializations/add', params)
  },

  updateSpecialization(specialization_id, params){
    return apiPostClient.post(`/pathways/admin/specializations/${specialization_id}/update`, params)
  },
  
  deleteSpecialization(specialization_id){
    console.log("posting id for deletion")
    console.log(specialization_id)
    return apiPostClient.post(`/pathways/admin/specializations/${specialization_id}/delete`)
  },


  get_data(specialization_id,params) {
    return apiPostClient.get( `/pathways/admin/specializations/${specialization_id}`)
  },

  link_employer(specialization_id, params){

    return apiPostClient.post( `/pathways/admin/specializations/${specialization_id}/link-employer`,params)

  },

  unlink_employer(specialization_id,params){
    return apiPostClient.post( `/pathways/admin/specializations/${specialization_id}/unlink-employer`, params)

  },

  getemployers(country_id){
    return apiPostClient.get( `pathways/admin/employers?country_id=${country_id}`);

  }

  


}

import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  getCountries(params){
    return apiPostClient.get(`/pathways/admin/countries/`)
  },
 
  getpathways_page(page,params) {
      return apiPostClient.get(`/pathways/admin?page=${page}`,params)
  },

  getpathways() {
    return apiPostClient.get(`/pathways/admin`)
  },

  buildPathways(pathway_id){
    return apiPostClient.post(`/pathways/admin/build-request`)
  },

  getPathwayData(pathway_id){
    return apiPostClient.get(`/pathways/admin/${pathway_id}`)
  },
 
  addPathway(params){
    return apiPostClient.post(`/pathways/admin/add`,params)
  },
 
  updatePathway(pathway_id){
    return apiPostClient.post(`pathways/admin/${pathway_id}/update`)
  },

  deletePathway(pathway_id){
    return apiPostClient.post(`pathways/admin/${pathway_id}/delete`)
  },

 
 

}

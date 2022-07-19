import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getlist(page,params) {
    return apiPostClient.get(`/pathways/admin/employer-types`)
  },

  getpages(page,params) {
    if(page) {
      return apiPostClient.get(`/pathways/admin/employer-types?page=${page}`,params)
    }else{
      return apiPostClient.get(`/pathways/admin/employer-types`)
    }
  },

  getEmployertype(employer_type_id){
    return apiPostClient.get(`/pathways/admin/employer-types/${employer_type_id}`)
  },

  addEmployertypes(params){
    console.log("im here at the service")
    return apiPostClient.post('/pathways/admin/employer-types/add', params)
  },

  updateEmployertypes(employer_type_id, params){
    return apiPostClient.post(`/pathways/admin/employer-types/${employer_type_id}/update`, params)
  }, 

  delete(employer_type_id, params){
    return apiPostClient.post(`/pathways/admin/employer-types/${employer_type_id}/delete`, params)
  },

}

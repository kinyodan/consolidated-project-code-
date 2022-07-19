import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  list(page,country_id,term){
    if(page) {
      return apiPostClient.post(`/courses/admin/list-education-types?search_term=${term}&country_id=${country_id}&age=${page}`,params)
    }else{
      return apiGetClient.get(`/courses/admin/list-education-types?search_term=${term}&country_id=${country_id}`)
    }
  },

  create(name,country_id){
    return apiGetClient.post(`/courses/admin/create-education-types?education_type_name=${name}&country_id=${country_id}`)
  },

  update(id,name,country_id){
    return apiGetClient.post(`/courses/admin/${id}/update-education-type?education_type_name=${name}&country_id=${country_id}`)
  },

  delete(id){
    return apiPostClient.post(`/courses/admin/${id}/delete-education-type`)
  },

}

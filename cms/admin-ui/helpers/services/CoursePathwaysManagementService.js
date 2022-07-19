import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  build(){
    return apiGetClient.get(`/courses/admin/build-courses-pathways`)
  }, 

  buildGrades(){
    console.log("buildGrades")
    return apiGetClient.get(`/courses/admin/build-grades`)
  },

  listItems(){
    return apiGetClient.get(`/courses/admin/list-courses-pathways`)
  },

  createItem(data){
    return apiGetClient.post(`/courses/admin/create-courses-pathways?career_pathways_id=${data.pathway}&academic_disciplines_id=[${data.academic_disciplines}]`)
  },

  getToEdit(item_id){
    return apiGetClient.get(`/courses/admin/${item_id}/edit-courses-pathways`)
  },

  updateItem(cluster_id,cluster_name,country_id){
    return apiGetClient.post(`/courses/admin/${cluster_id}/update-clusters?cluster_name=${cluster_name}&country_id=${country_id}`)
  },

  deleteItem(cluster_id){
    return apiPostClient.post(`/courses/admin/${cluster_id}/delete-courses-pathways`)
  },

}

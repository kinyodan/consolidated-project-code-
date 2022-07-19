import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  build(){
    return apiGetClient.get(`courses/admin/build-clusters`)
  },
  buildGrades(){
    return apiGetClient.get(`/courses/admin/build-grades`)
  },
  listClusters(){
    return apiGetClient.get(`/courses/admin/list-clusters`)
  },
  createCluster(cluster_data){
    return apiGetClient.post(`/courses/admin/create-cluster?country_id=${cluster_data.country_id}&cluster_name=${cluster_data.cluster_name}`)
  },
  getClusterToEdit(cluster_id){
    return apiGetClient.get(`/courses/admin/${cluster_id}/edit-clusters`)
  },
  updateCluster(cluster_id,cluster_name,country_id){
    return apiGetClient.post(`/courses/admin/${cluster_id}/update-clusters?cluster_name=${cluster_name}&country_id=${country_id}`)
  },
  deleteClusters(cluster_id){
    return apiPostClient.post(`/courses/admin/${cluster_id}/delete-clusters`)
  },
  getClustersSubjects(cluster_id,country_id,page){
    return apiPostClient.get(`/courses/admin/${cluster_id}/select-cluster-subject?country_id=${country_id}page=${page}`)
  },
  createClustersSubjects(params){
    return apiPostClient.post(`/courses/admin/create-cluster-subject?cluster_id=${params.cluster_id}&subject_id=[${params.subject_ids}]&education_type_id=${params.education_type_id}&country_id=${params.country_id}&is_primary=${params.is_primary}&career_pathway_id=${params.career_pathway_id}&course_code=${params.course_code}`)
  },
  deleteClustersSubjects(cluster_subject_id){
    return apiPostClient.post(`/courses/admin/${cluster_subject_id}/delete-cluster-subject`)
  },

}

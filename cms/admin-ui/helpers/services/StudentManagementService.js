import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  listSubjects(page){
    if(page) {
      return apiPostClient.post(`/courses/admin/list-subjects?page=${page}`,params)
    }else{
      return apiGetClient.get(`/courses/admin/list-subjects`)
    }
  },
  pageChangeSubjects(page){
    return apiGetClient.get(`/courses/admin/list-subjects?page=${page}`,params)
  },
  createSubject(subject_data){
    return apiGetClient.post(`/courses/admin/create-subject?country_id=${subject_data.country_id}&subject_name=${subject_data.subject_name}`)
  },
  updateSubjects(subject_id,subject_name,country_id){
    return apiGetClient.post(`/courses/admin/${subject_id}/update-subject?subject_name=${subject_name}&country_id=${country_id}`)
  },
  deleteSubject(subject_id){
    return apiPostClient.post(`/courses/admin/${subject_id}/delete-subject`)
  },
}

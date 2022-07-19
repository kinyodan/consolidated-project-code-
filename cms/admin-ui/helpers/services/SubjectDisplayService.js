import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  getListDisplays(education_type_id,current_discipline,country_id,page){
    if(!page){
      page = 1
    }
    console.log("getListDisplays")
    console.log(education_type_id)
    console.log(current_discipline)
    console.log(country_id)
    console.log(page)



    if(country_id){
      return apiPostClient.post(`/courses/rpc/list-display-subjects/${current_discipline}?country_id=${country_id}&education_type_id=${education_type_id}&page=${page}`)
    }else{
      return apiPostClient.post(`/courses/rpc/list-display-subjects/${current_discipline}?page=${page}`)
    }
  },

  getList(discipline_id,page){
    return apiPostClient.post(`/courses/rpc/get-courses-subjects/${discipline_id}?page=${page}`)
  },

  create(data){
    return apiPostClient.post(`/courses/admin/create-display-subjects?education_type_id=${data.education_type_id}&display_order=${data.display_order}&country_id=${data.country_id}&academic_disciplines_id=${data.academic_disciplines_id}&subject_title=${data.subject_title}&subject_title_description=${data.subject_title_description}`)
  },

  update(id,params){
     return apiPostClient.post(`/courses/admin/${id}/update-display-subjects?education_type_id=${params.education_type_id}&display_order=${params.display_order}&country_id=${params.country_id}&academic_disciplines_id=${params.academic_disciplines_id}&subject_title=${params.subject_title}&subject_title_description=${params.subject_title_description}`)
  },

  delete(display_subject_id){
     return apiPostClient.post(`/courses/admin/${display_subject_id}/delete-display-subjects`)
  },

  getitem(display_subject_id){
    return apiPostClient.get(`/courses/admin/${display_subject_id}/select-display-subjects`)

  }


}

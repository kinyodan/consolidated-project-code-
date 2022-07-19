import {apiPostClient} from "./axios-config";
export default {
  addCurriculum(formdata){
    return apiPostClient.post(`/curriculums/add-a-curriculum`,formdata)
  },

  updateCurriculum(curriculum_id,formdata){
    return apiPostClient.post(`/curriculums/${curriculum_id}/update-a-curriculum`,formdata)
  },

  deleteCurriculum(curriculum_id){
    return apiPostClient.post(`/curriculums/${curriculum_id}/delete-a-curriculum`)
  },

  showCurriculum(curriculum_id){
    return apiPostClient.post(`/curriculums/${curriculum_id}/show-a-curriculum`)
  },

  listCurriculum(){
    return apiPostClient.post(`/curriculums/list`,)
  },
}

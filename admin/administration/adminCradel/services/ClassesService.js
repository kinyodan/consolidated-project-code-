import {apiPostClient,apiGetClient} from "./axios-config";
export default {
  addClass(data){
    return apiPostClient.post(`/classes/add-class`, data)
  },

  listClasses(){
    return apiPostClient.post(`/classes/list-classes`)
  },

  updateClass(class_id,data){
  return apiPostClient.post(`/classes/${class_id}/update-a-class`, data)
  },

  deleteClass(class_id){
    return apiPostClient.post(`/classes/${class_id}/delete-a-class`)
  },

  showClass(class_id){
    return apiPostClient.post(`/classes/${class_id}/show-a-class`)
  },
}

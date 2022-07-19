import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  addClass(data){
    return apiPostClient.post(`/api/classes/add-a-class`, data)
  },

  listClasses(){
    return listApiPostClient.post(`/api/classes/list-classes`)
  },

  updateClass(class_id,data){
  return apiPostClient.post(`/api/classes/${class_id}/update-a-class`, data)
  },

  deleteClass(class_id){
    return apiPostClient.post(`/api/classes/${class_id}/delete-a-class`)
  },

  showClass(class_id){
    return apiPostClient.post(`/api/classes/${class_id}/show-a-class`)
  },
}

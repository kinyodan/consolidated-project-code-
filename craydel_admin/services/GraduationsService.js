import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  listGraduations(){
    return listApiPostClient.post(`/api/years/list`,)
  },

  addGraduations(){
    return apiPostClient.post(`/api/years/add-a-year`,)
  },

  updateGraduations(year_id){
    return apiPostClient.post(`/api/years/${year_id}/update-a-year`,)
  },

  deleteGraduations(year_id){
    return apiPostClient.post(`/api/years/${year_id}/delete-a-year`,)
  },

}

import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  addLicence(school_code,data){
    return apiPostClient.post(`/api/licenses/licenses/${school_code}/add-license `, data)
  },

  listLicences(){
    return listApiPostClient.post(`/api/licenses/licenses`)
  },

}

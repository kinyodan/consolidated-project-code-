import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  addBankDetails(school_code,data){
    return apiPostClient.post(`/api/school/${school_code}/bank-details/add`, data)
  },

  listBankDetails(school_code){
    return listApiPostClient.post(`/api/school/${school_code}/bank-details`)
  },

  updateBankDetails(school_code,bank_detail_id,data){
  return apiPostClient.post(`/api/school/${school_code}/bank-details/${bank_detail_id}/update`, data)
  },

  deleteBankDetails(school_code,bank_detail_id){
    return apiPostClient.post(`/api/school/${school_code}/bank-details/${bank_detail_id}/delete`)
  },

  showBankDetails(school_code,bank_detail_id){
    return apiPostClient.post(`/api/school/${school_code}/bank-details/${bank_detail_id}/show`)
  },
}

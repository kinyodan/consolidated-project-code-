import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  addAdmin(school_code,data){
    return apiPostClient.post(`/api/school/${school_code}/school-admins/add`, data)
  },

  listAdmins(school_code){
    return listApiPostClient.post(`/api/school/${school_code}/school-admins`)
  },

  updateAdmin(school_code,school_admin_id,data){
  return apiPostClient.post(`/api/school/${school_code}/school-admins/${school_admin_id}/update `, data)
  },

  deleteAdmin(school_code,school_admin_id){
    return apiPostClient.post(`api/school/${school_code}/school-admins/${school_admin_id}/delete`)
  },

  showAdmin(school_code,school_id){
    return apiPostClient.post(`/api/school/${school_code}/school-admins/${school_id}/show`)
  },
}

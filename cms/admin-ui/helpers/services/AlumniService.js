import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  buildAlumni(institution_code){
    return apiPostClient.get(`/institutions/admin/${institution_code}/alumni/build`)
  },
  getAlumniList(institution_code){
    return apiPostClient.get(`/institutions/admin/${institution_code}/alumni`)
  },
  getAlumniListPage(institution_code,page){
    return apiPostClient.get(`/institutions/admin/${institution_code}/alumni?page=${page}`)
  },
  addAlumni(institution_code,params){
    return apiPostClient.post(`/institutions/admin/${institution_code}/alumni/add`,params)
  },
  getAlumniDetail(institution_code,alumnus_id){
    return apiPostClient.get(`/institutions/admin/${institution_code}/alumni/${alumnus_id}`)
  },
  updateAlumni(institution_code,alumnus_id,params){
    return apiPostClient.post(`/institutions/rpc/alumni-update-profile/${alumnus_id}`,params)
  },
  updateAlumniProfile(alumni_id,formdata){
    return apiPostClient.post(`/institutions/rpc/alumni-update-profile/${alumni_id}`,formdata)
  },
  deleteAlumni(institution_code,alumnus_id){
    return apiPostClient.post(`/institutions/${institution_code}/alumni/${alumnus_id}/delete`)
  },




}

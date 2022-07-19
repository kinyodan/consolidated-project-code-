import {apiGetClient, apiPostClient} from '../axios-config';

//create the required functions
export default {

  getemployerlist() {
   return apiPostClient.get(`/pathways/admin/employers`)

  },

  getemployerpage(page,params) {
    return apiPostClient.get(`/pathways/admin/employers?page=${page}`,params)
  },

  buildEmployer() {
    return apiPostClient.get( `/pathways/admin/employers/build`);
  },
    
  getEmployerCountries(employer_id) {
    return apiPostClient.get( `/pathways/admin/employer-countiries/${employer_id}`);
  },

  getemployerdetails(employer_id){
    return apiPostClient.get(`/pathways/admin/employers/${employer_id}`);
  },

  addEemployers(params){
    return apiPostClient.post('/pathways/admin/employers/add', params);
  },

  updateEmployers(employer_id, params){
    return apiPostClient.post(`/pathways/admin/employers/${employer_id}/update`, params);
  },

  delete(employer_id){
    return apiPostClient.post(`/pathways/admin/employers/${employer_id}/delete`);
  },
  
}

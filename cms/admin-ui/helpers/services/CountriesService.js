import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  getCountries(params){
    return apiPostClient.get(`/pathways/admin/countries/`)
  },
 

}

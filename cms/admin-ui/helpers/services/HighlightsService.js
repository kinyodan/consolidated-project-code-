import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {

  getHighlightiList(institution_code){
    return apiPostClient.get(`/institutions/admin/${institution_code}/highlights`)
  },

  getHighlightiListPage(institution_code,page,params){
    return apiPostClient.get(`/institutions/admin/${institution_code}/highlights?page=${page}`,params)
  },

  getHighlightDetail(institution_code,highlight_id){
    return apiPostClient.get(`/institutions/admin/${institution_code}/highlights/${highlight_id}`)
  },
  
  addHighlight(institution_code,params){
    return apiPostClient.post(`/institutions/admin/${institution_code}/highlights/add`,params)
  },

  updateHighlight(institution_code,highlight_id,params){
    return apiPostClient.post(`/institutions/admin/${institution_code}/highlights/${highlight_id}/update`, params)
  },

  deleteHighlight(institution_code,highlight_id){
    return apiPostClient.post(`/institutions/admin/${institution_code}/highlights/${highlight_id}/delete`)
  },


}



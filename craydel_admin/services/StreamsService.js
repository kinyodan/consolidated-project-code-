import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  listStreams(school_code){
    return listApiPostClient.post(`/api/school/AG/streams?school_code=${school_code}`,)
  },

  addStreams(){
    return apiPostClient.post(`/api/school/AG/streams/add`,)
  },

  updateStreams(stream_id){
    return apiPostClient.post(`/api/school/AG/streams/${stream_id}/update`,)
  },

  deleteStreams(stream_id){
    return apiPostClient.post(`/api/school/AG/streams/${stream_id}/delete`,)
  },

}

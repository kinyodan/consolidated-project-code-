import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  listStreams(school_code,page){
    return listApiPostClient.post(`/api/school/${school_code}/streams?page=${page}`,)
  },

  addStream(school_code,formdata){
    return apiPostClient.post(`/api/school/${school_code}/streams/add`,formdata)
  },

  updateStream(school_code,stream_id){
    return apiPostClient.post(`/api/school/${school_code}/streams/${stream_id}/update`,)
  },

  deleteStream(school_code,stream_id){
    return apiPostClient.post(`/api/school/${school_code}/streams/${stream_id}/delete`,)
  },

  showStream(school_code,stream_id){
    return apiPostClient.post(`/api/school/${school_code}/streams/${stream_id}/show`,)
  },

}

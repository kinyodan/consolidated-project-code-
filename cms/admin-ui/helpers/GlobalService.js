
import {apiGetClient, apiPostClient} from './axios-config'
import Router from 'vue-router';
import VueRouter from 'vue-router'

//create the required functions
export default {

  active(api_url,id) {
    return apiPostClient.get(`${api_url}`,id)
  },

  deactive(api_url,id) {
    return apiPostClient.get(`${api_url}`,id)
  },


  alert_timer(){
      
      var del_flag = this.$route.query.dl
      console.log(this.deleted_record_value );

      if (del_flag === "0"){

        setTimeout(() => {
         this.showAlert = true;
        }, 1000);

      }
      this.showAlert = false;
  },


  redirect_to_pathway(pathway_id){
    if (pathway_id){
      console.log("redirecting to pathway")
      window.location.href = '/pathways/pathways/pathway?id='+pathway_id;
    }

  }

}
import {campaignsGetClient} from "@/helpers/axios-config";
export default {
  getTestimonials(testimonial_type){
    return campaignsGetClient.get(`https://campaigns.craydel.com/wp-json/craydel/v2/testimonials/${testimonial_type}`)
  },

}

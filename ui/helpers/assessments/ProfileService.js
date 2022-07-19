import {apiGetClient} from "@/helpers/axios-config";

export default {
  getProfile(app) {
    return apiGetClient(app).get(`/assessments/get-profile`)
  },
  getProfileBuild(app) {
    return apiGetClient(app).get(`/assessments/student/build-registration/ke`)
  },
  submitProfile(app,profileData){
    return apiGetClient(app).post(`/assessments/student/register`,profileData)
  }
}

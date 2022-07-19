import {apiGetClient} from "@/helpers/axios-config";
export default {
  getDashboard(app,slotCode) {
    return apiGetClient(app).get(`/assessments/student/dashboards/${slotCode}`)
  },
  startAssessment(app,studentCode) {
    return apiGetClient(app).get(`/assessments/student/start-assessment/${studentCode}`)
  },
  getRecommendedPathwayDetails(app, pathwayData){
    return apiGetClient(app).post(`courses/rpc/get-single-pathway-details`,pathwayData)
  }
}

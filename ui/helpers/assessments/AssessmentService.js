import {apiGetClient} from "@/helpers/axios-config";
export default {
  getQuestions(app,slotCode) {
    return apiGetClient(app).get(`/assessments/student/questions/${slotCode}`)
  },
  submitAnswer(app,slotCode,data) {
    return apiGetClient(app).post(`/assessments/student/questions/save-progress/${slotCode}`,data)
  },
}

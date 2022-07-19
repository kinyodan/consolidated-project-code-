import {reportsApiGetClient} from "@/helpers/axios-config";
export default {
  getStudentApplications(app, studentData){
    let url = `/api/tracking/student-application`
    return reportsApiGetClient(app).post(url,studentData)
  },
}

import {headerlessPostClient, headerLessSchoolServiceGetWithCustomURLClient} from "@/helpers/axios-config";
export default {
  getStudentDetails(student_email_address) {
    return headerLessSchoolServiceGetWithCustomURLClient().get(`/school/get-student-details/${student_email_address}`)
  }
}

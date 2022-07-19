import {apiPostClient, listApiPostClient} from "./axios-config";
export default {
  addStudent(school_code,data){
    return apiPostClient.post(`/api/school/${school_code}/students/add`, data)
  },

  listStudents(school_code){
    return listApiPostClient.post(`/api/school/${school_code}/students`)
  },

  updateStudent(school_code,student_id,data){
  return apiPostClient.post(`/api/school/${school_code}/students/${student_id}/update `, data)
  },

  deleteStudent(school_code,student_id){
    return apiPostClient.post(`api/school/${school_code}/students/${student_id}/delete`)
  },

  showStudent(school_code, student_id){
    return apiPostClient.post(`/api/school/${school_code}/students/${student_id}/show`)
  },
}

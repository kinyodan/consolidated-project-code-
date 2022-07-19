import {schoolsApiClient,schoolsApiClientWithAuth} from "~/services/axios-config";
export default {
  getSchool(app){
    return schoolsApiClientWithAuth({app}).get('/school-details');
  },
  getStudents(app,page = 0,itemsPerPage=15,search,sortBy,sortDirection){
    return schoolsApiClientWithAuth({app}).get(`/students?page=${page}&itemsPerPage=${itemsPerPage}&search=${search}&sortBy=${sortBy}&sortDirection=${sortDirection}`);
  },
  getStudent(app,id){
    return schoolsApiClientWithAuth({app}).get(`/students/${id}`);
  },
  getStudentsBuild(app){
    return schoolsApiClientWithAuth({app}).get('/students-build');
  },
  inviteStudent(app,data){
    return schoolsApiClientWithAuth({app}).post('/students',data);
  },
  resendStudentInvite(app,data){
    return schoolsApiClientWithAuth({app}).post(`/students-resend-invite`,data);
  },
  deleteStudent(app,data){
    return schoolsApiClientWithAuth({app}).post(`/students-delete`,data);
  },
  bulkImportStudents(app,data){
    return schoolsApiClientWithAuth({app}).post(`/import-student-list`,data);
  }
}

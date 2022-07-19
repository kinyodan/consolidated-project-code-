import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getCourses(page,params) {
    if(page) {
      return apiPostClient.post(`/courses/admin?page=${page}`,params)
    }else{
      return apiPostClient.post(`/courses/admin`,params)
    }
  },
  getCourse(code) {
    return apiGetClient.get( `/courses/admin/${code}/edit`)
  },
  getCourseBuild() {
    return apiGetClient.get('/courses/admin/build')
  },
  postCourse(course){
    return apiPostClient.post('/courses/admin/create', course)
  },
  uploadCourseList(course){
    return apiPostClient.post('/courses/admin/import', course)
  },
  updateCourse(course,code) {
    return apiPostClient.post( `/courses/admin/${code}/update`, course)
  },
  setFeatured(code) {
    return apiPostClient.post( `/courses/admin/${code}/feature`)
  },
  deleteCourse(course_code,){
    return apiPostClient.post( `/courses/admin/${course_code}/delete`)
  },
  publishCourse(course_code){
    return apiPostClient.post( `/courses/admin/${course_code}/publish`)
  },
  unpublishCourse(course_code){
    return apiPostClient.post( `/courses/admin/${course_code}/unpublish`)
  },
  bulkDeleteCourses(course_codes){
    return apiPostClient.post( `/courses/admin/bulk-delete?course_codes=${course_codes}`)
  },
  bulkPublishCourse(course_codes){
    return apiPostClient.post( `/courses/admin/bulk-publish?course_codes=${course_codes}`)
  },
  bulkUnpublishCourse(course_codes){
    return apiPostClient.post( `/courses/admin/bulk-unpublish?course_codes=${course_codes}`)
  },

}

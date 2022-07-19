import {apiGetClient,SecApiGetClient} from "@/helpers/axios-config";
export default {
  getPathwayCourseCategories(app, pathwayData,page){
    let url = `/courses/rpc/get-courses-pathways`
    if(page){
      url = `${url}?page=${page}`
    }
    return apiGetClient(app).post(url,pathwayData)
  },
  getPathwayCategorySubjects(app, discipline,categoryData,page){
    let url = `/courses/rpc/list-display-subjects/${discipline}`
    if(page){
      url = `${url}?page=${page}`
    }
    return apiGetClient(app).post(url,categoryData)
  },
  getEducationSystems(country_code){
    return SecApiGetClient.get(`/courses/rpc/get-country-education-systems/${country_code}`)
  }

}

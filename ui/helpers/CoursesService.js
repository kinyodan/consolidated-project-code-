import {apiGetClient, apiPostClient, headerlessPostClient, searchGetClient} from "@/helpers/axios-config";

//create the required functions
export default {
  getCourses(page, search_term, params) {
    let filtersString = this.createFacetFilters(params);
    let source = "craydel_courses";
    //check if the source is different
    if("course_source" in params){
      //change the course source
      source = params.course_source
    }

    let queryData = {
      "page": page,
      "query": search_term ? search_term : "",
      "removeWordsIfNoResults":"allOptional",
      "facets": ['*'],
      "maxValuesPerFacet":100,
      "sortFacetValuesBy": 'alpha',
      "hitsPerPage":10,
      "filters":filtersString
    };
    return searchGetClient.post(`/${source}/query`, queryData)
  },
  getCoursesCount() {
    let source = "craydel_courses";
    let queryData = {
      "attributesToRetrieve":null
    };
    return searchGetClient.post(`/${source}/query`, queryData)
  },
  getSearchWidgetCourses(search_term) {
    let source = "craydel_courses";
    let queryData = {
      "query": search_term,
      "attributesToRetrieve": [
        'course_name',
        'discipline'
      ],
      "hitsPerPage":100,
    };
    return searchGetClient.post(`/${source}/query`, queryData)
  },
  getCourse(course_code) {
    return apiGetClient.get(`/courses/${course_code}`)
  },
  getAcademicDisciplines() {
    return apiGetClient.get(`/courses/academic-disciplines`)
  },
  disciplineChunks(disciplines,size) {
    if (disciplines.length <= size) {
      return [disciplines]
    }
    return [disciplines.slice(0, size), ...this.disciplineChunks(disciplines.slice(size), size)]
  },
  getFeaturedCourses() {
    return searchGetClient.post(`/craydel_courses/query`, {
      "filters":"(is_featured:1)"
    })
  },
  getExchangeRates() {
    return apiGetClient.get(`/courses/usd-exchange-rate`)
  },
  submitCourseLead(lead) {
    return apiPostClient.post(`/courses/lead/submit`,lead)
  },
  guestCheckout(guest) {
    return apiPostClient.post(`/users/register-guest`,guest)
  },
  getPopularCourses() {
    return headerlessPostClient.get(`/courses/get-top-courses`)
  },
  createFacetFilters(params) {
    let filtersString = '';
    //let badKeys = ['search_term','course_source','utm_source','utm_medium','utm_campaign','dxid']
    let goodKeys = process.env.COURSE_SEARCH_PARAMS
    for (let key in params) {
      if (goodKeys.includes(key)) {
        let value = params[key]
        if (value) {
          let keyArr = value.split(",")
          let keyFilterString = "("
          if(key === 'standard_fee_payable_usd'){
            keyFilterString += `${key} >= ${keyArr[0]} AND ${key} <= ${keyArr[1]}`
          }else {
            for (let i in keyArr) {
              let keyValue = keyArr[i].split('-').join(' ')
              //chec if negation is needed
              if(keyValue.startsWith('NOT')){
                //remove NOT
                let newKeyValue = keyValue.substring(4)
                keyFilterString += `NOT ${key}:"${newKeyValue}" OR `
              }else{
                keyFilterString += `${key}:"${keyValue}" OR `
              }
            }
            keyFilterString = keyFilterString.slice(0, -4) //remove the trailing OR
          }
          keyFilterString +=")"

          //append to main string
          filtersString +=`${keyFilterString} AND `
        }
      }
    }

    //remove the trailing AND
    filtersString = filtersString.slice(0,-5)
    return filtersString;
  },
  getCountriesOfferingAcademicDiscipline(filter_options){
    let query_data = {
      "query": "",
      "attributesToRetrieve": [
        "country"
      ],
      "filters": this.createFacetFilters(filter_options),
      "hitsPerPage":1000,
    }

    return searchGetClient.post(`/craydel_courses/query`, query_data)
  },


}

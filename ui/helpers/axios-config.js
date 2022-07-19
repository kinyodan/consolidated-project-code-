import axios from "axios";

//get the token
let token = "";

export const campaignsGetClient = axios.create({
  baseURL: `${process.env.CAMPAIGNS_ENDPOINT}`,
  withCredentials: false, // This is the default
  headers: {
  }
})
//get client
export function apiGetClient(app) {
  let token = app.$cookies.get('_token');
  return axios.create({
    baseURL: `${process.env.APIURL}`,
    withCredentials: false, // This is the default
    headers: {
      'token': token,
      'locale': 'en'
    }
  })
}

export const SecApiGetClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
  headers: {
    'token': token,
    'locale': 'en'
  }
})



//reports get client
export function reportsApiGetClient(app) {
  let token = app.$cookies.get('_token');
  console.log(process.env.REPORTING_SERVICE_URL)
  return axios.create({
    baseURL: `${process.env.REPORTING_SERVICE_URL}`,
    withCredentials: false, // This is the default
    headers: {
      'token': token,
      'locale': 'en'
    }
  })
}


//create a post client
  export const apiPostClient = axios.create({
    baseURL: `${process.env.APIURL}`,
    withCredentials: false, // This is the default
    headers: {
      'token': token,
      'locale': 'en',
      'Content-Type': 'multipart/form-data'
    }
  })

//create a auth post client
  export const authApiPostClient = axios.create({
    baseURL: `${process.env.APIURL}`,
    withCredentials: false, // This is the default
    headers: {
      'service': process.env.SERVICECODE,
    }
  })

//create a auth post client without headers
  export function headerlessPostClient({app}) {
    return axios.create({
      baseURL: `${process.env.APIURL}`,
      withCredentials: false, // This is the default
      headers: {}
    })
  }

  //create a auth post client without headers
  export function headerLessSchoolServiceGetWithCustomURLClient() {
    return axios.create({
      baseURL: `${process.env.SCHOOL_SERVICE_URL}`,
      withCredentials: false,
      headers: {}
    })
  }

export const countryGetClient = axios.create({
  baseURL: `${process.env.COUNTRY_DETECT_API_URL}`,
  withCredentials: false, // This is the default
  headers: {
  }
})

/*Algolia APIs*/
export const searchGetClient = axios.create({
  baseURL: `${process.env.ALGOLIA_SEARCH_ENDPOINT}`,
  headers: {
    'X-Algolia-API-Key': `${process.env.ALGOLIA_API_KEY}`,
    'X-Algolia-Application-Id': `${process.env.ALGOLIA_APPLICATION_ID}`,
    'Content-Type': 'application/json'
  }
})


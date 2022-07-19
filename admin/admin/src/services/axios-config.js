import axios from "axios";

//Craydel API resources
let token = `${process.env.APP_TOKEN}`
export const apiGetClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
  headers: {
    'token': token,
    'locale': 'en'
  }
})

export const apiPostClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
  headers: {
    'token': token,
    'locale': 'en',
    'country': '',
    'Content-Type': 'multipart/form-data'
  }
})

export const authApiPostClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
  headers: {
    'service': process.env.SERVICECODE,
  }
})

export const headerlessPostClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
  headers: {
  }
})

export const countryGetClient = axios.create({
  baseURL: `${process.env.COUNTRY_DETECT_API_URL}`,
  withCredentials: false, // This is the default
  headers: {
  }
})

/*Algolia APIs*/
export const searchGetClient = axios.create({
  baseURL: `${process.env.TEST_SEARCH_ENDPOINT}`,
  headers: {
    // 'X-Algolia-API-Key': `${process.env.ALGOLIA_API_KEY}`,
    // 'X-Algolia-Application-Id': `${process.env.ALGOLIA_APPLICATION_ID}`,
    'Content-Type': 'application/json'
  }
})

export const searchAnalyticsGetClient = axios.create({
  baseURL: `${process.env.ALGOLIA_SEARCH_ANALYTICS_ENDPOINT}`,
  headers: {
    'X-Algolia-API-Key': `${process.env.ALGOLIA_API_KEY}`,
    'X-Algolia-Application-Id': `${process.env.ALGOLIA_APPLICATION_ID}`,
  }
})

/*Zendesk API*/
export const zendeskPostClient = (email) => {
  return axios.create({
    baseURL: `${process.env.ZENDESK_API_ENDPOINT}`,
    withCredentials: false, // This is the default
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Basic ${email}/token:${process.env.ZENDESK_API_TOKEN}`

    }
  })
}
export const campaignsGetClient = axios.create({
  baseURL: `${process.env.CAMPAIGNS_ENDPOINT}`,
  withCredentials: false, // This is the default
  headers: {
  }
})
export const blogGetClient = axios.create({
  baseURL: `${process.env.BLOG_ENDPOINT}`,
  withCredentials: false, // This is the default
})

//mailjet object
export const mailjetClient = axios.create({
  baseURL: `${process.env.MJ_API_URL}`,
  headers: {
    'Authorization': `Bearer ${process.env.MJ_AUTH_KEY}`,
    "Access-Control-Allow-Origin": true,
    'Content-Type': 'application/json'
  }
})

export const analyticsClient = axios.create({
  baseURL: `${process.env.ANALYTICS_SERVICE_URL}`,
  withCredentials: false,
})

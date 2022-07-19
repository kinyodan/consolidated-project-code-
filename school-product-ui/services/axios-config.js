import axios from "axios";

export const schoolsApiClient = axios.create({
  baseURL: `${process.env.SCHOOL_SERVICE_URL}`,
  withCredentials: false, // This is the default
  headers: {

  }
})

export const schoolsApiClientWithAuth = function ({app}){
  return axios.create({
    baseURL: `${process.env.SCHOOL_SERVICE_URL}`,
    withCredentials: false, // This is the default
    headers: {
      Authorization: `Bearer ${app.$cookies.get('_token')}`
    }
  })
}

import axios from "axios";

//get the token
let token = localStorage.getItem('_token')

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

export const blogapiGetClient = axios.create({
  baseURL: `${process.env.SSL_BLOGAPIURL}`,
  withCredentials: false, // This is the default
  headers: {
  }
})

export const sslblogapiGetClient = axios.create({
  baseURL: `${process.env.SSL_BLOGAPIURL}`,
  withCredentials: false, // This is the default
  headers: {
  }
})


export const blogapiPostClient = axios.create({
  baseURL: `${process.env.BLOGAPIURL}`,
  withCredentials: false, // This is the default
  headers: {
    'locale': 'en',
    'Content-Type': 'multipart/form-data'
  }
})


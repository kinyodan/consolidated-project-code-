import axios from "axios";

export const apiPostClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
})

apiPostClient.interceptors.request.use(function (config) {
  config.headers["Authorization"] = "Bearer " + localStorage.getItem("token");
  config.headers["locale"] = 'en';
  config.headers["country"] = '';
  config.headers["Content-Type"] = 'multipart/form-data';
  return config;
});

export const listApiPostClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
})

listApiPostClient.interceptors.request.use(function (config) {
  config.headers["Authorization"] = "Bearer " + localStorage.getItem("token");
  config.headers["locale"] = 'en';
  return config;
});

export const authApiPostClient = axios.create({
  baseURL: `${process.env.APIURL}`,
  withCredentials: false, // This is the default
  headers: {
    'service': "SRVLDSUWYD65N",
  }
});




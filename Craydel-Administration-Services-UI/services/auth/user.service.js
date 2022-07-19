import {authApiPostClient} from '../axios-config'
export default {
  logout() {
    // remove user from local storage to log user out
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  },
  verifyToken(data) {
    return authApiPostClient.post(`${process.env.APIURLMAIN}/users/verify-auth-token`, data);
  },
}

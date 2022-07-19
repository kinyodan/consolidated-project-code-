import {authApiPostClient} from '../axios-config'
export default {
  logout() {
    // remove user from local storage to log user out
    localStorage.removeItem('_token');
    localStorage.removeItem('user');
  },
  verifyToken(data) {
    return authApiPostClient.post('/users/verify-auth-token', data);
  },
}

export default function ({ route, redirect }) {

  const publicPages = ['/account/login', '/account/register', '/account/forgot-password'];
  const authpage = !publicPages.includes(route.path);
  //const loggeduser = localStorage.getItem('user');
  const userToken = localStorage.getItem('_token');

  if (authpage && !userToken) {
      //return redirect('/account/login');
    return redirect(process.env.LOGINREDIRECTURL)
  }
}

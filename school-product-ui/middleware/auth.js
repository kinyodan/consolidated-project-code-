export default function ({app, route, redirect }) {

  const publicPages = ['/login'];
  const authpage = !publicPages.includes(route.path);
  const userToken = app.$cookies.get('_token');

  if (authpage && !userToken) {
    return redirect(`${process.env.LOGINURL}?redirect=${process.env.WEBSITEURL}/login&service=${process.env.SERVICE_CODE}`)
  }
}

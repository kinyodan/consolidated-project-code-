export default function ({ app, req, $device }) {
  const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    let r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  })

  if(!app.$cookies.get('USER_COOKIE_ID')){
    app.$cookies.set('USER_COOKIE_ID', uuid, {
      path: '/',
      maxAge: 60 * 60 * 24 * 30
    })
  }

  if(!app.$cookies.get('USER_VISIT_ID')){
    app.$cookies.set('USER_VISIT_ID', uuid, {
      path: '/',
      maxAge: 60 * 60 * 24
    })
  }

  if(req){
    app.$cookies.set('USER_AGENT', req.headers['user-agent'], {
      path: '/',
      maxAge: 60 * 60 * 24
    })
  }

  if($device){
    app.$cookies.set('USER_DEVICE_DETAILS', $device, {
      path: '/',
      maxAge: 60 * 60 * 24
    })
  }
}

<template>
  <div>

  </div>
</template>

<script>
// import jwt_decode from "jwt-decode";
import {mapState, mapActions} from "vuex"
import UserService from "@/services/auth/user.service";
import VueJwtDecode from "vue-jwt-decode";

export default {
  name: "login",
  mounted() {
    //set the toke and reload
    let token = this.$route.query._token;
    localStorage.setItem('token',token)
    this.setToken(token)
    if (token || localStorage.getItem('token')) {
        this.handleLogin(token);
    } else {
      this.handleRedirect()
    }
  },
  computed:{
    ...mapState('users', {
      token: state => state.token
    }),
  },
  methods: {
    ...mapActions('users', [
      'setToken'
    ]),
    handleLogin(token) {
      this.$cookies.set('_token', token);

      //Update the user cookie
      if (token) {
        this.$cookies.set('_token', token, {
          domain: process.env.COOKIEDOMAIN,
          path: "/",
          secure: process.env.COOKIESECURE,
          maxAge: 60 * 60 * 24 * 7
        });
        this.handleLoginData(token)
      }

      //redirect to the homepage
      location.href = process.env.WEBSITEURL
    },
    handleLoginData(token){
      //verify the token and decode it
      let data = {
        '_token':token
      }
      let self = this;

      UserService.verifyToken(data).then(response=>{
        let data = response.data;
        if(data.status){
          let decoded = VueJwtDecode.decode(token);
          console.log(decoded)
          console.log("decodeduiiiiiiiiiiiiii---------")
          if(decoded){

            let user = {
              id: decoded.user_code,
              username: decoded.email,
              name: decoded.display_name,
              email: decoded.email,
              profile_image : (decoded.profile_picture_url) ? decoded.profile_picture_url : `${process.env.DUMMY_PROFILE_IMAGE_URL}/${decoded.display_name}`
            }

            //set the tokens
            localStorage.setItem('user',JSON.stringify(user));
            localStorage.setItem('_token', token);

            self.$router.push('/');
          }
        }else{
          UserService.logout();
        }
      })
    },
    handleRedirect() {
      location.href = process.env.LOGINREDIRECTURL
    }
  },
}
</script>

<style scoped>

</style>

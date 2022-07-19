<script>
import UserService from "~/helpers/authservice/user.service";
import VueJwtDecode from "vue-jwt-decode";
/**
 * Login component
 */
export default {
  created() {
      let token = this.$route.query._token;
      if(token){
        this.handleLogin(token);
      }else{
        this.handleRedirect()
      }
  },
  methods: {
    handleLogin(token){
      //verify the token and decode it
      let data = {
        '_token':token
      }
      let self = this;
      console.log(token)

      UserService.verifyToken(data).then(response=>{
        let data = response.data;
        if(data.status){
          let decoded = VueJwtDecode.decode(token);
          console.log(decoded)
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
      //localStorage.setItem('_token', token)
      //this.$router.push('/')
    },
    handleRedirect(){
      window.location.href = process.env.LOGINREDIRECTURL
    }
  },
};
</script>

<template>
  <div>
  </div>
</template>

<style lang="scss" module></style>

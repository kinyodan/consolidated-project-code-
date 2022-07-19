<template>
  <div>

  </div>
</template>

<script>
// import jwt_decode from "jwt-decode";
import {mapState, mapActions} from "vuex"

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
      console.log(this.$cookies)
      this.$cookies.set('_token', token);

      //Update the user cookie
      if (token) {
        console.log(token)
        this.$cookies.set('_token', token, {
          domain: process.env.COOKIEDOMAIN,
          path: "/",
          secure: process.env.COOKIESECURE,
          maxAge: 60 * 60 * 24 * 7
        });
      }

      //redirect to the homepage
      location.href = process.env.WEBSITEURL
    },
    handleRedirect() {
      location.href = process.env.LOGINREDIRECTURL
    }
  },
}
</script>

<style scoped>

</style>

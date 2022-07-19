<template>
  <div>

  </div>
</template>

<script>
import jwt_decode from "jwt-decode";
export default {
  name: "login",
  mounted() {
    //create the loading page
   /* this.$nextTick(()=>{
      this.$nuxt.$loading.start()
    })*/

    //set the toke and reload
    let token = this.$route.query._token;
    if (token) {
        this.handleLogin(token);
    } else {
      this.handleRedirect()
    }
  },
  methods: {
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

<template>

</template>

<script>
export default {
  name: "login",
  created() {
    //Update the user cookie
    let token = this.$route.query._token;
    if (token) {
      if(process.env.GA_ENV === 'local'){
        this.$cookies.set('_token', token)
      }else{
        this.$cookies.set('_token', token, {
          domain: process.env.COOKIEDOMAIN,
          path: "/",
          secure: process.env.COOKIESECURE,
          maxAge: 60 * 60 * 24 * 7
        });
      }
    }
    this.$router.push('/')
  }
}
</script>

<style scoped>

</style>

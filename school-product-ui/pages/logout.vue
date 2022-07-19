<template>
  <div>
  </div>
</template>

<script>
export default {
  name: "logout",
  created() {
    //delete the cookie
    if(process.env.GA_ENV === 'local'){
      this.$cookies.remove('_token')
    }else{
      this.$cookies.remove('_token',{
        domain: process.env.COOKIEDOMAIN,
        path: "/",
        secure: process.env.COOKIESECURE,
      });
    }

    //redirect to login page
    // if(process.client){
    //   window.location.href=process.env.CRAYDELWEBSITEURL
    // }
    this.$router.push('/')

  }
}
</script>

<style scoped>

</style>

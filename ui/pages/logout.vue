<template>
  <div>
  </div>
</template>

<script>
export default {
  name: "logout",
  created() {
    //delete the cookie
    this.$cookies.remove('_token');

    //redirect to login page
    location.href=`${process.env.LOGINURL}?redirect=${process.env.WEBSITEURL}/login`
  }
}
</script>

<style scoped>

</style>

<template>
  <section class="welcome-footer">
    <!--<a href="#" class="page_cta" @click="doSomething" >Upgrade to Premium</a>-->
    <a :href="`${url}`" class="free-link">Continue on Free Account</a>
  </section>
</template>

<script>
import {mapState, mapActions} from 'vuex'

export default {
  name: "WelcomeFooter",
  url:"",
  created() {
    this.check_url()
  },
  methods:{
    //map sctions from the profile store
    ...mapActions('profile',['getProfile','getApplications']),
    ...mapActions('dashboard',['getDashboard']),

    check_url(){
      var current_path=this.$route.path;
      if(current_path=="/welcome-confirm"){
         this.url="/dashboard-free?q=true";
      }else{
        this.url="/welcome-confirm";
      }
      return this.url;
    }
  }
}
</script>

<style scoped>

</style>

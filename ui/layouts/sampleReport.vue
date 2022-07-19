<template>
  <div id="app">
    <menu-component style="display: none"></menu-component>
    <profile-top-section style="display: none"></profile-top-section>
    <!--Define the Main Wrapper -->

      <!--Main app content-->
      <Nuxt/>

  </div>
</template>
<script>
import ProfileTopSection from "@/components/ProfileComponents/ProfileTopSection";
import MenuComponent from "@/components/GeneralComponents/MenuComponent";
export default {
  name:"sampleReport",
  head(){
    return{
      script:[
        { hid: 'gtm', src: this.gaEnv === 'production' ? 'js/production-tag-manager.js' : 'js/test-tag-manager.js', defer:true}
      ]
    }
  },
  data() {
    return {
      gaEnv: process.env.GA_ENV,
    }
  },
  components:{
    ProfileTopSection,
    MenuComponent
  }
}
</script>

<style>

</style>

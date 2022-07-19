<template>
    <div class="app_header_left">
      <div class="menu_toggle" @click="showSideMenu">
        <div class="menu_toggle_icon">
          <span></span>
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <a href="/" class="site_logo" v-if="logoUrl.length > 0">
        <img :src="logoUrl" alt="Craydel.com">
      </a>
    </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name:"LogoComponent",
  data(){
    return {
      logoUrl: ""
    }
  },
  async fetch(){
    let school = await SchoolService.getSchool(this).then(response => response.data)
    if(school.status){
      this.logoUrl = school.data.school_logo_url
    }
  },
  methods: {
    showSideMenu(){
      document.body.classList.toggle('nav-open')
    },
  },
  watch: {
    logoUrl(newValue) {
      if(newValue.length) {
        console.log("Value changed")
      }
    }
  },
}
</script>

<style scoped>

</style>

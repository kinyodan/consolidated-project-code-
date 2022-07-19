<template>
  <div id="app" class="app_navigation_collapse">
    <div class="app_wrapper">
      <SidebarMenu></SidebarMenu>
      <div class="app_content">
          <Header></Header>
          <div class="menu_overlay" @click="hideSideMenu"></div>
          <Nuxt/>
      </div>
    </div>
    <SiteSvgIcons></SiteSvgIcons>
  </div>
</template>
<script lang="js">
import Header from "~/components/Header";
import Footer from "~/components/Footer";
import SidebarMenu from "../components/GeneralComponents/SidebarMenu";
import SiteSvgIcons from "../components/GeneralComponents/SiteSvgIcons";

export default {
  name: 'Default',
  components: {
    Header,
    Footer,
    SidebarMenu,
    SiteSvgIcons
  },
  methods: {
    hideSideMenu (){
      document.body.classList.remove('nav-open')
    }
  }

}
</script>

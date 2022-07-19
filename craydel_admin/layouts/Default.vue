<template>
  <div class="full_container">
      <!-- Sidebar  -->
      <nav id="sidebar">
        <div class="sidebar_blog_1">
          <div class="sidebar-header">
            <div class="logo_section">
              <a href="index.html"><img class="logo_icon img-responsive" src="images/logo/logo_icon.png" alt="#" /></a>
            </div>
          </div>
          <div class="sidebar_user_info">
            <div class="icon_setting"></div>
            <div class="user_profle_side">
              <div class="user_img"><img class="img-responsive" src="images/layout_img/user_img.jpg" alt="#" /></div>
              <div class="user_info">
                <h6>John David</h6>
                <p><span class="online_animation"></span> Online</p>
              </div>
            </div>
          </div>
        </div>
        <div class="sidebar_blog_2">
          <h4>General</h4>
          <ul class="list-unstyled components">
              <li v-for="items in navList" class="active">
              <p v-if="items.component==='CNavTitle'" class="p-heading"><strong> {{ items.name }}</strong></p>
              <a v-if="items.component==='CNavMainlist' && items.listType==='mainList'" :href="`${items.to}`" ><i :class="`${items.icon}`"></i>  <span>{{ items.name }}</span></a>
              <a v-if="items.component==='CNavGroup' && items.listType!=='mainList'" href="#dashboard" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i :class="`${items.icon}`"></i> <span>{{ items.name }}</span></a>
              <ul v-if="items.items"  class="collapse list-unstyled" id="dashboard">
                <li v-for="item in items.items">
                  <a :href="`${item.to}`"> <span>{{ item.name }}</span></a>
                </li>
              </ul>

            </li>
          </ul>
        </div>
      </nav>
      <!-- end sidebar -->

    <!-- right content -->
    <div id="content">
      <!-- topbar -->
      <div class="topbar">
        <nav class="navbar navbar-expand-lg navbar-light">
          <div class="full">
            <button type="button" id="sidebarCollapse" class="sidebar_toggle"><i class="fa fa-bars"></i></button>
            <div class="logo_section">
              <a href="index.html"><img class="img-responsive" src="images/logo/logo.png" alt="#" /></a>
            </div>
            <div class="right_topbar">
              <div class="icon_info">
                <ul>
                  <li><a href="#"><i class="fa fa-bell-o"></i><span class="badge">2</span></a></li>
                  <li><a href="#"><i class="fa fa-question-circle"></i></a></li>
                  <li><a href="#"><i class="fa fa-envelope-o"></i><span class="badge">3</span></a></li>
                </ul>
                <ul class="user_profile_dd">
                  <li>
                    <a class="dropdown-toggle" data-toggle="dropdown"><img class="img-responsive rounded-circle" src="images/layout_img/user_img.jpg" alt="#" /><span class="name_user">John David</span></a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="profile.html">My Profile</a>
                      <a class="dropdown-item" href="settings.html">Settings</a>
                      <a class="dropdown-item" href="help.html">Help</a>
                      <a class="dropdown-item" href="#"><span>Log Out</span> <i class="fa fa-sign-out"></i></a>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
      </div>
      <!-- end topbar -->
      <!-- dashboard inner -->
      <div class="midde_cont">
        <nuxt/>
        <!-- footer -->
        <div class="container-fluid">
          <div class="footer">
            <p>Copyright © 2018 Designed by html.design. All rights reserved.<br><br>
              Distributed By: <a href="https://themewagon.com/">ThemeWagon</a>
            </p>
          </div>
        </div>
      </div>
      <!-- end dashboard inner -->
    </div>
  </div>
</template>
<script>
// let ps = new PerfectScrollbar('#sidebar');
import nav from '~/services/nav'

export default {
  name:"Default",
  head: {
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: "Craydel Administration" },
      { name:"keywords" , content:""},
      {name:"description", content:""},
      { name:"author", content:""}
    ],
    link: [
      { rel:"icon" ,href:"images/fevicon.png", type:"image/png" },
      { rel:"stylesheet", href:"css/bootstrap.min.css" },
      { rel:"stylesheet" , href:"style.css"},
      { rel:"stylesheet", href:"css/responsive.css" },
      { rel:"stylesheet", href:"css/colors.css" },
      { rel:"stylesheet" , href:"css/bootstrap-select.css" },
      { rel:"stylesheet", href:"css/perfect-scrollbar.css" },
      { rel:"stylesheet" ,href:"css/custom.css" }
    ],
    script: [
      { src:"https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"},
      { src:"https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"},
      { src: "https://code.jquery.com/jquery-3.5.1.min.js" },
      { src:"js/jquery.min.js"},
      { src:"js/popper.min.js"},
      { src:"js/bootstrap.min.js"},
      { src:"js/animate.js"},
      { src:"js/bootstrap-select.js"},
      { src:"js/owl.carousel.js"},
      { src:"js/Chart.min.js"},
      { src:"js/Chart.bundle.min.js"},
      { src:"js/utils.js"},
      { src:"js/analyser.js"},
      { src:"js/perfect-scrollbar.min.js"},
      { src:"js/chart_custom_style1.js"},
      { src:"js/custom.js"},
    ],
    bodyAttrs: {
      class: 'dashboard dashboard_1'
    }
  },
  data(){
    return {
    navList:[],
    }
  },
  created() {
    this.setNavLIst()
  },
  mounted(){
    let token = this.$route.query._token;

    if (token) {
      localStorage.setItem('token',token)
      this.$cookies.set('_token', token, {
        domain: window.location.hostname,
        path: "/",
        secure: process.env.WEBSITEURL === 'https://craydel.com',
        maxAge: 60 * 60 * 24 * 7
      });
    }
    if(localStorage.getItem('token')) {
      // location.href = process.env.WEBSITEURL
      console.log("process.env.WEBSITEURL")
    }else{
      location.href = process.env.LOGINREDIRECTURL
    }
  },
  methods:{
    setNavLIst(){
      this.navList= nav.setNavLIst()
    }
  }

}
</script>
<style scoped>

</style>

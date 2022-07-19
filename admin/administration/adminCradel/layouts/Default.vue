<template>
  <div id="app" class="">
      <div>
        <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
          <div class="sidebar-brand d-none d-md-flex">
            <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
              <use xlink:href="assets/brand/coreui.svg#full"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="46" height="46" alt="CoreUI Logo">
              <use xlink:href="assets/brand/coreui.svg#signet"></use>
            </svg>
          </div>
          <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
            <template v-for="(item,index) in navList">
              <li v-if="item.component==='CNavTitle'" class="nav-title">{{ item.name }}</li>
              <li v-if="item.component==='CNavItem'"  class="nav-item"><a class="nav-link" :href="`${item.to}`">
                <svg class="nav-icon">
                  <use xlink:href="https://cdn.jsdelivr.net/npm/@coreui/icons/sprites/free.svg#cil-drop"></use>
                </svg>
                {{ item.name }}</a></li>
              <li  v-if="item.component==='CNavGroup'" @click="toggleMenu(`nav-group-${index}`)" class="nav-group" :id="`nav-group-${index}`" arial-pressed="true">
                <a class="nav-link nav-group-toggle" href="#">
                  <svg class="nav-icon">
                    <use xlink:href="https://cdn.jsdelivr.net/npm/@coreui/icons/sprites/free.svg#cil-puzzle"></use>
                  </svg> {{item.name}}
                </a>
                <ul class="nav-group-items">
                  <li v-for="subitem in item.items" class="nav-item"><a class="nav-link" :href="`${subitem.to}`"><span class="nav-icon"></span> {{subitem.name}}</a></li>
                </ul>
              </li>
            </template>
          </ul>
          <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
        </div>

        <div class="section_main_content">
          <Nuxt/>
        </div>
      </div>
  </div>
</template>
<script lang="js">

import nav from "~/services/nav.js"
import Vue from 'vue';
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

export default {
  name: 'Default',
  head(){
    return{
      title: "Craydel Administration",
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { hid: 'description', name: 'description', content: "Craydel Administration" },
        { name:"msapplication-TileColor" ,content:"#ffffff"},
        { name:"msapplication-TileImage" ,content:"assets/favicon/ms-icon-144x144.png"},
        { name:"theme-color", content:"#ffffff"}
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', ref: '/leap-icon.ico' },
        { rel:"apple-touch-icon" ,sizes:"57x57" ,ref:"assets/favicon/apple-icon-57x57.png"},
        { rel:"apple-touch-icon" ,sizes:"60x60" ,ref:"assets/favicon/apple-icon-60x60.png"},
        { rel:"apple-touch-icon" ,sizes:"72x72" ,ref:"assets/favicon/apple-icon-72x72.png"},
        { rel:"apple-touch-icon" ,sizes:"76x76" ,ref:"assets/favicon/apple-icon-76x76.png"},
        { rel:"apple-touch-icon" ,sizes:"114x114" ,href:"assets/favicon/apple-icon-114x114.png"},
        { rel:"apple-touch-icon" ,sizes:"120x120" ,href:"assets/favicon/apple-icon-120x120.png"},
        { rel:"apple-touch-icon" ,sizes:"144x144" ,ref:"assets/favicon/apple-icon-144x144.png"},
        { rel:"apple-touch-icon" ,sizes:"152x152" ,ref:"assets/favicon/apple-icon-152x152.png"},
        { rel:"apple-touch-icon" ,sizes:"180x180" ,ref:"assets/favicon/apple-icon-180x180.png"},
        { rel:"icon", type:"image/png" ,sizes:"192x192" ,ref:"assets/favicon/android-icon-192x192.png"},
        { rel:"icon" ,type:"image/png" ,sizes:"32x32" ,ref:"assets/favicon/favicon-32x32.png"},
        { rel:"icon", type:"image/png" ,sizes:"96x96" ,ref:"assets/favicon/favicon-96x96.png"},
        { rel:"icon", type:"image/png" ,sizes:"16x16" ,ref:"assets/favicon/favicon-16x16.png"},
        { rel: 'stylesheet',href: 'https://use.fontawesome.com/releases/v5.2.0/css/all.css' },
        { href: "https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@4.2.1/dist/css/coreui.min.css" ,rel:"stylesheet"},
        { rel:"stylesheet", href:"https://unpkg.com/simplebar@latest/dist/simplebar.css" },
        { src:"https://unpkg.com/simplebar@latest/dist/simplebar.min.js"},
        { rel:"stylesheet", href:"https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css"},
        { src:"https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"},
        { rel:"stylesheet" , href:"https://cdn.jsdelivr.net/npm/prismjs@1.23.0/themes/prism.css"},
      ],
      script: [
        { src: "https://code.jquery.com/jquery-3.5.1.min.js" },
        { src:"https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@4.2.1/dist/js/coreui.bundle.min.js"}
      ],
    }
  },
  data(){
    return {
      navList:[],
    }
  },
  mounted() {
    let token = this.$route.query._token;
    localStorage.setItem('token',token)

    if (token) {
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

    this.setNavListItems()

  },
  methods: {
   setNavListItems(){
     console.log(nav.setNavLIst())
     this.navList=nav.setNavLIst()
   },
   toggleMenu(selector,class_name){
     let listelement = document.getElementById(selector)
     console.log(listelement)
     listelement.classList.add("show");
   }
  },
}
</script>

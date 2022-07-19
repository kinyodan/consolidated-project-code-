<script>
import {
  authFackMethods
} from "~/store/helpers";

/**
 * Topbar component
 */
export default {
  data() {
    return {
      languages: [{
        flag: require("~/assets/images/flags/us.jpg"),
        language: "en",
        title: "English",
      },

      ],
      current_language: this.$i18n.locale,
      text: null,
      flag: null,
      value: null,
    };
  },
  computed:{
    loggedInUser(){
      return JSON.parse(localStorage.getItem('user'));
    }
  },
  mounted() {
    this.value = this.languages.find((x) => x.language === this.$i18n.locale);
    this.text = this.value.title;
    this.flag = this.value.flag;
  },
  methods: {
    ...authFackMethods,
    /**
     * Toggle menu
     */
    toggleMenu() {
      this.$parent.toggleMenu();
    },
    initFullScreen() {
      document.body.classList.toggle("fullscreen-enable");
      if (
        !document.fullscreenElement &&
        /* alternative standard method */
        !document.mozFullScreenElement &&
        !document.webkitFullscreenElement
      ) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(
            Element.ALLOW_KEYBOARD_INPUT
          );
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    },
    /**
     * Toggle rightsidebar
     */
    toggleRightSidebar() {
      this.$parent.toggleRightSidebar();
    },
    /**
     * Set languages
     */
    setLanguage(locale, country, flag) {
      this.$i18n.locale = locale;
      this.current_language = locale;
      this.text = country;
      this.flag = flag;
    },
    logoutUser() {
      //remove the session items
      localStorage.removeItem('_token');
      localStorage.removeItem('user');
     this.$router.push('/')
    },
  },
};
</script>

<template>
  <header id="page-topbar">
    <div class="navbar-header">
      <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
          <nuxt-link to="/" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="~/assets/images/logo-sm.png" alt height="22"/>
                    </span>
            <span class="logo-lg">
                        <img src="~/assets/images/logo-dark.png" alt height="20"/>
                    </span>
          </nuxt-link>

          <nuxt-link to="/" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="~/assets/images/logo-sm.png" alt height="22"/>
                    </span>
            <span class="logo-lg">
                        <img src="~/assets/images/logo-light.png" alt height="20"/>
                    </span>
          </nuxt-link>
        </div>

        <button @click="toggleMenu" type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn"
                id="vertical-menu-btn">
          <i class="fa fa-fw fa-bars"></i>
        </button>

      </div>

      <div class="d-flex">
        <b-dropdown variant="white" right toggle-class="header-item">
          <template v-slot:button-content>
            <img class :src="flag" alt="Header Language" height="16"/>
            {{ text }}
          </template>
          <b-dropdown-item class="notify-item" v-for="(entry, i) in languages" :key="`Lang${i}`" :value="entry"
                           @click="setLanguage(entry.language, entry.title, entry.flag)"
                           :link-class="{'active': entry.language === current_language}">
            <img :src="`${entry.flag}`" alt="user-image" class="mr-1" height="12"/>
            <span class="align-middle">{{ entry.title }}</span>
          </b-dropdown-item>
        </b-dropdown>


        <div class="dropdown d-none d-lg-inline-block ml-1">
          <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen"
                  @click="initFullScreen">
            <i class="uil-minus-path"></i>
          </button>
        </div>

        <b-dropdown class="d-inline-block" toggle-class="header-item" right variant="white">
          <template v-slot:button-content>
            <img class="rounded-circle header-profile-user" :src="loggedInUser.profile_image" alt="Header Avatar"/>
            <span class="d-none d-xl-inline-block ml-1 font-weight-medium font-size-15">{{ loggedInUser.name }}</span>
            <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
          </template>

          <!-- item-->
          <a class="dropdown-item" @click.prevent="logoutUser">
            <i class="uil uil-sign-out-alt font-size-18 align-middle mr-1 text-muted"></i>
            <span class="align-middle">{{ $t('navbar.dropdown.marcus.list.logout') }}</span>
          </a>
        </b-dropdown>

      </div>
    </div>
  </header>
</template>

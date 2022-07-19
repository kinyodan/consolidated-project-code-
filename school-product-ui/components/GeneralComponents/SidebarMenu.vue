<template>
  <div class="app_navigation">
    <div class="app_navigation_header">
      <nuxt-link to="" class="nav_logo">
        <img src="https://craydel.fra1.cdn.digitaloceanspaces.com/logo/craydel_white.svg" alt="Craydel Career guidance">
      </nuxt-link>
      <a class="menu_collapse" @click="collapseMenu">
        <svg width="14" height="13" viewBox="0 0 14 13" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M5.12326e-05 6.0858L5.79005 0.295797C5.97741 0.109546 6.23087 0.00500483 6.49505 0.00500484C6.75924 0.00500485 7.01269 0.109546 7.20005 0.295797C7.3863 0.48316 7.49084 0.736612 7.49084 1.0008C7.49084 1.26498 7.3863 1.51843 7.20005 1.7058L2.83005 6.0858L7.20005 10.4658C7.3863 10.6532 7.49084 10.9066 7.49084 11.1708C7.49084 11.435 7.3863 11.6884 7.20005 11.8758C7.01269 12.062 6.75924 12.1666 6.49505 12.1666C6.23087 12.1666 5.97741 12.062 5.79005 11.8758L5.12326e-05 6.0858Z"
            fill="#21264D"/>
          <path
            d="M6.20001 6.0858L11.99 0.295798C12.083 0.20207 12.1936 0.127676 12.3154 0.0769071C12.4373 0.0261384 12.568 -4.95165e-08 12.7 -4.3746e-08C12.832 -3.79756e-08 12.9627 0.0261384 13.0846 0.0769071C13.2064 0.127676 13.3171 0.20207 13.41 0.295798C13.5963 0.483161 13.7008 0.736612 13.7008 1.0008C13.7008 1.26498 13.5963 1.51844 13.41 1.7058L9.03001 6.0858L13.41 10.4658C13.5963 10.6532 13.7008 10.9066 13.7008 11.1708C13.7008 11.435 13.5963 11.6884 13.41 11.8758C13.317 11.9695 13.2064 12.0439 13.0846 12.0947C12.9627 12.1455 12.832 12.1716 12.7 12.1716C12.568 12.1716 12.4373 12.1455 12.3154 12.0947C12.1936 12.0439 12.083 11.9695 11.99 11.8758L6.20001 6.0858Z"
            fill="#21264D"/>
        </svg>
      </a>
    </div>
    <div class="app_primary_nav">
      <ul class="nav_menu">
        <li>
          <NuxtLink to="/" class="current" data-tooltip="Dashboard">
            <svg class="menu_svg_item">
              <use xlink:href="#dashboard_icon"></use>
            </svg>
            <span class="menu_item_label">Dashboard</span>
          </NuxtLink>
        </li>
        <li>
          <NuxtLink to="/manage-students" data-tooltip="Manage Students">
            <svg class="menu_svg_item">
              <use xlink:href="#student_icon"></use>
            </svg>
            <span class="menu_item_label">Manage Students</span>
          </NuxtLink>
        </li>
        <li>
          <a href="https://craydel.com/courses" target="_blank" data-tooltip="All Courses">
            <svg class="menu_svg_item">
              <use xlink:href="#session_icon"></use>
            </svg>
            <span class="menu_item_label">All Courses</span>
          </a>
        </li>
        <li>
          <a href="https://craydel.com/top-courses" target="_blank" data-tooltip="Career Resources">
            <svg class="menu_svg_item">
              <use xlink:href="#resource_icon"></use>
            </svg>
            <span class="menu_item_label">Career Resources</span>
          </a>
        </li>
        <li>
          <a href="https://craydel.com/institutions/" target="_blank" data-tooltip="Institutions">
            <svg class="menu_svg_item">
              <use xlink:href="#institution_icon"></use>
            </svg>
            <span class="menu_item_label">Institutions</span>
          </a>
        </li>
        <li>
          <a href="https://craydel.com/blog/" target="_blank" data-tooltip="Blog">
            <svg class="menu_svg_item">
              <use xlink:href="#mycourses_icon"></use>
            </svg>
            <span class="menu_item_label">Blog</span>
          </a>
        </li>
        <li>
          <NuxtLink to="/billing" data-tooltip="Billing">
            <svg class="menu_svg_item">
              <use xlink:href="#transaction_icon"></use>
            </svg>
            <span class="menu_item_label">Billing</span>
          </NuxtLink>
        </li>
        <li class="has_submenu">
          <a class="menu_with_submenu" data-tooltip="Settings" @click="openSubmenu($event)">
            <svg class="menu_svg_item">
              <use xlink:href="#settings_icon"></use>
            </svg>
            <span class="menu_item_label">Settings <v-icon dark small>mdi-chevron-right</v-icon></span>
          </a>
          <div class="submenu">
            <NuxtLink to="/school-profile">School Profile</NuxtLink>
            <NuxtLink to="/manage-classes">Manage Classes</NuxtLink>
            <NuxtLink to="/manage-streams">Manage Streams</NuxtLink>
            <NuxtLink to="/manage-graduations">Manage Graduations</NuxtLink>
            <NuxtLink to="/manage-users">Manage Users</NuxtLink>
          </div>
        </li>

        <!-- <li>
          <NuxtLink to="" data-tooltip="Get Mentorship">
            <svg class="menu_svg_item">
              <use xlink:href="#mydocument_icon"></use>
            </svg>
            <span class="menu_item_label">Get Mentorship</span>
          </NuxtLink>
        </li> -->
      </ul>
      <div class="school-switcher__mobile">
        <school-switcher></school-switcher>
      </div>
      <div class="login_modal_mobile">
        <LoginComponent></LoginComponent>
      </div>
    </div>
  </div>
</template>

<script>


import LoginComponent from "../HeaderComponents/LoginComponent";
import SchoolSwitcher from "@/components/HeaderComponents/SchoolSwitcher";

export default {
  name: "SideBarMenu",
  components: {
    SchoolSwitcher,
    LoginComponent
  },
  data() {
    return {}
  },
  methods: {
    collapseMenu() {
      document.getElementById('app').classList.toggle('app_navigation_collapse')
      document.querySelector('.has_submenu').classList.remove('open')
    },

    openSubmenu(item) {
      const app = document.getElementById('app')
      const menuParent = item.target.closest('.has_submenu')
      if (app.classList.contains('app_navigation_collapse')) {
        app.classList.remove('app_navigation_collapse')

        setTimeout(function () {
          menuParent.classList.add('open')
        }, 500);
      } else {
        if (menuParent.classList.contains('open')) {
          menuParent.classList.remove('open')
        } else {
          menuParent.classList.add('open')
        }
      }

    },
  }
}
</script>

<style>

</style>

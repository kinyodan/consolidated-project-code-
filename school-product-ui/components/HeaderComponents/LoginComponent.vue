<template>
  <div v-if="loaded" class="login_widget">
    <!--    <a :href="loginUrl" class="login_btn" v-if="!displayName">Book a FREE counselling session</a>-->
    <a href="" class="login_trigger" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span v-if="displayName">Hello {{ displayName }}</span>
      <span v-else>Sign In</span>
      <div class="login_trigger_avatar" aria-label="Main navigation menu">
        <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"
             style="display: block; height: 100%; width: 100%; fill: currentcolor;">
          <path
            d="m16 .7c-8.437 0-15.3 6.863-15.3 15.3s6.863 15.3 15.3 15.3 15.3-6.863 15.3-15.3-6.863-15.3-15.3-15.3zm0 28c-4.021 0-7.605-1.884-9.933-4.81a12.425 12.425 0 0 1 6.451-4.4 6.507 6.507 0 0 1 -3.018-5.49c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5a6.513 6.513 0 0 1 -3.019 5.491 12.42 12.42 0 0 1 6.452 4.4c-2.328 2.925-5.912 4.809-9.933 4.809z"></path>
        </svg>
      </div>
    </a>
    <div class="login_modal">
      <template v-if="displayName">
        <ul class="login_menu_item" >
<!--          <li><a :href="workspaceUrl">Dashboard</a></li>-->
        </ul>
      </template>
      <template v-else>
        <ul class="login_menu_item">
          <li><a :href="loginUrl">Sign In</a></li>
          <li><a :href="signupUrl">Register</a></li>
        </ul>
      </template>

      <template v-else-if>
        <ul class="login_menu_item">
          <li v-if="displayName"><a href="/logout">Logout</a></li>
        </ul>
      </template>


    </div>
  </div>
</template>

<script>
import jwt_decode from "jwt-decode";

export default {
  name:"LoginComponent",
  data() {
    return {
      loaded: false
    }
  },
  mounted() {
    //page is ready to be displayed
    this.loaded = true
  },
  computed: {
    loginUrl() {
      return `${process.env.LOGINURL}?redirect=${process.env.WEBSITEURL}/login`
    },
    signupUrl() {
      return `${process.env.LOGINURL}/register?redirect=${process.env.WEBSITEURL}/login`
    },
    workspaceUrl() {
      return `${process.env.WORKSPACEURL}`
    },
    displayName() {
      try {
        let token = this.$cookies.get('_token');
        if (token) {
          let user = jwt_decode(token);
          if (user) {
            return user.first_name
          } else {
            return ""
          }
        } else {
          return ""
        }
      } catch (e) {
        return ""
      }
    }
  }
}
</script>

<style>

</style>

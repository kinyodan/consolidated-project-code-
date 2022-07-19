require('dotenv').config({ path: `.env.${process.env.NODE_ENV}` })


export default {
  devtools: true,
  /*loading: "@components/loading.vue",*/
  /*
   ** Nuxt rendering mode
   ** See https://nuxtjs.org/api/configuration-mode
   */
 /* mode: "spa",*/
  ssr: false, //for spa
  /*
   ** Nuxt target
   ** See https://nuxtjs.org/api/configuration-target
   */
  target: "static",
  /*
   ** Headers of the page
   ** See https://nuxtjs.org/api/configuration-head
   */
  head: {
    meta: [
      { charset: "utf-8" },
      { name: "viewport", content: "width=device-width, initial-scale=1" },
      {
        hid: "description",
        name: "description",
        content: "Responsive Bootstrap 4 Admin Dashboard"
      }
    ],
    link: [{ rel: "icon", type: "image/x-icon", href: "/favicon.ico" }]
  },
  router: {
    // linkExactActiveClass: 'active'
  },
  /*
   ** Global CSS
   */
  css: ["~/assets/scss/app.scss"],
  /*
   ** Plugins to load before mounting the App
   ** https://nuxtjs.org/guide/plugins
   */
  plugins: [
    '~/plugins/index.js',
    '~/plugins/i18n.js',
    "~/plugins/simplebar.js",
    "~/plugins/vue-click-outside.js",
    "~/plugins/vue-apexcharts.js",
    "~/plugins/vuelidate.js",
    "~/plugins/vue-slidebar.js",
    "~/plugins/vue-lightbox.js",
    "~/plugins/vue-chartist.js",
    "~/plugins/vue-mask.js",
    "~/plugins/vue-googlemap.js"
  ],
  /*
   ** Auto import components
   ** See https://nuxtjs.org/api/configuration-components
   */
  components: true,
  /*
   ** Nuxt.js dev-modules
   */
  buildModules: [
    ['@nuxtjs/dotenv']

  ],
  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://bootstrap-vue.js.org
    "bootstrap-vue/nuxt",
    // Doc: https://github.com/nuxt/content
    "@nuxt/content",
    'cookie-universal-nuxt',
  ],

  /*
   ** Content module configuration
   ** See https://content.nuxtjs.org/configuration
   */
  content: {},
  /*
   ** Build configuration
   ** See https://nuxtjs.org/api/configuration-build/
   */
  build: {},

  env: {
        APIURL: "https://api.craydel.online",
        BLOGAPIURL: "http://campaigns.craydel.com/wp-json",
        SSL_BLOGAPIURL: "https://campaigns.craydel.com/wp-json",
        LOGINREDIRECTURL: process.env.REDIRECTURL || "https://accounts.craydel.online?redirect=http://localhost:3000/account/login",
        SERVICECODE: process.env.CODE || "SRV4DRAZM829S",
        DUMMY_PROFILE_IMAGE_URL: process.env.DUMMY_IMAGE_URL || "https://icotar.com/initials",
        ROLLBAR_TOKEN: "899348fe87b844bfb5638360f00ea91b",
      }
};

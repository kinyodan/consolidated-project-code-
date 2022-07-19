export default {
  loading: '~/components/GeneralComponents/LoadingBar.vue',
  // Target: https://go.nuxtjs.dev/config-target
  //target: 'static',
  server: {
    //port: 80, // default: 3000
    host: '0.0.0.0' // default: localhost
  },


  //prevent dynamic routes from adding a prefix to the static folder
  static: {
    prefix: false
  },
  router: {
    base: process.env.ROUTER_BASE || '/',

  },

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'Craydel Career Counselling',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {hid: 'description', name: 'description', content: ''},
      {name: 'HandheldFriendly', content: 'True'},
      {name: 'MobileOptimized', content: '320'},
      {name: 'msapplication-TileColor', content: '#17244F'},
      {name: 'msapplication-TileImage', content: 'images/favicon/ms-icon-144x144.png'},
      {name: 'theme-color', content: '#17244F'},
    ],
    link: [
      {rel: 'manifest', href: 'images/favicon/manifest.json'},
      {rel: 'apple-touch-icon', sizes: '57x57', href: 'images/favicon/apple-icon-57x57.png'},
      {rel: 'apple-touch-icon', sizes: '60x60', href: 'images/favicon/apple-icon-60x60.png'},
      {rel: 'apple-touch-icon', sizes: '72x72', href: 'images/favicon/apple-icon-72x72.png'},
      {rel: 'apple-touch-icon', sizes: '76x76', href: 'images/favicon/apple-icon-76x76.png'},
      {rel: 'apple-touch-icon', sizes: '114x114', href: 'images/favicon/apple-icon-114x114.png'},
      {rel: 'apple-touch-icon', sizes: '120x120', href: 'images/favicon/apple-icon-120x120.png'},
      {rel: 'apple-touch-icon', sizes: '144x144', href: 'images/favicon/apple-icon-144x144.png'},
      {rel: 'apple-touch-icon', sizes: '152x152', href: 'images/favicon/apple-icon-152x152.png'},
      {rel: 'apple-touch-icon', sizes: '180x180', href: 'images/favicon/apple-icon-180x180.png'},
      {rel: 'icon', sizes: '192x192', type: "image/png", href: 'images/favicon/android-icon-192x192.png'},
      {rel: 'icon', sizes: '32x32', type: "image/png", href: 'images/favicon/favicon-32x32.png'},
      {rel: 'icon', sizes: '96x96', type: "image/png", href: 'images/favicon/favicon-96x96.png'},
      {rel: 'icon', sizes: '16x16', type: "image/png", href: 'images/favicon/favicon-16x16.png'},
    ],
    script: [
      {src: 'js/jquery.js', type: 'text/javascript'},
      {src: 'js/bootstrap.min.js', type: 'text/javascript'},
      {src: 'js/bxslider.js', type: 'text/javascript'},
      {src: 'js/slick.min.js', type: 'text/javascript'},
      {src: 'js/modernizr.js', type: 'text/javascript'},
      {src: 'https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.5/lottie.min.js'},
      {src: 'js/main.js', type: 'text/javascript'},
      {src: 'js/confetti.js', type: 'text/javascript'},
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/css/main.css',
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    {src: '~/plugins/ApexChart.js',mode:'client'},
    {src: '~/plugins/Vuelidate.js'},
    {src: '~/plugins/vue-splide.js',mode:'client'},
    {src: '~/plugins/freshDesk.js', mode: 'client'}
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    '@nuxtjs/dotenv'
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    /*'bootstrap-vue/nuxt',*/
    'cookie-universal-nuxt',
    'vue-scrollto/nuxt',
  ],

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },
  env:{
    LOGINURL:process.env.LOGINURL || "https://accounts.craydel.online",
    APIURL:process.env.APIURL || 'https://api.craydel.online/',
    SCHOOL_SERVICE_URL:process.env.SCHOOL_SERVICE_URL || 'https://school-api-service.craydel.online/',
    WEBSITEURL:process.env.WEBSITEURL || "http://192.168.78.220:3000",
    COOKIEDOMAIN:process.env.COOKIEDOMAIN || "192.168.78.220",
    COOKIESECURE:process.env.COOKIESECURE || false,
    GA_ENV:process.env.GA_ENV || 'local',
    MARKETPLACE_URL:process.env.MARKETPLACE_URL || 'https://craydel.com',
    REPORTING_SERVICE_URL:process.env.REPORTING_SERVICE_URL || 'https://reporting.craydel.com',
  }
}

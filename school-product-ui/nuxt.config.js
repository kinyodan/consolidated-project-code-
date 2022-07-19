export default {
  loading: '~/components/LoadingBar.vue',
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: true,

  server: {
    //port: 80, // default: 3000
    host: '0.0.0.0' // default: localhost
  },

  // Global page headers: https://go.nuxtjs.dev/config-head

  head: {
    title: 'Craydel School System',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1, maximum-scale=1'},
      {hid: 'description', name: 'description', content: ''},
      {name: 'HandheldFriendly', content: 'True'},
      {name: 'MobileOptimized', content: '320'},
      {name: 'msapplication-TileColor', content: '#17244F'},
      {name: 'msapplication-TileImage', content: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/ms-icon-144x144.png'},
      {name: 'theme-color', content: '#17244F'},
    ],
    link: [
      {rel: 'manifest', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/manifest.json'},
      {rel: 'apple-touch-icon', sizes: '57x57', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-57x57.png'},
      {rel: 'apple-touch-icon', sizes: '60x60', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-60x60.png'},
      {rel: 'apple-touch-icon', sizes: '72x72', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-72x72.png'},
      {rel: 'apple-touch-icon', sizes: '76x76', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-76x76.png'},
      {rel: 'apple-touch-icon', sizes: '114x114', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-114x114.png'},
      {rel: 'apple-touch-icon', sizes: '120x120', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-120x120.png'},
      {rel: 'apple-touch-icon', sizes: '144x144', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-144x144.png'},
      {rel: 'apple-touch-icon', sizes: '152x152', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-152x152.png'},
      {rel: 'apple-touch-icon', sizes: '180x180', href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/apple-icon-180x180.png'},
      {rel: 'icon', sizes: '192x192', type: "image/png", href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/android-icon-192x192.png'},
      {rel: 'icon', sizes: '32x32', type: "image/png", href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/favicon-32x32.png'},
      {rel: 'icon', sizes: '96x96', type: "image/png", href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/favicon-96x96.png'},
      {rel: 'icon', sizes: '16x16', type: "image/png", href: 'https://ddasf3j8zb8ok.cloudfront.net/images/favicon/favicon-16x16.png'},
    ],
    script: [
      {src: 'https://ddasf3j8zb8ok.cloudfront.net/js/jquery.js', type: 'text/javascript', defer: true, body: true, rel: 'dns-prefetch'},
      {src: 'https://ddasf3j8zb8ok.cloudfront.net/js/bootstrap.min.js', type: 'text/javascript', defer: true, body: true, rel: 'dns-prefetch'},
    ]
  },
  css: [
    '~/assets/css/main.css'
  ],


  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    {src: '~/plugins/vue-datepicker.js', mode: 'client'},
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // Simple usage
    '@nuxtjs/vuetify',
    '@nuxtjs/composition-api/module',
  ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    'cookie-universal-nuxt',
  ],

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    transpile: [/echarts/, /zrender/]
  },

  env:{
    SCHOOL_SERVICE_URL: process.env.SCHOOL_SERVICE_URL || 'https://school-api-service.craydel.online',
    WEBSITEURL: process.env.WEBSITE_URL || 'https://schools.craydel.com',
    CRAYDELWEBSITEURL: process.env.CRAYDELWEBSITEURL || 'https://craydel.com',
    WORKSPACEURL: process.env.WORKSPACEURL || 'https://workspace.craydel.com',
    COOKIEDOMAIN:process.env.COOKIEDOMAIN || "schools.craydel.com",
    COOKIESECURE:process.env.COOKIESECURE || true,
    LOGINURL:process.env.LOGINURL || "https://accounts.craydel.online",
    GA_ENV:process.env.GA_ENV || "production",
    SERVICE_CODE:process.env.SERVICE_CODE || "SRVLDSUWYD65N",
  }
}

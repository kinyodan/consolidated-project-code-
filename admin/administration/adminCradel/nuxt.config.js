import colors from 'vuetify/es5/util/colors'
const webpack = require('webpack')

export default {
  debug: true,
  ssr: true,
  loadingIndicator: {
    name: 'rotating-plane',
    color: 'blue',
    background: 'red'
  },
  server: {
    host: '0.0.0.0'
  },
  static: {
    prefix: false
  },
  router: {
    base: '/'
  },

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'adminCradel',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '~/assets/css/style.scss'
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
  ],

  env: {
    APIURL: process.env.APIURL || 'http://127.0.0.1:8000',
    LOGINREDIRECTURL: process.env.LOGINREDIRECTURL || "https://accounts.craydel.online?redirect=http://192.168.72.50:3000/login",
    WEBSITEURL: process.env.WEBSITEURL || 'http://192.168.72.50:3000/',
    COOKIEDOMAIN: process.env.COOKIEDOMAIN || '192.168.72.50',
    COOKIESECURE: process.env.COOKIESECURE || false,
    CAMPAIGNS_ENDPOINT: process.env.CAMPAIGNS_ENDPOINT || 'https://campaigns.craydel.com/wp-json',
    BLOG_ENDPOINT: process.env.BLOG_ENDPOINT || 'https://campaigns.craydel.com/wp-json',
    COUNTRY_DETECT_API_URL: process.env.COUNTRY_DETECT_API_URL || 'https://ipwhois.app',
    FORCE_MAIN_SITE_REDIRECT: process.env.FORCE_MAIN_SITE_REDIRECT || true,
  },

  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: true,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    'bootstrap-vue/nuxt',
    'cookie-universal-nuxt'
  ],

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
    extend(config) {
      config.module.rules.push({
        test: /\.ts$/,
        loader: 'ts-loader',
      });
    }
  }
}



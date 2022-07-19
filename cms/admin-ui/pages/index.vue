<script>
/**
 * Dashboard component
 */
import GlobalService from "~/helpers/GlobalService";


export default {
  head() {
    return {
      title: `${this.title} | Dashboard`,
    };
  },
  data() {
    return {
      title: "Craydel",
      items: [{
        text: "Craydel",
      },
        {
          text: "Craydel",
          active: true,
        },
      ],
    };
  },
  middleware: "authentication",
  methods:{
    rollbar_init(){
      // include and initialize the rollbar library with your access token
      var Rollbar = require('rollbar')
      var rollbar = new Rollbar({
        accessToken: process.env.ROLLBAR_TOKEN,
        captureUncaught: true,
        captureUnhandledRejections: true,
      })
    },
  }
};
</script>

<template>
  <div>
    <PageHeader :title="title" :items="items"/>
  </div>
</template>

<style></style>

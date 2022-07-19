<template>
  <div class="error-page">
    <div class="wrap">
      <div class="error-page__header">
        <div class="logo">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/craydel-logo.svg" alt="Craydel" />
        </div>
      </div>
      <div class="error-page__content">
        <h1>Oh no, something went wrong!</h1>
        <h4>Sorry, looks like we sent you the wrong way. Let us guide you back!</h4>
        <p>Head to the <a href="/courses">Courses Search Page</a> to find your ideal course, or go back to the <a href="/">Homepage</a></p>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'NotFoundError',
  layout: 'errorPage',
  props: {
    error: {
      type: Object,
      default: () => {},
    },
  },
};
</script>

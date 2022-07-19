<template>
  <div class="success_page_main">
      <div class="success_message_excerpt">
        <img src="~/assets/images/failed_icon.svg" alt="craydel payment failed">
        <h4>Payment Failed!</h4>
        <p>{{ message }}</p>
        <p>
          Transaction ID: <strong>{{ transID }} </strong> <br/>
          Transaction Token: <strong>{{ transToken }} </strong>
        </p>
        <p>Please contact support on <a href="mailto:support@craydel.com">support@craydel.com</a></p>
        <div class="action_buttons">
          <nuxt-link to="/" class="page_cta inverse">Back to home</nuxt-link>
          <a href="https://craydel.com" target="_blank" class="page_cta">Try again</a>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name:"payment-fail",
  data(){
    return{
      message:"",
      transToken:"",
      transID:""
    }
  },
  mounted(){
    let internal = this.$route.query.internal ? this.$route.query.internal : false
    if(internal){
      //check if its a redirection from an internal link so donot process DPO stuff
      this.message = this.$route.query.message ? this.$route.query.message : "N/A"
      this.transID = this.$route.query.TransID ? this.$route.query.TransID : "N/A"
      this.transToken = this.$route.query.TransactionToken ? this.$route.query.TransactionToken : "N/A"
    }
  }
}
</script>

<style>

</style>

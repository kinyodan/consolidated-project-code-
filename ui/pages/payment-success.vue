<template>
  <div class="success_page_main">
      <div class="success_message_excerpt">
        <img src="~/assets/images/payment_success.svg" alt="craydel payment succeeded">
        <h4>Your payment is successful!</h4>
        <p>
          {{ message }}
        </p>
        <div class="action_buttons">
          <a href="/" class="page_cta inverse">Back to home</a>
          <a href="/my-packages" class="page_cta">View your profile</a>
        </div>
<!--        <a href="" class="view_invoice_link">View your invoice</a>-->
      </div>
  </div>
</template>

<script>
import PaymentService from "@/helpers/PaymentService";
export default {
  name:"payment-success",
  data(){
    return{
      message:""
    }
  },
  mounted() {
    //verify payment
    this.verifyPayment();
  },
  methods:{
    verifyPayment(){
      //start loading
      this.$nextTick(() => {
        this.$nuxt.$loading.start()
      });

      //get the params
      let params = this.$route.query
      let self = this
      PaymentService.verifyPayment(params.TransactionToken).then(response=>{
        let data = response.data;
        if(data.status){
          //remove the loading screen
          this.$nextTick(() => {
            this.$nuxt.$loading.finish()
          });

          //set the success message
          self.message = data.message
        }else{
          //redirect to pyament fail page
          params.message = data.message;
          params.internal=true

          //remove the loading screen
          this.$nextTick(() => {
            this.$nuxt.$loading.finish()
          });

          //redirect
          this.$router.push({path:'/payment-fail', query:params})
        }
      })
    }
  }
}
</script>

<style>

</style>

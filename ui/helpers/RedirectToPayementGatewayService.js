import PaymentService from "@/helpers/PaymentService";

export default { 
  reDirectToPaymentGateway(user,product_code,product_quantity){
    let formData = new FormData();
    formData.append('product_code', product_code)
    formData.append('user_code', user.user_code)
    formData.append('country_code', user.country_code)
    formData.append('user_full_names', user.full_name)
    formData.append('user_email_address', user.email)
    formData.append('mobile_number', user.mobile_number)
    formData.append('product_quantity', product_quantity)

    let self = this
    //this.$nuxt.$loading.start()

    PaymentService.getPaymentPage(formData).then(response => {
      let data = response.data;
      if (data.status) {
        self.paymentError = false
        self.paymentErrorText = ""

        let redirectUrl = data.data.payment_page_url;
        if (redirectUrl) {
          if (process.client) {
            location.href = redirectUrl
          }
        } else {
          self.submitError = true;
          self.submitErrorText = "We could not complete the payment please try again later!"
          //this.$nuxt.$loading.finish()
        }
      } else {
        self.submitError = true;
        self.submitErrorText = `${data.message}. Please refresh the page and try again.`
        //this.$nuxt.$loading.finish()
      }
    });
  },
}    
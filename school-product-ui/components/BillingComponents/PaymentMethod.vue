<template>
  <div class="payment-method">
    <v-tabs v-model="tab" background-color="transparent" hide-slider color="payment-tab" class="mb-2">
      <v-tab v-for="item in items" :key="item.id">
        <img :src="item.payment_logo" :alt="item.payment_name"/>
      </v-tab>
    </v-tabs>
    <v-tabs-items v-model="tab">
      <v-tab-item v-for="item in items" :key="item.id">
        <v-card color="payment-tab" flat>

          <v-card-text>
            <div v-if="!show_instructions">
              <div class="form_group">
                <v-card-subtitle class="pa-0 pb-4 font-weight-bold">Pay with {{ item.payment_name }}</v-card-subtitle>
                <label class="form_label" for="payment_phone">Phone</label>
                <vue-phone-number-input
                  v-model="payment_phone"
                  :border-radius="10"
                  id="payment_phone"
                  class="custom-phone"
                  default-country-code="KE"
                ></vue-phone-number-input>
              </div>

              <v-btn
                class="ma-2 mx-0"
                :loading="mobile_payment_loading"
                :disabled="mobile_payment_loading"
                color="success"
                depressed
                @click="mobilePaymentLoader = 'mobile_payment_loading'"
              >
                Confirm
                <template v-slot:loader>
                  <span>Wait... {{ timerCount }} seconds</span>
                </template>
              </v-btn>
            </div>

            <div v-if="show_instructions">
              <v-alert border="left" text type="error">
                No prompt was sent on your phone. Kindly follow the instructions below to make payment.
              </v-alert>

              <p>To make payment via M-PESA,</p>

              <ol>
                <li>Go to your M-PESA menu</li>
                <li>Select Lipa na M-PESA</li>
                <li>Select Pay Bill</li>
                <li>Enter the Business Number as <strong>4091853</strong></li>
                <li>Enter the account number <strong>{ account_number }</strong></li>
                <li>Enter the amount <strong>{ amount }</strong></li>
                <li>Follow the prompts to complete your transaction.</li>
                <li>Kindly click the "Place Order" Button below once you have completed payment.</li>
              </ol>
              <br/>
              Please note that you need to Make Payment Before you Submit. If this is not the case, your order will
              automatically cancel within 30 minutes.

              <div class="action_btns mt-4">
                <button type="submit" class="edit_btn" v-scroll>Place Order</button>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
  name: "PaymentMethod",
  components: {VuePhoneNumberInput},
  data() {
    return {
      tab: null,
      items: [
        {
          id: 1,
          payment_name: 'm-pesa',
          payment_logo: 'https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/payment-methods/m-pesa_logo.png',
        },
        {
          id: 2,
          payment_name: 'airtel money',
          payment_logo: 'https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/payment-methods/airtel_logo.png',
        },
      ],
      payment_phone: "",
      mobilePaymentLoader: null,
      mobile_payment_loading: false,
      show_instructions: false,
      timerCount: 30
    }
  },
  watch: {
    mobilePaymentLoader() {
      const l = this.mobilePaymentLoader
      this[l] = !this[l]

      setTimeout(() => (this[l] = false, this.show_instructions = true), 3000)
    },

  },
}
</script>

<style scoped>

</style>

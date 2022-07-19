<template>
  <div class="modal fade in" id="buy_licenses" tabindex="-1">
    <div class="modal-dialog">
      <div id="scroll-content" class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Buy Licenses</h2>
            <a href="javascript:void(0)" class="cancel_btn" data-dismiss="modal">Close</a>

            <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="showAlert">
              <span v-html="alertMsg"></span>
              <button type="button" class="close_alert" @click="showAlert = false" aria-label="Close">&times;
              </button>
            </div>
          </div>
          <form action="" @submit.prevent="showAlert">

            <div class="step-header" v-if="step == 1">
              <v-card-title class="pa-0 step-title">Number of Licenses</v-card-title>
            </div>

            <div class="step-header" v-if="step == 2">
              <v-btn icon class="back-btn" @click="step = 1" title="Back to Cart">
                <v-icon>mdi-chevron-left</v-icon>
              </v-btn>
              <v-card-title class="pa-0 step-title">Billing Details</v-card-title>
            </div>

            <div class="step-header" v-if="step == 3">
              <v-btn v-if="!payment_success" icon class="back-btn" @click="step = 2" title="Back to Billing">
                <v-icon>mdi-chevron-left</v-icon>
              </v-btn>
              <v-card-title class="pa-0 step-title">Payment Details</v-card-title>
            </div>

            <v-stepper v-model="step" alt-labels flat>
              <v-stepper-header flat>
                <v-stepper-step :complete="step > 1" step="1">
                  Cart
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step :complete="step > 2" step="2">
                  Billing
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step step="3">
                  Payment
                </v-stepper-step>
              </v-stepper-header>

              <v-stepper-items>
                <v-stepper-content step="1">
                  <div class="my-6 px-4">

                    <div class="form_group">
                      <label class="form_label" for="license_quantity">Licenses</label>
                      <input type="number" value="1">
                    </div>

                    <v-divider class="mb-3"></v-divider>

                    <div class="cart-total">
                      <div class="cart-total__label">Subtotal</div>
                      <div class="cart-total__value">Ksh 2,000</div>
                      <div class="cart-total__label">VAT</div>
                      <div class="cart-total__value">Ksh 150</div>
                      <div class="cart-total__label cart-total--bold">Total</div>
                      <div class="cart-total__value cart-total--bold">Ksh 2,150</div>
                    </div>
                  </div>

                  <div class="action_btns px-4 mb-8">
                    <button type="button" class="edit_btn" @click="goToStep(2)">Continue</button>
                  </div>
                </v-stepper-content>

                <v-stepper-content step="2">
                  <div class="my-6 px-4">
                    <div class="form_group">
                      <label class="form_label" for="billing_name">Full Name</label>
                      <input type="text" v-model="billing_name" id="billing_name">
                    </div>

                    <div class="form_group">
                      <label class="form_label" for="billing_phone">Phone</label>
                      <vue-phone-number-input
                        v-model="billing_phone"
                        :border-radius="10"
                        id="billing_phone"
                        class="custom-phone"
                        default-country-code="KE"
                      ></vue-phone-number-input>
                    </div>

                    <div class="form_group">
                      <label class="form_label" for="billing_email">Email</label>
                      <input type="email" v-model="billing_email" id="billing_email">
                    </div>

                    <div class="form_group">
                      <label class="form_label" for="billing_address">Address</label>
                      <textarea v-model="billing_address" id="billing_address"></textarea>
                    </div>

                  </div>

                  <div class="action_btns px-4 mb-8">
                    <button type="button" class="edit_btn" @click="goToStep(3)">Continue</button>
                  </div>
                </v-stepper-content>

                <v-stepper-content step="3">
                  <div class="my-6 px-4" v-if="!payment_success">
                    <div class="form_group">
                      <label class="form_label">Choose Payment Method</label>

                      <v-expansion-panels focusable class="accordion-form" v-model="payment_accordion">
                        <v-expansion-panel>
                          <v-expansion-panel-header hide-actions disable-icon-rotate @click="setPaymentMethod($event)"
                                                    data-paymentmethod="mobile">
                            <v-radio-group v-model="selected_payment_method">
                              <v-radio
                                label="Mobile Money"
                                name="payment_method"
                                value="mobile"
                              ></v-radio>
                            </v-radio-group>
                          </v-expansion-panel-header>
                          <v-expansion-panel-content>
                            <payment-method></payment-method>
                          </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-expansion-panel>
                          <v-expansion-panel-header hide-actions disable-icon-rotate @click="setPaymentMethod($event)"
                                                    data-paymentmethod="card">
                            <v-radio-group v-model="selected_payment_method">
                              <v-radio
                                label="Card"
                                name="payment_method"
                                value="card"
                              ></v-radio>
                            </v-radio-group>
                          </v-expansion-panel-header>
                          <v-expansion-panel-content>
                            <div class="d-flex align-center flex-column">
                              <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/offsite.svg" width="163"
                                   height="81" alt=""/>
                              <p class="text_center mt-3">After clicking “Place Order”, you will be redirected to a
                                payment gateway to complete your purchase securely.</p>
                              <div class="action_btns">
                                <button type="submit" class="edit_btn">Place Order</button>
                              </div>
                            </div>
                          </v-expansion-panel-content>
                        </v-expansion-panel>
                      </v-expansion-panels>
                    </div>
                  </div>
                  <div class="my-6 px-4" v-else>
                    <v-alert border="left" text type="success">
                      Payment was successful
                    </v-alert>
                  </div>
                </v-stepper-content>
              </v-stepper-items>
            </v-stepper>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import PaymentMethod from "@/components/BillingComponents/PaymentMethod";

export default {
  name: "BuyLicenses",
  components: {PaymentMethod, VuePhoneNumberInput},
  data() {
    return {
      step: 1,
      billing_name: "",
      billing_phone: "",
      billing_email: "",
      billing_address: "",
      payment_accordion: 0,
      selected_payment_method: "mobile",
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
      payment_success: false
    }
  },
  methods: {
    goToStep(step) {
      this.step = step
      let container = document.getElementById('scroll-content')
      container.scrollTop = 0
    },
    setPaymentMethod(e) {
      let selected = e.currentTarget.getAttribute('data-paymentmethod')
      if (selected) {
        this.selected_payment_method = selected
      }
    },
    closeModal() {
      $('#buy_licenses').modal('hide')
    },
  },
}
</script>

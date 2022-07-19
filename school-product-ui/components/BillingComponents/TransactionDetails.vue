<template>
  <div class="modal fade in" id="show_transaction_details" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">

          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader"
            :loading="loading"
            type="card-heading, divider, card-heading, paragraph, card-heading@3, divider, actions"
          ></v-skeleton-loader>

          <div v-show="!firstLoad">
            <div class="modal_header wrap-mobile">
              <h2 class="modal_title">{{ transaction.id }} - Details</h2>
              <v-btn class="download_excel"
                     small
                     href="#"
              >
                Download Invoice
              </v-btn>
            </div>

            <v-expansion-panels focusable class="accordion-form" v-model="panel">
              <v-expansion-panel>
                <v-expansion-panel-header>Transaction Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div>
                      <h3 class="details-modal-content__title">Transaction Date</h3>
                      <span>{{ new Date(transaction.date).toLocaleDateString() }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Number of Licenses</h3>
                      <span>{{ transaction.quantity }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Transacted By</h3>
                      <span>{{ transaction.transacted_by }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Payment Status</h3>
                      <v-chip :color="getColor(transaction.payment_status)" dark>
                        {{ transaction.payment_status }}
                      </v-chip>
                    </div>
                    <div class="span--two">
                      <h3 class="details-modal-content__title">Amount Breakdown</h3>
                      <div class="cart-total">
                        <div class="cart-total__label">Subtotal</div>
                        <div class="cart-total__value">Ksh 2,000</div>
                        <div class="cart-total__label">VAT</div>
                        <div class="cart-total__value">Ksh 150</div>
                        <div class="cart-total__label cart-total--bold">Total</div>
                        <div class="cart-total__value cart-total--bold">Ksh 2,150</div>
                      </div>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Billing Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div>
                      <h3 class="details-modal-content__title">Full Name</h3>
                      <span>{{ transaction.billing_name }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Phone</h3>
                      <span>{{ transaction.billing_phone }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Email</h3>
                      <span>{{ transaction.billing_email }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Address</h3>
                      <span>{{ transaction.billing_address }}</span>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Payment Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div>
                      <h3 class="details-modal-content__title">Payment Method</h3>
                      <span>{{ transaction.payment_method }}</span>
                    </div>
                    <div v-if="transaction.payment_phone">
                      <h3 class="details-modal-content__title">Payment Phone</h3>
                      <span>{{ transaction.payment_phone }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Confirmation Code</h3>
                      <span>{{ transaction.confirmation_code }}</span>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>

            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="closeModal">Close</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name: "TransactionDetails",
  props: ['transaction', 'show_transaction_details_loader'],
  data() {
    return {
      panel: 0,
      loading: true,
      firstLoad: true,
    }
  },
  watch: {
    show_transaction_details_loader: {
      immediate: true,
      deep: true,
      handler(newValue, oldValue) {
        this.firstLoad = newValue
      }
    },
    transaction: {
      immediate: true,
      deep: true,
      handler(newValue, oldValue) {
        if(newValue){
          this.transaction = newValue
          this.panel = 0
        }
      }
    },
  },
  methods: {
    getColor(value) {
      if (value === 'Paid') {
        return 'green'
      } else if (value === 'Failed') {
        return 'failed'
      } else {
        return 'red'
      }
    },
    closeModal() {
      $('#show_transaction_details').modal('hide');
      this.$emit('modal_dialog_closed')
    },
  },
}
</script>

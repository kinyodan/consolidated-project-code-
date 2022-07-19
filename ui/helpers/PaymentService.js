import {headerlessPostClient} from "@/helpers/axios-config";
export default {
  verifyPayment(token) {
    return headerlessPostClient({app}).get(`/billing/verify-transaction/${token}`)
  },
  
  getPaymentPage(data){
    return headerlessPostClient({app}).post(`/billing/make-payment`,data)
  },
  validateDiscountCoupon(data){
    return headerlessPostClient({app}).post(`/billing/verify-coupon-code`,data)
  }
}

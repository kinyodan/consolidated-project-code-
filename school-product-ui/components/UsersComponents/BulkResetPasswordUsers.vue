<template>
  <div class="modal fade in" id="bulk_reset_password_users" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Reset Password for Users in Bulk</h2>
            <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Close</a>

            <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="showAlert">
              <span v-html="alertMsg"></span>
              <button type="button" class="close_alert" @click="showAlert = false" aria-label="Close">&times;
              </button>
            </div>
          </div>

          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader"
            :loading="loading"
            type="list-item-avatar@5, divider, actions"
          ></v-skeleton-loader>

          <div v-show="!firstLoad">
            <bulk-users></bulk-users>
            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="resetPasswordItem">Reset Password</a>
              <a href="" class="cancel_btn" data-dismiss="modal">Cancel</a>
            </div>
          </div>

          <v-dialog v-model="dialogResetPassword" :retain-focus="false" content-class="dialog">
            <v-card>
              <v-card-title class="text-h6 text-center">Are you sure you want to reset the password for { count } users?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue" text @click="closeResetPassword">Cancel</v-btn>
                <v-btn color="error" text @click="resetPasswordItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import BulkUsers from "@/components/UsersComponents/BulkUsers";

export default {
  name: "BulkResetPasswordUsers",
  components: {BulkUsers},
  data() {
    return {
      loading: true,
      firstLoad: false,
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
      dialogResetPassword: false,
    }
  },
  methods: {
    resetPasswordItem() {
      this.dialogResetPassword = true
    },

    resetPasswordItemConfirm() {
      this.closeResetPassword()
      $('#bulk_reset_password_users').modal('hide');
    },

    closeResetPassword() {
      this.dialogResetPassword = false
    },

    closeModal() {
      $('#bulk_reset_password_users').modal('hide');
    },
  },
}
</script>

<style scoped>

</style>

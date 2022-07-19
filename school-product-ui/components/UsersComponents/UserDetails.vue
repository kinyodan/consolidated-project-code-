<template>
  <div class="modal fade in" id="show_user_details" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader"
            :loading="loading"
            type="card-heading, divider, image, card-heading, text, card-heading, text, card-heading, text, divider, actions"
          ></v-skeleton-loader>

          <div v-show="!firstLoad">
            <div class="modal_header">
              <h2 class="modal_title">{ full_name } - Details</h2>
              <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Close</a>

              <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="showAlert">
                <span v-html="alertMsg"></span>
                <button type="button" class="close_alert" @click="showAlert = false" aria-label="Close">&times;
                </button>
              </div>
            </div>

            <div class="details-modal-content no_margin_top">
              <div class="span--two">
                <div class="profile-photo">
                  <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg"
                       width="200" height="200" alt="User Photo"/>
                </div>
              </div>
              <div>
                <h3 class="details-modal-content__title">Title</h3>
                <span>{ title }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Full Name</h3>
                <span>{ full_name }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Preferred Name</h3>
                <span>{ preferred_name }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Phone</h3>
                <span>{ phone }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Email</h3>
                <span>{ email }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Gender</h3>
                <span>{ gender }</span>
              </div>
              <div class="span--two">
                <h3 class="details-modal-content__title">Nationality</h3>
                <span>{ nationality }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Branch</h3>
                <span>{ branch }</span>
              </div>
              <div>
                <h3 class="details-modal-content__title">Role</h3>
                <span>{ role }</span>
              </div>
              <div class="span--two">
                <ul class="meta">
                  <li>Created by: { created_by }</li>
                  <li>Updated by: { updated_by }</li>
                </ul>
              </div>
            </div>

            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="editItem">Edit</a>
              <a class="delete_btn" @click="deleteItem">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <v-dialog v-model="dialogDelete" :retain-focus="false" content-class="dialog">
      <v-card>
        <v-card-title class="text-h6 text-center">Are you sure you want to delete this user?</v-card-title>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
          <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name: "UserDetails",
  props: ['show_user_details_loader'],
  data() {
    return {
      loading: true,
      firstLoad: true,
      dialogDelete: false,
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
    }
  },
  watch: {
    dialogDelete(val) {
      val || this.closeDelete()
    },
    show_user_details_loader: {
      immediate: true,
      deep: true,
      handler(newValue, oldValue) {
        this.firstLoad = newValue
      }
    }
  },
  methods: {
    editItem() {
      $('#show_user_details').modal('hide');
      $('#create_new_user').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    deleteItem() {
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.closeDelete()
      $('#show_user_details').modal('hide');
    },

    closeDelete() {
      this.dialogDelete = false
    },

    closeModal() {
      $('#show_user_details').modal('hide');
      this.$emit('modal_dialog_closed')
    },
  },
}
</script>

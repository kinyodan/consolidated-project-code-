<template>
  <div class="modal fade in" id="show_stream_details" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader"
            :loading="loading"
            type="card-heading, card-heading, text, sentences, actions"
            max-width="300"
          ></v-skeleton-loader>

          <div v-show="!firstLoad">
            <div class="modal_header">
              <h2 class="modal_title">Stream Details</h2>
              <a href="javascript:void(0)" class="cancel_btn" data-dismiss="modal">Close</a>

              <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="showAlert">
                <span v-html="alertMsg"></span>
                <button type="button" class="close_alert" @click="showAlert = false" aria-label="Close">&times;
                </button>
              </div>
            </div>

            <div class="details-modal-content">
              <div class="span--two">
                <h3 class="details-modal-content__title">Stream Name</h3>
                <span>{ name }</span>
              </div>
              <div class="span--two">
                <ul class="meta">
                  <li>Created by: { created_by }</li>
                  <li>Updated by: { updated_by }</li>
                </ul>
              </div>
            </div>

            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="editItem(item)">Edit</a>
              <a class="delete_btn" @click="deleteItem(item)">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <v-dialog v-model="dialogDelete" content-class="dialog">
      <v-card>
        <v-card-title class="text-h6 text-center">Are you sure you want to delete this item?</v-card-title>
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

export default {
  name: "StreamDetails",
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
  },
  created() {
    this.getDataFromApi()
  },
  methods: {
    getDataFromApi() {
      this.loading = true;

      setTimeout(() => {
        if (this.firstLoad) this.firstLoad = false
        this.loading = false;
      }, 5000);
    },

    editItem(item) {
      $('#show_stream_details').modal('hide');
      $('#add_new_stream').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    deleteItem(item) {
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.closeDelete()
      $('#show_stream_details').modal('hide');
    },

    closeDelete() {
      this.dialogDelete = false
    },
  },
}
</script>

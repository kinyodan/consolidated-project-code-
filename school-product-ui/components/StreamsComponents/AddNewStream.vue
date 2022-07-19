<template>
  <div class="modal fade in" id="add_new_stream" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Add Stream</h2>
          </div>
          <form action="">
            <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="addSuccess">
              <span v-html="alertMsg"></span>
              <button type="button" class="close_alert" @click="addSuccess = false" aria-label="Close">&times;</button>
            </div>
            <div class="full_width">
              <div class="form_group mb-0">
                <label class="form_label">Stream Name</label>
                <input type="text" required v-model="stream_name" id="stream_name">
              </div>
            </div>
            <div class="action_btns modal_footer">
              <input type="submit" value="Create Stream">
              <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "AddNewStream",
  data() {
    return {
      stream_name: "",
      addSuccess: false,
      alertClass: 'alert-success',
      alertMsg: '',
    }
  },
  methods: {
    closeModal() {
      $('#add_new_stream').modal('hide');
    },
  }
}
</script>

<style scoped>

</style>

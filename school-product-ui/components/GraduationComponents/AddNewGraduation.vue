<template>
  <div class="modal fade in" id="add_new_graduation" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Add Graduation</h2>
          </div>
          <form action="">
            <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="addSuccess">
              <span v-html="alertMsg"></span>
              <button type="button" class="close_alert" @click="addSuccess = false" aria-label="Close">&times;</button>
            </div>
            <div class="full_width">
              <div class="left_form">
                <div class="form_group">
                  <label class="form_label">Graduation Month</label>
                  <div class="styled-select">
                    <select name="graduation_month" required v-model="graduation_month">
                      <option value="" selected="" disabled>Select Month</option>
                      <option value="January">January</option>
                      <option value="February">February</option>
                      <option value="March">March</option>
                      <option value="April">April</option>
                      <option value="May">May</option>
                      <option value="June">June</option>
                      <option value="July">July</option>
                      <option value="August">August</option>
                      <option value="September">September</option>
                      <option value="October">October</option>
                      <option value="November">November</option>
                      <option value="December">December</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="right_form">
                <div class="form_group mb-0">
                  <label class="form_label">Graduation Year</label>
                  <div class="styled-select">
                    <select name="graduation_year" required v-model="graduation_year">
                      <option value="" selected="" disabled>Select Year</option>
                      <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="action_btns modal_footer">
              <input type="submit" value="Create Graduation">
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
  name: "AddNewGraduation",
  data() {
    return {
      graduation_month: "",
      graduation_year: "",
      addSuccess: false,
      alertClass: 'alert-success',
      alertMsg: '',
    }
  },
  computed:{
    years() {
      let current_year = new Date().getFullYear();
      let years = [];
      for (let i = current_year + 5; i > current_year - 3; i--) {
        years.push(i);
      }
      return years;
    },
  },
  methods: {
    closeModal() {
      $('#add_new_graduation').modal('hide');
    },
  }
}
</script>

<style scoped>

</style>

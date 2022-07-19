<template>
  <div class="modal fade in" id="bulk_excel_upload" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Add Students in Bulk</h2>
            <v-app>
              <v-menu offset-y attach="#student_bulk_upload">
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    class="download_excel"
                    small
                    v-bind="attrs"
                    v-on="on"
                    id="student_bulk_upload"
                  >
                    Download Template
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                    v-for="(item, index) in templates"
                    :key="index"
                    class="template-link"
                  >
                    <v-list-item-title>
                      <a :href="item.value">{{ item.title }}</a>
                    </v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-app>
          </div>

          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader file-placeholder"
            :loading="loading"
            type="image, divider, actions"
          ></v-skeleton-loader>

          <form enctype="multipart/form-data" novalidate v-on:submit.prevent="uploadFile()">
            <div class="alert alert-dismissible fade show" :class="alert_class" v-if="show_message" role="alert">
              <p v-html="message"></p>
              <button type="button" @click="show_message = false" class="close_alert" aria-label="Close">&times;
              </button>
            </div>
            <div class="full_width">
              <div class="form_group">
                <label class="form_label">Upload the students list</label>
                <div class="upload_file">
                  <input v-on:change="onFileSelected" type="file" id="invite_list" name="invite_list" ref="invite_list"
                         accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>
              </div>
            </div>
            <div class="action_btns modal_footer">
              <input type="submit" value="Upload Student List">
              <a href="" class="cancel_btn" data-dismiss="modal">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name: "BulkExcelUpload",
  data() {
    return {
      loading: true,
      firstLoad: true,
      file: '',
      uploaded: 0,
      imported: 0,
      message: '',
      show_message: false,
      alert_class: '',
      templates: [
        {title: 'KCSE', value: '/Student%20Invite%20List%20-%20Template.xlsx'},
        {title: 'IGCSE', value: '/Student%20Invite%20List%20-%20Template.xlsx'}
      ],
    }
  },
  methods: {
    onFileSelected() {
      this.file = this.$refs.invite_list.files[0];
    },
    async uploadFile(event) {
      this.$nuxt.$loading.start()
      const uploadFrom = new FormData()
      uploadFrom.append('school_id', '1')
      uploadFrom.append('invite_list', this.file)

      try {
        let response = await SchoolService.bulkImportStudents(this, uploadFrom).then(response => response.data)

        if (response.status && parseInt(response.imported_records) === parseInt(response.uploaded_records)) {
          this.uploaded = response.uploaded_records
          this.imported = response.imported_records
          this.message = response.message
          this.show_message = true
          this.alert_class = 'alert-success'

          if (process.client) {
            setTimeout(function () {
              this.show_message = false
              window.location.reload();
            }, 2500);
          }
        } else {
          this.show_message = true
          this.uploaded = response.uploaded_records
          this.imported = response.imported_records
          this.message = response.message
          this.alert_class = 'alert-danger'
        }

        this.$nuxt.$loading.finish()
        event.target.reset();
      } catch (err) {
        console.log(err)
        this.$nuxt.$loading.finish()
      }
    }
  },
}
</script>

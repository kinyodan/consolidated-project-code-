<template>
  <div class="modal fade in" id="bulk_rollover_students" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Rollover Students</h2>
            <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Close</a>
          </div>

          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader"
            :loading="loading"
            type="list-item-avatar@5, divider, actions"
          ></v-skeleton-loader>

          <div v-show="!firstLoad">
            <!-- selected students -->
            <v-list class="pt-0">
              <v-list-item
                v-for="student in students"
                :key="student.id"
                class="px-0"
              >
                <v-list-item-avatar>
                  <v-img
                    :alt="`${student.name} photo`"
                    :src="student.photo"
                  ></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ student.name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ student.class + student.stream }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <!-- selected students end -->

            <div class="full_width">
              <div class="left_form">
                <div class="form_group autocomplete">
                  <label class="form_label" for="rollover_class">Rollover Class</label>
                  <v-autocomplete
                    v-model="class_id"
                    :items="classes"
                    item-text="class_name"
                    item-value="id"
                    hide-details="auto"
                    label="Select Class"
                    solo
                    required
                    id="rollover_class"
                  >
                    <template v-slot:append-item>
                      <v-divider class="mb-2"></v-divider>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-action-text>
                            <a href="javascript:void(0)" @click="addNewClass">+ Add
                              Class</a>
                          </v-list-item-action-text>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-autocomplete>
                </div>
              </div>
              <div class="right_form">
                <div class="form_group autocomplete mb-0">
                  <label class="form_label" for="rollover_stream">Rollover Stream</label>
                  <v-autocomplete
                    v-model="stream_id"
                    :items="streams"
                    item-text="stream_name"
                    item-value="id"
                    hide-details="auto"
                    label="Select Stream"
                    solo
                    required
                    id="rollover_stream"
                  >
                    <template v-slot:append-item>
                      <v-divider class="mb-2"></v-divider>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-action-text>
                            <a href="javascript:void(0)" class="autocomplete-add-item" @click="addNewStream">+ Add
                              Stream</a>
                          </v-list-item-action-text>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-autocomplete>
                </div>
              </div>
            </div>

            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="rollover">Rollover Students</a>
              <a href="" class="cancel_btn" data-dismiss="modal">Cancel</a>
            </div>
          </div>

          <v-dialog v-model="dialogRollover" content-class="dialog">
            <v-card>
              <v-card-title class="text-h6 text-center">Are you sure you want to rollover { count }
                students?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue" text @click="closeRollover">Cancel</v-btn>
                <v-btn color="error" text @click="rolloverConfirm">OK</v-btn>
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

export default {
  name: "BulkRolloverStudents",
  data() {
    return {
      loading: true,
      firstLoad: false,
      students: [
        {
          id: 1,
          photo: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
          name: 'Jason Oner',
          class: 'Form 1',
          stream: 'A'
        },
        {
          id: 2,
          photo: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
          name: 'Mike Carlson',
          class: 'Form 1',
          stream: 'B'
        },
        {
          id: 3,
          photo: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
          name: 'Cindy Baker',
          class: 'Form 2',
          stream: 'C'
        },
        {
          id: 4,
          photo: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
          name: 'Ali Connors',
          class: 'Form 2',
          stream: 'P'
        },
        {
          id: 5,
          photo: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
          name: 'Travis Howard',
          class: 'Form 3',
          stream: 'Y'
        },
      ],
      class_id: "",
      classes: [
        {
          id: 1,
          class_name: 'Class 1'
        },
        {
          id: 2,
          class_name: 'Class 2'
        },
        {
          id: 3,
          class_name: 'Class 3'
        },
      ],
      stream_id: "",
      streams: [
        {
          id: 1,
          stream_name: 'A'
        },
        {
          id: 2,
          stream_name: 'P'
        },
        {
          id: 3,
          stream_name: 'Y'
        },
      ],
      dialogRollover: false,
    }
  },
  methods: {
    addNewClass() {
      $('#add_new_class').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    addNewStream() {
      $('#add_new_stream').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    rollover() {
      this.dialogRollover = true
    },

    rolloverConfirm() {
      this.closeRollover()
      $('#bulk_rollover_students').modal('hide');
    },

    closeRollover() {
      this.dialogRollover = false
    },

    closeModal() {
      $('#bulk_rollover_students').modal('hide');
    },
  },
}
</script>

<style scoped>

</style>

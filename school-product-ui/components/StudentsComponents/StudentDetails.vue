<template>
  <div class="modal fade in" id="show_student_details" tabindex="-1">
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
            <div class="modal_header">
              <h2 class="modal_title">{{ student.student_name }} - Details</h2>
              <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Close</a>
            </div>

            <v-expansion-panels focusable class="accordion-form" v-model="panel">
              <v-expansion-panel>
                <v-expansion-panel-header>Student Milestones</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div>
                      <h3 class="details-modal-content__title">Invite Sent</h3>
                      <span>{{ student.is_invite_sent ? "Yes" : "No" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Student Activated</h3>
                      <span>{{ student.is_account_activated ? "Yes" : "No" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Student Subscribed</h3>
                      <span>{{ student.has_subscribed_for_assessment ? "Yes" : "No" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Psychometric Assessment Done</h3>
                      <span>{{ student.has_done_career_counselling ? "Yes" : "No" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Course Application Done</h3>
                      <span>{{ student.has_applied_for_course ? "Yes" : "No" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Course Applied</h3>
                      <span>{{ student.student_opportunity_course ? student.student_opportunity_course : "" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Institution</h3>
                      <span>{{
                          student.student_opportunity_institution ? student.student_opportunity_institution : ""
                        }},{{
                          student.student_opportunity_institution_location ? student.student_opportunity_institution_location : ""
                        }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Intake</h3>
                      <span>{{ student.student_opportunity_intake ? student.student_opportunity_intake : "" }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Application Status</h3>
                      <span>{{ student.student_opportunity_status ? student.student_opportunity_status : "" }}</span>
                    </div>
                    <div class="span--two">
                      <ul class="meta">
                        <li>Created by: { created_by }</li>
                        <li>Updated by: { updated_by }</li>
                      </ul>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Student Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div class="span--two">
                      <div class="profile-photo">
                        <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg"
                             width="200" height="200" alt="Student Photo"/>
                      </div>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Student Email</h3>
                      <span>{{ student.student_email }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Date of Birth</h3>
                      <span>{ date_of_birth }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Gender</h3>
                      <span>{ gender }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Nationality</h3>
                      <span>{ nationality }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Student Phone</h3>
                      <span>{ nationality }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Address</h3>
                      <span>{ address }</span>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Academic Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div>
                      <h3 class="details-modal-content__title">School Branch</h3>
                      <span>{ school_branch }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Curriculum</h3>
                      <span>{ curriculum }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Class</h3>
                      <span>{{ student.class_name }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Stream</h3>
                      <span>{{ student.stream_name }}</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Enrollment Date</h3>
                      <span>{ enrollment_date }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Graduation</h3>
                      <span>{ graduation }</span>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Guardian Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="details-modal-content no_margin_top">
                    <div class="span--two">
                      <div class="profile-photo">
                        <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg"
                             width="200" height="200" alt="Guardian Photo"/>
                      </div>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Guardian Full Name</h3>
                      <span>{ guardian_name }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Guardian Phone</h3>
                      <span>{ guardian_phone }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Guardian Email</h3>
                      <span>{ guardian_email }</span>
                    </div>
                    <div>
                      <h3 class="details-modal-content__title">Guardian Relationship</h3>
                      <span>{ guardian_relationship }</span>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>

            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="editItem">Edit</a>
              <a class="delete_btn" @click="deleteItem">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <v-dialog v-model="dialogDelete" content-class="dialog">
      <v-card>
        <v-card-title class="text-h6 text-center">Are you sure you want to delete this student?</v-card-title>
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
  name: "StudentDetails",
  props: ['student', 'show_student_details_loader'],
  data() {
    return {
      panel: 0,
      loading: true,
      firstLoad: true,
      dialogDelete: false,
    }
  },
  watch: {
    dialogDelete(val) {
      val || this.closeDelete()
    },
    show_student_details_loader: {
      immediate: true,
      deep: true,
      handler(newValue, oldValue) {
        this.firstLoad = newValue
      }
    }
  },
  methods: {
    editItem() {
      $('#show_student_details').modal('hide');
      $('#add_new_user').modal({
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
      $('#show_student_details').modal('hide');
    },

    closeDelete() {
      this.dialogDelete = false
    },

    closeModal() {
      $('#show_student_details').modal('hide');
      this.$emit('modal_dialog_closed')
    },
  },
}
</script>

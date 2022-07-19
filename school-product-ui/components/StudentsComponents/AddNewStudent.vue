<template>
  <div class="modal fade in" id="add_new_user" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Add Student</h2>
          </div>
          <form action="" @submit.prevent="inviteStudent">
            <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="inviteSuccess">
              <span v-html="alertMsg"></span>
              <button type="button" class="close_alert" @click="inviteSuccess = false" aria-label="Close">&times;
              </button>
            </div>

            <v-expansion-panels focusable class="accordion-form" v-model="panel">
              <v-expansion-panel>
                <v-expansion-panel-header>Student Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="full_width">
                    <div class="form_group text_center margin-bottom_md">
                      <label class="form_label" for="student_photo">Student Photo</label>
                      <cropper
                        class="cropper"
                        v-if="student_photo"
                        :src="student_photo"
                        ref="cropper"
                        :auto-zoom="true"
                        :stencil-size="{
                          width: 280,
                          height: 280
                        }"
                        :canvas="{
                          height: 200,
                          width: 200,
                        }"
                        image-restriction="stencil"
                      />

                      <div class="profile-photo" v-if="!student_photo">
                        <img :src="student_cropped_photo" width="200" height="200" alt="Student Photo"/>
                      </div>

                      <div class="file-upload">
                        <label class="file-upload__btn">
                          <input type="file" required @change="loadStudentImage" id="student_photo" accept="image/*">
                          <svg width="20" height="17.3">
                            <use xlink:href="#camera_icon"></use>
                          </svg>
                          Upload image
                        </label>
                        <a href="javascript:void(0)" v-if="student_photo" class="file-upload__crop"
                           @click="cropStudent">Use</a>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group">
                        <label class="form_label" for="student_name">Student Full Name</label>
                        <input type="text" required v-model="student_name" id="student_name">
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group">
                        <label class="form_label" for="student_email">Student Email</label>
                        <input type="email" required v-model="student_email" id="student_email">
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group">
                        <label class="form_label" for="student_dob">Date of Birth <span
                          class="optional">(optional)</span></label>
                        <input type="date" v-model="student_dob" id="student_dob">
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="student_gender">Gender <span
                          class="optional">(optional)</span></label>
                        <v-autocomplete
                          v-model="gender_id"
                          :items="genders"
                          hide-details="auto"
                          label="Select Gender"
                          solo
                          id="student_gender"
                        ></v-autocomplete>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="student_nationality">Nationality <span
                          class="optional">(optional)</span></label>
                        <v-autocomplete
                          v-model="nationality_id"
                          :items="nationalities"
                          hide-details="auto"
                          label="Select Nationality"
                          solo
                          id="student_nationality"
                        ></v-autocomplete>
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group">
                        <label class="form_label" for="student_phone">Student Phone</label>
                        <vue-phone-number-input
                          v-model="student_phone"
                          :border-radius="10"
                          id="student_phone"
                          class="custom-phone"
                          default-country-code="KE"
                          required
                        ></vue-phone-number-input>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="form_group">
                      <label class="form_label" for="student_address">Address</label>
                      <textarea required v-model="student_address" id="student_address"></textarea>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Academic Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="school_branch">School Branch</label>
                        <v-autocomplete
                          v-model="branch_id"
                          :items="schoolBranches"
                          hide-details="auto"
                          label="Select Branch"
                          solo
                          required
                          id="school_branch"
                        ></v-autocomplete>
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="student_curriculum">Curriculum</label>
                        <v-autocomplete
                          v-model="curriculum_id"
                          :items="curriculums"
                          hide-details="auto"
                          label="Select Curriculum"
                          solo
                          required
                          id="student_curriculum"
                        ></v-autocomplete>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="student_class">Class</label>
                        <v-autocomplete
                          v-model="class_id"
                          :items="studentClasses"
                          item-text="class_name"
                          item-value="id"
                          hide-details="auto"
                          label="Select Class"
                          solo
                          required
                          id="student_class"
                        >
                          <template v-slot:append-item>
                            <v-divider class="mb-2"></v-divider>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-action-text>
                                  <a href="javascript:void(0)" data-toggle="modal" data-target="#add_new_class">+ Add
                                    Class</a>
                                </v-list-item-action-text>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-autocomplete>
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="student_stream">Stream</label>
                        <v-autocomplete
                          v-model="stream_id"
                          :items="studentStreams"
                          item-text="stream_name"
                          item-value="id"
                          hide-details="auto"
                          label="Select Stream"
                          solo
                          required
                          id="student_stream"
                        >
                          <template v-slot:append-item>
                            <v-divider class="mb-2"></v-divider>
                            <v-list-item>
                              <v-list-item-content>
                                <v-list-item-action-text>
                                  <a href="javascript:void(0)" class="autocomplete-add-item" data-toggle="modal"
                                     data-target="#add_new_stream">+ Add Stream</a>
                                </v-list-item-action-text>
                              </v-list-item-content>
                            </v-list-item>
                          </template>
                        </v-autocomplete>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group">
                        <label class="form_label" for="student_enrollment_date">Enrollment Date <span
                          class="optional">(optional)</span></label>
                        <input type="date" v-model="student_enrollment_date" id="student_enrollment_date">
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="student_graduation">Expected Graduation Year</label>
                        <v-autocomplete
                          v-model="graduation_id"
                          :items="graduations"
                          hide-details="auto"
                          label="Select Graduation"
                          solo
                          required
                          id="student_graduation"
                        ></v-autocomplete>
                      </div>

                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>Guardian Details</v-expansion-panel-header>
                <v-expansion-panel-content>
                  <div class="full_width">
                    <div class="form_group text_center margin-bottom_md">
                      <label class="form_label" for="guardian_photo">Guardian Photo</label>
                      <cropper
                        class="cropper"
                        v-if="guardian_photo"
                        :src="guardian_photo"
                        ref="cropper"
                        :auto-zoom="true"
                        :stencil-size="{
                          width: 280,
                          height: 280
                        }"
                        :canvas="{
                          height: 200,
                          width: 200,
                        }"
                        image-restriction="stencil"
                      />

                      <div class="profile-photo" v-if="!guardian_photo">
                        <img :src="guardian_cropped_photo" width="200" height="200" alt="Guardian Photo"/>
                      </div>

                      <div class="file-upload">
                        <label class="file-upload__btn">
                          <input type="file" required @change="loadGuardianImage" id="guardian_photo" accept="image/*">
                          <svg width="20" height="17.3">
                            <use xlink:href="#camera_icon"></use>
                          </svg>
                          Upload image
                        </label>
                        <a href="javascript:void(0)" v-if="guardian_photo" class="file-upload__crop"
                           @click="cropGuardian">Use</a>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group">
                        <label class="form_label" for="guardian_name">Guardian Full Name</label>
                        <input type="text" v-model="guardian_name" id="guardian_name">
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group">
                        <label class="form_label" for="guardian_phone">Guardian Phone</label>
                        <vue-phone-number-input
                          v-model="guardian_phone"
                          :border-radius="10"
                          id="guardian_phone"
                          class="custom-phone"
                          default-country-code="KE"
                          required
                        ></vue-phone-number-input>
                      </div>
                    </div>
                  </div>
                  <div class="full_width">
                    <div class="left_form">
                      <div class="form_group">
                        <label class="form_label" for="guardian_email">Guardian Email</label>
                        <input type="email" required v-model="guardian_email" id="guardian_email">
                      </div>
                    </div>
                    <div class="right_form">
                      <div class="form_group autocomplete">
                        <label class="form_label" for="guardian_relationship">Guardian Relationship</label>
                        <v-autocomplete
                          v-model="relationship_id"
                          :items="relationships"
                          hide-details="auto"
                          label="Select Relationship"
                          solo
                          required
                          id="guardian_relationship"
                        ></v-autocomplete>
                      </div>
                    </div>
                  </div>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
            <div class="action_btns modal_footer">
              <input type="submit" value="Create Student">
              <a href="" class="cancel_btn" @click.prevent="closeModal">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';
import {Cropper} from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';
import 'vue-advanced-cropper/dist/theme.classic.css';

export default {
  name: "AddNewStudent",
  components: {VuePhoneNumberInput, Cropper},
  data() {
    return {
      branch_id: "",
      class_id: "",
      stream_id: "",
      nationality_id: "",
      curriculum_id: "",
      gender_id: "",
      graduation_id: "",
      relationship_id: "",
      panel: 0,
      coordinates: {
        width: 0,
        height: 0,
        left: 0,
        top: 0
      },
      student_cropped_photo: "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg",
      student_photo: null,
      student_name: "",
      student_email: "",
      student_dob: "",
      student_address: "",
      student_phone: "",
      student_graduation: "",
      student_enrollment_date: "",
      guardian_cropped_photo: "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg",
      guardian_photo: null,
      guardian_name: "",
      guardian_phone: "",
      guardian_email: "",
      guardian_relationship: "",
      inviteSuccess: false,
      alertClass: 'alert-success',
      alertMsg: '',
      schoolBranches: ['Westlands', 'Parklands', 'Mombasa', 'Thika', 'General Mathenge', 'Eldoret'],
      nationalities: ['Kenya', 'Nigeria', 'United Kingdom', 'United States of America'],
      curriculums: ['KCSE', 'IGCSE', 'IB'],
      genders: ['Male', 'Female', 'I would rather not say'],
      relationships: ['Father', 'Mother', 'Brother', 'Sister', 'Uncle', 'Aunt', 'Other'],
      graduations: ['December 2021', 'March 2022'],
      studentClasses: [],
      studentStreams: [],
    }
  },
  methods: {
    async inviteStudent() {
      this.$nuxt.$loading.start()
      let formData = new FormData()
      formData.append('student_name', this.student_name)
      formData.append('student_email', this.student_email)
      formData.append('class_id', this.class_id)
      formData.append('stream_id', this.stream_id)
      formData.append('school_id', 1)

      let response = await SchoolService.inviteStudent(this, formData).then(response => response.data)

      this.$nuxt.$loading.finish()

      if (!response.status) {
        this.alertClass = "alert-danger"
        this.alertMsg = response.message
        this.inviteSuccess = true
      } else {
        this.student_name = ''
        this.student_email = ''
        this.class_id = ''
        this.stream_id = ''

        let student = response.data;
        this.alertMsg = `<strong>${student.student_name}</strong> has been successfully invited`
        this.alertClass = "alert-success"
        this.inviteSuccess = true

        if (process.client) {
          setTimeout(function () {
            this.show_message = false
            window.location.reload(true);
          }, 2500);
        }
      }
    },

    cropStudent() {
      const {coordinates, canvas} = this.$refs.cropper.getResult();
      this.coordinates = coordinates;
      this.student_cropped_photo = canvas.toDataURL();
      this.student_photo = null;
    },

    loadStudentImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        //Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.student_photo) {
          URL.revokeObjectURL(this.student_photo);
        }

        const reader = new FileReader();
        reader.onload = e => {
          this.student_photo = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },

    cropGuardian() {
      const {coordinates, canvas} = this.$refs.cropper.getResult();
      this.coordinates = coordinates;
      this.guardian_cropped_photo = canvas.toDataURL();
      this.guardian_photo = null;
    },

    loadGuardianImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        //Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.guardian_photo) {
          URL.revokeObjectURL(this.guardian_photo);
        }

        const reader = new FileReader();
        reader.onload = e => {
          this.guardian_photo = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },

    closeModal(){
      $('#add_new_user').modal('hide')
    },

  },
  async fetch() {
    let response = await SchoolService.getStudentsBuild(this).then(response => response.data.data)
    if (response) {
      this.studentClasses = response.classes
      this.studentStreams = response.streams
    }
  },

  destroyed() {
    // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
    if (this.student_photo) {
      URL.revokeObjectURL(this.student_photo);
    }

    if (this.guardian_photo) {
      URL.revokeObjectURL(this.guardian_photo);
    }
  },
}
</script>

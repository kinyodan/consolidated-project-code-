<template>
  <div class="modal fade in" id="create_new_user" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Add User</h2>

            <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="showAlert">
              <span v-html="alertMsg"></span>
              <button type="button" class="close_alert" @click="showAlert = false" aria-label="Close">&times;
              </button>
            </div>
          </div>
          <form action="" @submit.prevent="showAlert">

            <div class="full_width">
              <div class="form_group text_center margin-bottom_md">
                <label class="form_label" for="user_photo">User Photo</label>
                <cropper
                  class="cropper"
                  v-if="user_photo"
                  :src="user_photo"
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

                <div class="profile-photo" v-if="!user_photo">
                  <img :src="user_cropped_photo" width="200" height="200" alt="User Photo"/>
                </div>

                <div class="file-upload">
                  <label class="file-upload__btn">
                    <input type="file" required @change="loadUserImage" id="user_photo" accept="image/*">
                    <svg width="20" height="17.3">
                      <use xlink:href="#camera_icon"></use>
                    </svg>
                    Upload image
                  </label>
                  <a href="javascript:void(0)" v-if="user_photo" class="file-upload__crop"
                     @click="cropUser">Use</a>
                </div>
              </div>
            </div>
            <div class="full_width">
              <div class="left_form">
                <div class="form_group autocomplete">
                  <label class="form_label" for="user_title">Title</label>
                  <v-autocomplete
                    v-model="title_id"
                    :items="titles"
                    hide-details="auto"
                    label="Select Title"
                    solo
                    id="user_title"
                  ></v-autocomplete>
                </div>
              </div>
              <div class="right_form">
                <div class="form_group">
                  <label class="form_label" for="user_name">Full Name</label>
                  <input type="text" required v-model="user_name" id="user_name">
                </div>
              </div>
            </div>
            <div class="full_width">
              <div class="left_form">
                <div class="form_group">
                  <label class="form_label" for="user_preferred_name">Preferred Name <span
                    class="optional">(optional)</span></label>
                  <input type="text" v-model="user_preferred_name" id="user_preferred_name">
                </div>
              </div>
              <div class="right_form">
                <div class="form_group">
                  <label class="form_label" for="user_phone">Phone <span class="optional">(optional)</span></label>
                  <vue-phone-number-input
                    v-model="user_phone"
                    :border-radius="10"
                    id="user_phone"
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
                  <label class="form_label" for="user_email">Email</label>
                  <input type="email" required v-model="user_email" id="user_email">
                </div>
              </div>
              <div class="right_form">
                <div class="form_group autocomplete">
                  <label class="form_label" for="user_gender">Gender <span
                    class="optional">(optional)</span></label>
                  <v-autocomplete
                    v-model="gender_id"
                    :items="genders"
                    hide-details="auto"
                    label="Select Gender"
                    solo
                    id="user_gender"
                  ></v-autocomplete>
                </div>
              </div>
            </div>
            <div class="full_width">
              <div class="form_group autocomplete">
                <label class="form_label" for="user_nationality">Nationality <span
                  class="optional">(optional)</span></label>
                <v-autocomplete
                  v-model="nationality_id"
                  :items="nationalities"
                  hide-details="auto"
                  label="Select Nationality"
                  solo
                  id="user_nationality"
                ></v-autocomplete>
              </div>
            </div>
            <div class="full_width">
              <div class="left_form">
                <div class="form_group autocomplete">
                  <label class="form_label" for="user_school">School</label>
                  <v-autocomplete
                    v-model="school_id"
                    :items="schools"
                    hide-details="auto"
                    label="Select School"
                    solo
                    id="user_school"
                  ></v-autocomplete>
                </div>
              </div>
              <div class="right_form">
                <div class="form_group autocomplete">
                  <label class="form_label" for="user_role">Role</label>
                  <v-autocomplete
                    v-model="role_id"
                    :items="roles"
                    hide-details="auto"
                    label="Select Role"
                    solo
                    id="user_role"
                  ></v-autocomplete>
                </div>
              </div>
            </div>

            <div class="action_btns modal_footer">
              <input type="submit" value="Create User">
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
  name: "AddNewUser",
  components: {VuePhoneNumberInput, Cropper},
  data() {
    return {
      user_cropped_photo: "https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg",
      user_photo: null,
      coordinates: {
        width: 0,
        height: 0,
        left: 0,
        top: 0
      },
      title_id: "",
      titles: ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof'],
      user_name: "",
      user_preferred_name: "",
      user_email: "",
      user_phone: "",
      gender_id: "",
      genders: ['Male', 'Female', 'I would rather not say'],
      nationality_id: "",
      nationalities: ['Kenya', 'Nigeria', 'United Kingdom', 'United States of America'],
      school_id: "",
      schools: ['School 1', 'School 2', 'School 3'],
      role_id: "",
      roles: ['Administrator', 'Counsellor'],
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
    }
  },
  methods: {
    cropUser() {
      const {coordinates, canvas} = this.$refs.cropper.getResult();
      this.coordinates = coordinates;
      this.user_cropped_photo = canvas.toDataURL();
      this.user_photo = null;
    },

    loadUserImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        //Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
        if (this.user_photo) {
          URL.revokeObjectURL(this.user_photo);
        }

        const reader = new FileReader();
        reader.onload = e => {
          this.user_photo = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },

    closeModal() {
      $('#create_new_user').modal('hide')
    },

  },

  destroyed() {
    // Revoke the object URL, to allow the garbage collector to destroy the uploaded before file
    if (this.user_photo) {
      URL.revokeObjectURL(this.user_photo);
    }
  },
}
</script>

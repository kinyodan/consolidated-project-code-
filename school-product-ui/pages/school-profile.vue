<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">School Profile</h2></div>
    </div>
    <div class="dash_content_body">
      <v-app class="dash_inner_content">
        <form action="" @submit.prevent="updateSchoolProfile" class="school-profile">
          <div class="alert alert-dismissible fade show" :class="alertClass" role="alert" v-if="updateSuccess">
            <span v-html="alertMsg"></span>
            <button type="button" class="close_alert" @click="updateSuccess = false" aria-label="Close">&times;
            </button>
          </div>

          <div class="fieldset">
            <div>
              <span class="field-label">School Logo</span>
            </div>
            <div class="inline-field">
              <v-img
                :src="logoUrl"
                alt="School logo"
                max-width="292"
                min-height="85"
                class="school-logo"
              >
                <template v-slot:placeholder>
                  <v-row
                    class="fill-height ma-0"
                    align="center"
                    justify="center"
                  >
                    <v-progress-circular
                      indeterminate
                      color="grey"
                    ></v-progress-circular>
                  </v-row>
                </template>
              </v-img>
              <div class="file-upload">
                <label class="file-upload__btn">
                  <input type="file" required @change="loadSchoolLogo" id="school_logo" accept="image/*">
                  <svg width="20" height="17.3">
                    <use xlink:href="#camera_icon"></use>
                  </svg>
                  Replace logo
                </label>
              </div>
            </div>
          </div>

          <v-divider class="my-6"></v-divider>

          <div class="fieldset">
            <div>
              <span class="field-label">Contact Details</span>
            </div>
            <div>
              <div class="form_group">
                <label class="form_label" for="school_email">Email</label>
                <input type="email" required id="school_email">
              </div>
              <div class="form_group">
                <label class="form_label" for="school_phone">Phone</label>
                <vue-phone-number-input
                  :border-radius="10"
                  id="school_phone"
                  class="custom-phone"
                  default-country-code="KE"
                  required
                ></vue-phone-number-input>
              </div>
              <div class="form_group">
                <label class="form_label" for="school_country">Country</label>
                <input type="text" readonly id="school_country" class="readonly" value="Kenya">
              </div>
              <div class="form_group">
                <label class="form_label" for="school_address">Address</label>
                <textarea required id="school_address"></textarea>
              </div>
            </div>
          </div>

          <v-divider class="my-6"></v-divider>

          <div class="fieldset">
            <div>
              <span class="field-label">Link Multiple Curriculums</span>
            </div>
            <div>
              <div class="form_group autocomplete">
                <v-select
                  v-model="selectedCurriculums"
                  :items="curriculums"
                  hide-details
                  multiple
                  clearable
                  solo
                >
                  <template v-slot:prepend-item>
                    <v-list-item
                      ripple
                      @mousedown.prevent
                      @click="toggleSelectCurriculums"
                    >
                      <v-list-item-action>
                        <v-icon :color="selectedCurriculums.length > 0 ? '#18244F darken-4' : ''">
                          {{ selectCurriculumIcon }}
                        </v-icon>
                      </v-list-item-action>
                      <v-list-item-content>
                        <v-list-item-title>
                          Select All
                        </v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider class="mt-2"></v-divider>
                  </template>

                  <template v-slot:selection="{ item, index }">
                    <v-chip v-if="index === 0">
                      <span>{{ item }}</span>
                    </v-chip>
                    <span v-if="index === 1" class="grey--text text-caption">
                      (+{{ selectedCurriculums.length - 1 }} others)
                    </span>
                  </template>
                </v-select>
              </div>
            </div>
          </div>

          <v-divider class="my-6"></v-divider>

          <div class="action_btns">
            <a class="edit_btn" @click="updateSchoolProfile">Save Changes</a>
          </div>
        </form>
      </v-app>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import VuePhoneNumberInput from 'vue-phone-number-input';
import 'vue-phone-number-input/dist/vue-phone-number-input.css';

export default {
  name: "school-profile",
  components: {VuePhoneNumberInput},
  middleware: ['auth'],
  data() {
    return {
      logoUrl: "",
      updateSuccess: false,
      alertClass: 'alert-success',
      alertMsg: '',
      curriculums: ['KCSE', 'IGCSE', 'IB'],
      selectedCurriculums: [],
    }
  },
  async fetch() {
    let school = await SchoolService.getSchool(this).then(response => response.data)
    if (school.status) {
      //this.logoUrl = school.data.school_logo_url
    }
  },
  computed: {
    selectAllCurriculums() {
      return this.selectedCurriculums.length === this.curriculums.length
    },
    selectSomeCurriculums() {
      return this.selectedCurriculums.length > 0 && !this.selectAllCurriculums
    },
    selectCurriculumIcon() {
      if (this.selectAllCurriculums) return 'mdi-close-box'
      if (this.selectSomeCurriculums) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },
  methods: {
    async updateSchoolProfile() {

    },
    loadSchoolLogo(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          this.logoUrl = e.target.result;
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },
    toggleSelectCurriculums() {
      this.$nextTick(() => {
        if (this.selectAllCurriculums) {
          this.selectedCurriculums = []
        } else {
          this.selectedCurriculums = this.curriculums.slice()
        }
      })
    },
  },
}
</script>

<style scoped>

</style>

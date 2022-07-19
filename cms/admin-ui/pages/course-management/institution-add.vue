<template>
  <div>
    <PageHeader :title="title" :items="items"/>

    <div class="row">
      <div class="col-12">
        <!--   Link back to  list   -->
        <div>
          <router-link class="btn btn-success waves-effect waves-light mb-3" to="/course-management/institution-list"><i
            class="mdi mdi-chevron-left mr-1"></i> Back to Institutions
          </router-link>
        </div>
        <!--   End Link back to  list   -->

        <!--   Form   -->
        <div class="card">
          <div class="card-body">
            <p class="card-title-desc">
              Add the Institution details.
            </p>
            <div class="alert alert-danger" v-if="institutionFormSubmittedError">
              <p>{{ institutionFormSubmittedErrorText }}</p>
            </div>
            <form-wizard @onComplete="institutionFormSubmit">
              <tab-content title="Institution Details" :selected="true">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Name</label>
                      <input v-model="formData.institution_name" type="text"
                             class="form-control"
                             placeholder="Institution Name" name="institution_name"
                             :class="{'is-invalid': hasError('institution_name')}"/>
                      <div v-if="hasError('institution_name')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_name.required">Please provide a valid name.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Institution Type</label>
                      <v-select
                        :options="institution_types"
                        :class="{'is-invalid': hasError('institution_type')}"
                        label="name" :reduce="name => name.id"
                        :value="formData.institution_type"
                        v-model="formData.institution_type">

                      </v-select>
                      <div v-if="hasError('institution_type')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_type.required">Please select the Institution type.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Ownership Type</label>
                      <v-select
                        :options="ownership_types"
                        :class="{'is-invalid': hasError('ownership_type')}"
                        :value="formData.ownership_type"
                        v-model="formData.ownership_type">
                      </v-select>
                      <div v-if="hasError('ownership_type')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.ownership_type.required">Please select the Ownership type.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description</label>
                      <ckeditor v-model="formData.description" :editor="editor"></ckeditor>
                      <div v-if="hasError('description')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.description.required">Please provide the short description.</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Institution Overview</label>
                      <ckeditor v-model="formData.profile_details" :editor="editor"></ckeditor>
                      <div v-if="hasError('profile_details')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.profile_details.required">Please provide the institution overview.</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Country</label>
                      <v-select
                        :options="countries"
                        label="name"
                        :reduce="name => name.iso_code"
                        :value="formData.country_code"
                        :class="{'is-invalid': hasError('country_code')}"
                        v-model="formData.country_code">

                      </v-select>
                      <div v-if="hasError('country_code')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.country_code.required">Please select the institution country.</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>City</label>
                      <input v-model="formData.city" type="text" class="form-control"
                             placeholder="City" name="city" :class="{'is-invalid': hasError('city') }"/>
                      <div v-if="hasError('city')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.city.required">Please enter the institution city.</span>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Logo</label>
                      <div class="col-md-12 mb-3" v-if="formData.logo_url">
                        <img class="rounded mr-2" :alt="formData.institution_name"
                             width="200" :src="formData.logo_url"
                             data-holder-rendered="true" />
                      </div>
                      <input ref="institution_logo" v-on:change="handleLogoUpload()" type="file" class="form-control"
                             placeholder="Logo" name="institution_logo"
                             :class="{'is-invalid': hasError('institution_logo')}"/>
                      <div v-if="hasError('institution_logo')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_logo.required">Please enter the institution logo.</span>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Website</label>
                      <input v-model="formData.website_url" type="url" class="form-control"
                             placeholder="https://xxxxxx.edu" name="website_url"
                             :class="{'is-invalid': hasError('website_url')}"/>
                      <div v-if="hasError('website_url')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.website_url.required">Please enter the institution website.</span>
                        <span v-if="!$v.formData.website_url.url">Please enter a valid website link.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Enquiry Form Link</label>
                      <input v-model="formData.inquiry_form_url" type="url" class="form-control"
                             placeholder="https://xxxxxx.edu/enquire" name="inquiry_form_url"
                             :class="{'is-invalid': hasError('inquiry_form_url')}"/>
                      <div v-if="hasError('inquiry_form_url')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.inquiry_form_url.required">Please enter the institution enquiry form link.</span>
                        <span v-if="!$v.formData.inquiry_form_url.url">Please enter a valid website link.</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Registration Date</label>
                      <date-picker v-model="formData.date_registered"
                                   :class="{'is-invalid': hasError('date_registered')}"
                                   name="date_registered"
                                   type="year" lang="en"
                                   placeholder="Select Year">
                      </date-picker>
                      <div v-if="hasError('date_registered')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.date_registered.required">Please enter the institution registration date.</span>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="Contact Details">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Email Address</label>
                      <input v-model="formData.email_address" type="text" class="form-control"
                             placeholder="Email" name="email_address" :class="{'is-invalid': hasError('email_address')}"/>
                      <div v-if="hasError('email_address')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.email_address.required">Please enter the institution email.</span>
                        <span v-if="!$v.formData.email_address.email">Please enter a valid email.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Phone Number</label>
                      <input v-model="formData.phone_number" type="text" class="form-control"
                             placeholder="e.g +254 xxx xxx xxx" name="phone_number"
                             :class="{'is-invalid': hasError('phone_number')}"/>
                      <div v-if="hasError('phone_number')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.phone_number.required">Please enter the institution phone number.</span>
                        <!--<span v-if="!$v.formData.phone_number.email">Please enter a valid email.</span>-->
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Postal Address</label>
                      <textarea
                        v-model="formData.university_postal_address"
                        :class="{'is-invalid': hasError('university_postal_address')}"
                        class="form-control"
                        name="university_postal_address"
                        id="university_postal_address"
                        rows="5">
                      </textarea>

                      <div v-if="hasError('university_postal_address')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.university_postal_address.required">Please enter the institution postal address.</span>
                      </div>
                    </div>

                  </div>
                </div>
              </tab-content>
              <tab-content title="Admissions">

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Admissions Email Address</label>
                      <input v-model="formData.academic_office_email_address" type="text" class="form-control"
                             placeholder="Email" name="academic_office_email_address" :class="{
                    'is-invalid': hasError('academic_office_email_address')
                  }"/>
                      <div v-if="hasError('academic_office_email_address')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.academic_office_email_address.required">Please enter the admissions email.</span>
                        <span v-if="!$v.formData.academic_office_email_address.email">Please enter a valid email.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Admissions Office Phone Number</label>
                      <input v-model="formData.academic_office_phone_number" type="text" class="form-control"
                             placeholder="e.g +254 xxx xxx xxx" name="academic_office_phone_number"
                             :class="{'is-invalid': hasError('academic_office_phone_number')}"/>
                      <div v-if="hasError('academic_office_phone_number')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.academic_office_phone_number.required">Please enter the admissions phone number.</span>
                        <!--<span v-if="!$v.formData.phone_number.email">Please enter a valid email.</span>-->
                      </div>
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Admissions Postal Address</label>
                      <textarea
                        v-model="formData.academic_office_postal_address"
                        :class="{'is-invalid': hasError('academic_office_postal_address')}"
                        class="form-control"
                        name="academic_office_postal_address"
                        id="academic_office_postal_address"
                        rows="5">
                      </textarea>

                      <div v-if="hasError('academic_office_postal_address')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.academic_office_postal_address.required">Please enter the admissions postal address.</span>
                      </div>
                    </div>

                  </div>
                </div>
              </tab-content>
              <tab-content title="Finance ">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Finance Email Address</label>
                      <input v-model="formData.finance_office_email_address" type="text" class="form-control"
                             placeholder="Email" name="finance_office_email_address" :class="{
                    'is-invalid': hasError('finance_office_email_address')
                  }"/>
                      <div v-if="hasError('finance_office_email_address')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.finance_office_email_address.required">Please enter the finance email.</span>
                        <span v-if="!$v.formData.finance_office_email_address.email">Please enter a valid email.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Finance Phone Number</label>
                      <input v-model="formData.finance_office_phone_number" type="text" class="form-control"
                             placeholder="e.g +254 xxx xxx xxx" name="finance_office_phone_number"
                             :class="{'is-invalid': hasError('finance_office_phone_number')}"/>
                      <div v-if="hasError('finance_office_phone_number')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.finance_office_phone_number.required">Please enter the finance phone number.</span>
                        <!--<span v-if="!$v.formData.phone_number.email">Please enter a valid email.</span>-->
                      </div>
                    </div>

                  </div>
                </div>
              </tab-content>
              <tab-content title="Ranking & Accreditation">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Accredited By</label>
                      <input v-model="formData.accredited_by" type="text" class="form-control"
                             placeholder="Accreditation Body" name="accredited_by"
                             :class="{'is-invalid': hasError('accredited_by')}"/>
                      <div v-if="hasError('accredited_by')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.accredited_by.required">Please enter a valid accreditation body.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Acronym</label>
                      <input v-model="formData.accredited_by_acronym" type="text" class="form-control"
                             placeholder="Accreditation Body Acronym" name="accredited_by_acronym"
                             :class="{'is-invalid': hasError('accredited_by_acronym')}"/>
                      <div v-if="hasError('accredited_by_acronym')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.accredited_by_acronym.required">Please enter a valid accreditation body acronym.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Accreditation body website</label>
                      <input v-model="formData.accreditation_body_url" type="url" class="form-control"
                             placeholder="https://xxxx.com/" name="accreditation_body_url"
                             :class="{'is-invalid': hasError('accreditation_body_url')}"/>
                      <div v-if="hasError('accreditation_body_url')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.accreditation_body_url.required">Please enter a valid accreditation body.</span>
                        <span v-if="!$v.formData.accreditation_body_url.url">Please enter a valid url.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Global Ranking</label>
                      <input v-model="formData.global_ranking" type="number" class="form-control"
                             placeholder="" name="global_ranking"
                             :class="{'is-invalid': hasError('global_ranking')}"/>
                      <div v-if="hasError('global_ranking')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.global_ranking.numeric">Please enter a valid number.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Continental Ranking</label>
                      <input v-model="formData.continental_ranking" type="number" class="form-control"
                             placeholder="" name="continental_ranking"
                             :class="{'is-invalid': hasError('continental_ranking')}"/>
                      <div v-if="hasError('continental_ranking')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.continental_ranking.numeric">Please enter a valid number.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Regional Ranking</label>
                      <input v-model="formData.regional_ranking" type="number" class="form-control"
                             placeholder="" name="regional_ranking"
                             :class="{'is-invalid': hasError('regional_ranking')}"/>
                      <div v-if="hasError('regional_ranking')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.regional_ranking.numeric">Please enter a valid number.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Country Ranking</label>
                      <input v-model="formData.country_ranking" type="number" class="form-control"
                             placeholder="" name="country_ranking"
                             :class="{'is-invalid': hasError('country_ranking')}"/>
                      <div v-if="hasError('country_ranking')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.country_ranking.numeric">Please enter a valid number.</span>
                      </div>
                    </div>

                  </div>

                </div>
              </tab-content>
              <tab-content title="Location & SEO">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group position-relative">
                      <label>Main Campus Physical Location</label>
                      <textarea
                        v-model="formData.main_campus_physical_location"
                        :class="{'is-invalid': hasError('main_campus_physical_location')}"
                        name="main_campus_physical_location"
                        class="form-control"
                        id="main_campus_physical_location" rows="5"></textarea>
                      <div v-if="hasError('main_campus_physical_location')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.main_campus_physical_location.required">Please enter a valid location.</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Main Campus Latitude</label>
                      <input v-model="formData.main_campus_latitude" type="text" class="form-control"
                             placeholder="0.00" name="main_campus_latitude"
                             :class="{'is-invalid': hasError('main_campus_latitude')}"/>
                      <div v-if="hasError('main_campus_latitude')"
                           class="invalid-feedback">
                        <!--<span v-if="!$v.formData.main_campus_latitude.numeric">Please enter a valid latitude.</span>-->
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Main Campus Longitude</label>
                      <input v-model="formData.main_campus_longtitude" type="url" class="form-control"
                             placeholder="0.00" name="main_campus_longtitude"
                             :class="{'is-invalid': hasError('main_campus_longtitude')}"/>
                      <div v-if="hasError('main_campus_longtitude')"
                           class="invalid-feedback">
                        <!-- <span v-if="!$v.formData.main_campus_longtitude.numeric">Please enter a valid Longitude.</span>-->
                      </div>
                    </div>

                  </div>

                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group position-relative">
                      <label>SEO Keywords <i>(comma separated)</i></label>
                      <input v-model="formData.seo_keywords" type="text" class="form-control"
                             placeholder="university,best" name="seo_keywords"
                             :class="{'is-invalid': hasError('seo_keywords')}"/>
                      <div v-if="hasError('seo_keywords')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.seo_keywords.numeric">Please enter valid keywords.</span>
                      </div>
                    </div>
                  </div>


                  <div class="col-md-12">
                    <div class="form-group position-relative">
                      <label>SEO Description</label>
                      <textarea
                        v-model="formData.seo_description" class="form-control"
                        :class="{'is-invalid': hasError('seo_description')}"
                        name="seo_description"
                        id="seo_description" rows="5"></textarea>
                      <div v-if="hasError('seo_description')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.seo_description.required">Please enter a valid SEO description.</span>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
            </form-wizard>
          </div>
        </div>
        <!--   End Form   -->

      </div>
    </div>
  </div>
</template>

<script>
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";

import {
  required,
  email,
  minLength,
  maxLength,
  numeric,
  url,
  alphaNum,
  requiredIf
} from "vuelidate/lib/validators";

import {
  FormWizard,
  TabContent,
  ValidationHelper
} from "vue-step-wizard";
import "vue-step-wizard/dist/vue-step-wizard.css";

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  head() {
    return {
      title: `${this.title} | Institutions`
    };
  },
  components: {
    FormWizard,
    TabContent,
    vSelect,
    DatePicker,
    ckeditor:CKEditor.component
  },
  mixins: [ValidationHelper],
  data() {
    return {
      title: "Create new",
      items: [
        {
          text: "Institutions",
          href: "/course-management/institution-list"
        },
        {
          text: "Create",
          active: true
        }
      ],
      countries: [],
      institution_types: [],
      ownership_types:[],
      editor: ClassicEditor,
      formData: {
        institution_name: "",
        description: "",
        institution_type: "",
        ownership_type: "",
        country_code: "",
        city: "",
        email_address: "",
        university_postal_address: "",
        phone_number: "",
        institution_logo: "",
        website_url: "",
        inquiry_form_url: "",
        date_registered: "",
        profile_details: "",
        academic_office_phone_number: "",
        academic_office_email_address: "",
        academic_office_postal_address: "",
        finance_office_email_address: "",
        finance_office_phone_number: "",
        global_ranking: "",
        continental_ranking: "",
        regional_ranking: "",
        country_ranking: "",
        accredited_by: "",
        accredited_by_acronym: "",
        accreditation_body_url: "",
        main_campus_latitude: "",
        main_campus_longtitude: "",
        main_campus_physical_location: "",
        seo_description: "",
        seo_keywords: "",
      },
      validationRules: [{
        institution_name: {
          required,
          minLength: minLength(3),
          maxLength: maxLength(100),
        },
        description: {
          required,
          minLength: minLength(10),
        },
        profile_details:{
          required
        },
        institution_type: {
          required
        },
        ownership_type:{
          required
        },
        country_code: {
          required,
          maxLength: maxLength(2),
        },
        city: {
        },
        institution_logo: {
          required: requiredIf(function () {
            if (this.formData.logo_url || this.editAction) {
              return false
            }
            return true
          })
        },
        website_url: {
        },
        inquiry_form_url: {
        },
        date_registered: {

        }
      }, {
        email_address: {
          email
        },
        university_postal_address: {
        },
        phone_number: {
        },
      },
        {
          academic_office_phone_number: {
          },
          academic_office_email_address: {
          },
          academic_office_postal_address: {
          }
        },
        {
          finance_office_email_address: {
          },
          finance_office_phone_number: {
          }
        },
        {
          global_ranking: {

          },
          continental_ranking: {

          },
          regional_ranking: {

          },
          country_ranking: {

          },
          accredited_by: {
          },
          accredited_by_acronym: {
          },
          accreditation_body_url: {
            url
          }
        },
        {
          main_campus_latitude: {
          },
          main_campus_longtitude: {
          },
          main_campus_physical_location: {
          },
          seo_description: {
          },
          seo_keywords: {
          }
        }
      ],
      institutionFormSubmitted: false,
      institutionFormSubmittedError: false,
      institutionFormSubmittedErrorText: "",
      editAction: false,
      institutionCode: null,
      updateLogoFile: null,
    };

  },
  computed: {},
  created() {

    //get the form builder
    this.getFormBuilder()
    this.rollbar_init();

    //check if it is an edit action
    let code = this.$route.query.code
    if (code) {
      //set the title and edit actions
      this.editAction = true
      this.title = `Update ${code}`
      this.institutionCode = code

      //get the institution for editing
      this.getInstitutionToEdit(code);

    } else {
      this.editAction = false
      this.institutionCode = null
    }
  },
  methods: {
    rollbar_init(){
      // include and initialize the rollbar library with your access token
      var Rollbar = require('rollbar')
      var rollbar = new Rollbar({
        accessToken: process.env.ROLLBAR_TOKEN,
        captureUncaught: true,
        captureUnhandledRejections: true,
      })
      // record a generic message and send it to Rollbar
      rollbar.log(console.error("hello from cms"));

    },
    /**
     * Validation type submit
     */
    // eslint-disable-next-line no-unused-vars
    institutionFormSubmit(e) {

      var institutionData = new FormData();
      let self = this;

      //get the form data
      for (let key in this.formData) {
        //unset afew keys
        //bad keys
        let badArr = ['system_internal_ranking','has_updates',
          'indexing_error','approved_at',
          'approved_by','is_picked_for_indexing',
          'indexing_object_id','logo_cdn_upload_error',
          'time_picked_for_indexing','temp_log_path','time_taken_to_index','type','deleted_by']
        if (!badArr.includes(key)) {
          if(this.formData[key] && this.formData[key] !== 'null'){
            institutionData.append(key, this.formData[key]);
          }
        }
      }
      //append the logo on edit
      if (this.editAction) {
        institutionData.append('institution_logo', this.updateLogoFile);

        InstitutionsService.updateInstitution(institutionData,this.institutionCode).then(response => {
          let data = response.data;
          if (data.status) {
            //redirect to the listing page
            self.$router.push('/course-management/institution-list');
          } else {
            self.institutionFormSubmittedError = true
            self.institutionFormSubmittedErrorText = data.message
          }
        })
      } else {
        InstitutionsService.postInstitution(institutionData).then(response => {
          let data = response.data;
          if (data.status) {
            //redirect to the listing page
            self.$router.push('/course-management/institution-list');
          } else {
            self.institutionFormSubmittedError = true
            self.institutionFormSubmittedErrorText = data.message
          }
        })
      }    
    },
    handleLogoUpload() {
      //check if we are updating or just creating
      if (this.editAction) {
        this.updateLogoFile = this.$refs.institution_logo.files[0];
      } else {
        this.formData.institution_logo = this.$refs.institution_logo.files[0];
      }
    },
    /*updateProfileDetails() {
      this.formData.profile_details = this.formData.institution_name
    },*/
    getFormBuilder() {
      let self = this;
      InstitutionsService.getInstitutionBuild().then(response => {
        let data = response.data
        if (data.status) {
          self.countries = data.data.countries
          self.institution_types = data.data.institution_type
          self.ownership_types = data.data.ownership_type
        }
      })
    },
    getInstitutionToEdit(code) {
      let self = this;
      InstitutionsService.getInstitution(code).then(response => {
        let data = response.data
        if (data.status) {
          self.formData = data.data
        }
      });
    }
  },
  validationRules: {},
  middleware: "authentication",
  name: "InstitutionAdd",
}
</script>

<style scoped>
.vue-step-wizard {
  background: none;
  padding: 0;
  width: auto;
  height: auto;
}
</style>

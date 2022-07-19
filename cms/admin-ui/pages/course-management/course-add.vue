<template>
  <div>
    <PageHeader :title="title" :items="items"/>

    <div class="row">
      <div class="col-12">
        <!--   Link back to  list   -->
        <div>
          <router-link class="btn btn-success waves-effect waves-light mb-3" to="/course-management/courses-list"><i
            class="mdi mdi-chevron-left mr-1"></i> Back to Courses
          </router-link>
        </div>
        <!--   End Link back to  list   -->

        <div class="card">
          <div class="card-body">
            <p class="card-title-desc">
              Add the course details.
            </p>
            <div class="alert alert-danger" v-if="courseFormSubmittedError">
              <p>{{ courseFormSubmittedErrorText }}</p>
            </div>

            <!--   The Course Form         -->
            <form-wizard @onComplete="courseFormSubmit">
              <tab-content title="Course Details" :selected="true">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Name</label>
                      <input v-model="formData.course_name" type="text"
                             class="form-control"
                             placeholder="Course Name" name="course_name"
                             :class="{'is-invalid': hasError('course_name')}"/>
                      <div v-if="hasError('course_name')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.course_name.required">Please provide a valid name.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Course Type</label>
                      <v-select
                        :options="courseTypes"
                        :class="{'is-invalid': hasError('course_type')}"
                        label="name" :reduce="name => name.id"
                        :value="formData.course_type"
                        v-model="formData.course_type">

                      </v-select>
                      <div v-if="hasError('course_type')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.course_type.required">Please select the course type.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Graduate Level</label>
                      <v-select
                        :options="graduateLevels"
                        :class="{'is-invalid': hasError('graduate_level')}"
                        :value="formData.graduate_level"
                        v-model="formData.graduate_level">
                      </v-select>
                      <div v-if="hasError('graduate_level')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.graduate_level.required">Please select the graduate level.</span>
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
                        :class="{'is-invalid': hasError('country_code')}"
                        label="name" :reduce="name => name.iso_code"
                        :value="formData.country_code"
                        v-model="formData.country_code">

                      </v-select>
                      <div v-if="hasError('country_code')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.country_code.required">Please select the country.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Institution</label>
                      <v-select
                        :options="institutions"
                        :class="{'is-invalid': hasError('country_code')}"
                        label="institution_name" :reduce="institution_name => institution_name.institution_code"
                        :value="formData.institution_code"
                        v-model="formData.institution_code">

                      </v-select>
                      <div v-if="hasError('institution_code')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_code.required">Please select the institution.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Institution Course Code</label>
                      <input v-model="formData.institution_course_code" type="text"
                             class="form-control"
                             placeholder="e.g. BBIT 53474" name="institution_course_code"
                             :class="{'is-invalid': hasError('institution_course_code')}"/>
                      <div v-if="hasError('institution_course_code')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_course_code.required">Please provide a valid code.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Faculty & Department</label>
                       <!-- dsc -->
                      <treeselect v-model="linked_disciplines" :multiple="true" :options="academicDisciplines" />

                      <div v-if="hasError('linked_disciplines')"
                           class="invalid-feedback">
                        <span v-if="!$v.linked_disciplines.required">Please select the course discipline.</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Faculty Code</label>
                      <input v-model="formData.faculty_code" type="text"
                             class="form-control"
                             placeholder="FIT" name="faculty_code"
                             :class="{'is-invalid': hasError('faculty_code')}"/>
                      <div v-if="hasError('faculty_code')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.faculty_code.required">Please provide a valid code.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Course Website Link</label>
                      <input v-model="formData.institution_website_course_url" type="url" class="form-control"
                             placeholder="https://xxxxxx.edu/bbit" name="institution_website_course_url"
                             :class="{'is-invalid': hasError('institution_website_course_url')}"/>
                      <div v-if="hasError('institution_website_course_url')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_website_course_url.required">Please enter the course website.</span>
                        <span
                          v-if="!$v.formData.institution_website_course_url.url">Please enter a valid website link.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Course Application Link</label>
                      <input v-model="formData.institution_website_application_form_url" type="url" class="form-control"
                             placeholder="https://xxxxxx.edu/bbit/apply" name="institution_website_application_form_url"
                             :class="{'is-invalid': hasError('institution_website_application_form_url')}"/>
                      <div v-if="hasError('institution_website_application_form_url')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.institution_website_application_form_url.required">Please enter the course application link.</span>
                        <span v-if="!$v.formData.institution_website_application_form_url.url">Please enter a valid website link.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Course Image</label>
                      <div class="col-md-12 mb-3" v-if="formData.course_image">
                        <img class="rounded mr-2" :alt="formData.course_name"
                             :src="formData.course_image"
                             data-holder-rendered="true"/>
                      </div>
                      <input ref="course_image" v-on:change="handleLogoUpload()" type="file" class="form-control"
                             placeholder="Course Image" name="course_image"
                             :class="{'is-invalid': hasError('course_image')}"/>
                      <div v-if="hasError('course_image')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.course_image.required">Please enter the course image.</span>
                      </div>
                    </div>

                  </div>
                </div>
              </tab-content>
              <tab-content title="Course Overview">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Course Description</label>
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
                      <label>Detailed Course Overview</label>
                      <ckeditor v-model="formData.course_overview" :editor="editor"></ckeditor>
                      <div v-if="hasError('course_overview')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.course_overview.required">Please provide the course overview.</span>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="Admission Details">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Attendance Type</label>
                      <multiselect
                        :value="formData.attendance_type"
                        v-model="formData.attendance_type"
                        :options="attendanceTypes"
                        :class="{'is-invalid': hasError('attendance_type')}"
                      >
                      </multiselect>
                      <div v-if="hasError('attendance_type')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.attendance_type.required">Please select the attendance type.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Available Learning Modes</label>
                      <v-select
                        :options="learningModes"
                        :class="{'is-invalid': hasError('learning_mode')}"
                        label="name" :reduce="name => name.id"
                        :value="formData.learning_mode"
                        v-model="formData.learning_mode">
                      </v-select>
                      <div v-if="hasError('learning_mode')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.learning_mode.required">Please select at least one learning mode .</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label>Enrollment Dates</label>
                  </div>
                  <div class="col-md-12">
                    <div class="row">
                      <template v-for="(field,index) in enrollmentFields" class="mt-2">
                        <div class="col-md-3">
                          <label>Start Month</label>
                          <multiselect v-model="field.start_month" :options="monthsInYear"></multiselect>
                        </div>
                        <div class="col-md-3">
                          <label>End Month</label>
                          <multiselect v-model="field.end_month" :options="monthsInYear"></multiselect>
                        </div>
                        <div class="col-md-4">
                          <label>Deadline</label>
                          <date-picker v-model="field.deadline" :first-day-of-week="1" lang="en"></date-picker>
                        </div>
                        <div class="col-md-2">
                          <label>Action</label>
                          <a class="btn btn-danger btn-block inner" href="javascript:void(0);" @click.prevent="removeEnrollmentDate(index)" v-b-tooltip.hover title="Delete">
                            Remove
                          </a>
                        </div>
                      </template>
                    </div>
                  </div>
                  <div class="col-md-12 mt-2">
                    <button class="btn btn-primary" @click.prevent="addEnrollmentDates">Add Enrollment</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Course Requirements</label>
                      <ckeditor v-model="formData.course_requirements" :editor="editor"></ckeditor>
                      <div v-if="hasError('course_requirements')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.course_requirements.required">Please provide the course requirements.</span>
                      </div>
                    </div>
                  </div>
                </div>

              </tab-content>
              <tab-content title="Structure & Accreditation">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Course Duration Category</label>
                      <multiselect
                        :value="formData.course_duration_category"
                        v-model="formData.course_duration_category"
                        :options="courseDurationCategories"
                        :class="{'is-invalid': hasError('course_duration_category')}"
                      >
                      </multiselect>
                      <div v-if="hasError('attendance_type')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.course_duration_category.required">Please select the course duration category.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label>Course Duration</label>
                      <input v-model="formData.course_duration" type="text"
                             class="form-control"
                             placeholder="Course Duration" name="course_duration"
                             :class="{'is-invalid': hasError('course_duration')}"/>
                      <div v-if="hasError('course_duration')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.course_duration.required">Please provide a valid duration.</span>
                        <span v-if="!$v.formData.course_duration.numeric">Duration must be a number.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Detailed Course Structure</label>
                      <ckeditor v-model="formData.course_structure_breakdown" :editor="editor"></ckeditor>
                      <div v-if="hasError('course_structure_breakdown')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.course_structure_breakdown.required">Please provide the course structure.</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Accredited By</label>
                      <input v-model="formData.accredited_by" type="text"
                             class="form-control"
                             placeholder="Accredited By" name="accredited_by"
                             :class="{'is-invalid': hasError('accredited_by')}"/>
                      <div v-if="hasError('accredited_by')"
                           class="invalid-feedback">
                      </div>
                    </div>

                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label>Accredited By (Initials)</label>
                      <input v-model="formData.accredited_by_acronym" type="text"
                             class="form-control"
                             placeholder="e.g CHE" name="accredited_by_acronym"
                             :class="{'is-invalid': hasError('accredited_by_acronym')}"/>
                      <div v-if="hasError('accredited_by_acronym')"
                           class="invalid-feedback">
                      </div>
                    </div>

                  </div>
                  <div class="col-md-5">
                    <div class="form-group position-relative">
                      <label>Accreditation Organization Website</label>
                      <input v-model="formData.accreditation_organization_url" type="url"
                             class="form-control"
                             placeholder="http://example.com" name="accreditation_organization_url"
                             :class="{'is-invalid': hasError('accreditation_organization_url')}"/>
                      <div v-if="hasError('accreditation_organization_url')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.accreditation_organization_url.url">Please provide a valid website link.</span>
                      </div>
                    </div>

                  </div>
                </div>
              </tab-content>
              <tab-content title="Fees & Scholarships">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Standard Fees Billing Type</label>
                      <multiselect
                        :value="formData.standard_fee_billing_type"
                        v-model="formData.standard_fee_billing_type"
                        :options="standardFeeBillingTypes"
                        :class="{'is-invalid': hasError('standard_fee_billing_type')}"
                      >
                      </multiselect>
                      <div v-if="hasError('standard_fee_billing_type')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.standard_fee_billing_type.required">Please select the billing type.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Currency</label>
                      <input v-model="formData.currency" type="text"
                             class="form-control"
                             placeholder="USD" name="currency"
                             :class="{'is-invalid': hasError('currency')}"/>
                      <div v-if="hasError('currency')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.currency.required">Please select the currency.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label></label>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="ignore_first_year_fees_compute_based_on_total"
                               v-model="formData.ignore_first_year_fees_compute_based_on_total">
                        <label class="form-check-label" for="ignore_first_year_fees_compute_based_on_total">
                          Ignore First year fees compute based on Total
                        </label>
                      </div>
                    </div>

                  </div>

                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Standard Fee Payable</label>
                      <input v-model="formData.standard_fee_payable" type="text"
                             class="form-control"
                             placeholder="Standard Fee Payable" name="standard_fee_payable"
                             :class="{'is-invalid': hasError('standard_fee_payable')}"/>
                      <div v-if="hasError('standard_fee_payable')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.standard_fee_payable.required">Please provide standard fee.</span>
                        <span v-if="!$v.formData.standard_fee_payable.numeric">Standard fee must be a number.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Standard First Year Fee Payable (USD)</label>
                      <input v-model="formData.standard_first_year_fee_payable_usd" type="text"
                             class="form-control"
                             placeholder="Standard Fee Payable" name="standard_first_year_fee_payable_usd"
                             :class="{'is-invalid': hasError('standard_first_year_fee_payable_usd')}"/>
                      <div v-if="hasError('standard_first_year_fee_payable_usd')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.standard_first_year_fee_payable_usd.required">Please provide standard fee.</span>
                        <span v-if="!$v.formData.standard_first_year_fee_payable_usd.numeric">Standard fee must be a number.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Maximum Scholarship Available</label>
                      <input v-model="formData.maximum_scholarship_available" type="text"
                             class="form-control"
                             placeholder="Maximum Scholarship Available" name="maximum_scholarship_available"
                             :class="{'is-invalid': hasError('maximum_scholarship_available')}"/>
                      <div v-if="hasError('maximum_scholarship_available')"
                           class="invalid-feedback">
                        <span v-if="!$v.formData.maximum_scholarship_available.numeric">Maximum Scholarship Available must be a number.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Detailed Standard Fees Structure</label>
                      <ckeditor v-model="formData.standard_fee_breakdown" :editor="editor"></ckeditor>
                      <div v-if="hasError('standard_fee_breakdown')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.standard_fee_breakdown.required">Please provide the standard fee structure.</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Foreign Fees Billing Type</label>
                      <multiselect
                        :value="formData.foreign_student_fee_billing_type"
                        v-model="formData.foreign_student_fee_billing_type"
                        :options="standardFeeBillingTypes"
                        :class="{'is-invalid': hasError('foreign_student_fee_billing_type')}"
                      >
                      </multiselect>
                      <div v-if="hasError('foreign_student_fee_billing_type')"
                           class="invalid-feedback">

                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Foreign Fee Payable</label>
                      <input v-model="formData.foreign_student_fee_payable" type="number"
                             class="form-control"
                             placeholder="Foreign Fee Payable" name="foreign_student_fee_payable"
                             :class="{'is-invalid': hasError('foreign_student_fee_payable')}"/>
                      <div v-if="hasError('foreign_student_fee_payable')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.foreign_student_fee_payable.numeric">Foreign fee must be a number.</span>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label>Foreign First Year Fee Payable (USD)</label>
                      <input v-model="formData.foreign_student_first_year_fee_payable_usd" type="number"
                             class="form-control"
                             placeholder="Foreign Fee Payable" name="foreign_student_first_year_fee_payable_usd"
                             :class="{'is-invalid': hasError('foreign_student_first_year_fee_payable_usd')}"/>
                      <div v-if="hasError('foreign_student_first_year_fee_payable_usd')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.foreign_student_first_year_fee_payable_usd.numeric">Foreign fee must be a number.</span>
                      </div>
                    </div>

                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Detailed Foreign Fees Structure</label>
                      <ckeditor v-model="formData.foreign_student_fee_breakdown" :editor="editor"></ckeditor>
                      <div v-if="hasError('foreign_student_fee_breakdown')"
                           class="invalid-feedback">
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
              <tab-content title="SEO">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group position-relative">
                      <label>Meta Keywords <i>(comma separated)</i></label>
                      <input v-model="formData.meta_keywords" type="text" class="form-control"
                             placeholder="university,best" name="meta_keywords"
                             :class="{'is-invalid': hasError('meta_keywords')}"/>
                      <div v-if="hasError('meta_keywords')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.meta_keywords.numeric">Please enter valid keywords.</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group position-relative">
                      <label>Meta Description</label>
                      <textarea
                        v-model="formData.meta_description" class="form-control"
                        :class="{'is-invalid': hasError('meta_description')}"
                        name="meta_description"
                        id="meta_description" rows="5"></textarea>
                      <div v-if="hasError('meta_description')"
                           class="invalid-feedback">
                        <span
                          v-if="!$v.formData.meta_description.required">Please enter a valid meta description.</span>
                      </div>
                    </div>
                  </div>
                </div>
              </tab-content>
            </form-wizard>
            <!--   End The Course Form         -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CoursesService from "~/helpers/course-services/CoursesService";
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
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

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";


export default {
  name: "course-add",
  components: {
    FormWizard,
    TabContent,
    vSelect,
    Multiselect,
    DatePicker,
    ckeditor: CKEditor.component,
    Treeselect
  },
  mixins: [ValidationHelper],
  head() {
    return {
      title: `${this.title} | Course`
    };
  },
  data() {
    return {
      title: "Add a Course",
      items: [{
        text: "Course Management"
      },
        {
          text: "Courses",
        },
        {
          text: "Add a Course",
          active: true
        }
      ],
      courseFormSubmittedError: false,
      courseFormSubmittedErrorText: "",
      countries: [],
      institutions: [],
      courseTypes: [],
      graduateLevels: [],
      learningModes: [],
      academicDisciplines: [],
      attendanceTypes: [],
      standardFeeBillingTypes: [],
      courseDurationCategories: [],
      courseImage: "",
      courseCode: "",
      editAction: false,
      monthsInYear: [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
      ],
      enrollmentFields: [{
        start_month: "",
        end_month: "",
        deadline: "",
      }],
      selectLearningModes: [],
      editor: ClassicEditor,
      linked_disciplines:[],
      formData: {
        //basic course Details
        linked_blog_articles:[],
        linked_course_categories:[],

        country_code: "",
        institution_code: "",
        course_name: "",
        course_type: "",
        graduate_level: "",
        institution_course_code: "",
        faculty_code: "N/A",
        discipline_code: "",
        institution_website_course_url: "",
        institution_website_application_form_url: "",
        course_image: "",

        //Course overview section
        description: "",
        course_overview: "",

        //course admission details
        attendance_type: "",
        learning_mode: [],
        course_requirements: "",
        enrollment_details: [],

        //course structure & accreditation details
        course_duration_category: "",
        course_duration: 0,
        course_structure_breakdown: "",
        accredited_by: "",
        accredited_by_acronym: "",
        accreditation_organization_url: "",

        //fees and scholarships details
        standard_fee_billing_type: "",
        currency: "",
        ignore_first_year_fees_compute_based_on_total:0,
        standard_fee_payable: 0,
        standard_first_year_fee_payable_usd: 0,
        standard_fee_breakdown: "",
        foreign_student_fee_billing_type: "",
        foreign_student_fee_payable: 0,
        foreign_student_first_year_fee_payable_usd: 0,
        foreign_student_fee_breakdown: "",
        maximum_scholarship_available: 0,

        //seo section
        meta_keywords: "",
        meta_description: "",

      },
      validationRules: [
        //  Course details validations
        {
          country_code: {},
          institution_code: {},
          discipline_code: {
            required
          },
          course_name: {
            minLength: minLength(3),
            maxLength: maxLength(200),
          },
          course_type: {},
          graduate_level: {},
          institution_course_code: {
            minLength: minLength(3),
            maxLength: maxLength(200),
          },
          institution_website_course_url: {
            url
          },
          institution_website_application_form_url: {
            url
          },
          course_image: {
            /*required: requiredIf(function () {
              if (this.formData.course_image || this.editAction) {
                return false
              }
              return true
            })*/
          },
          course_description: {},
        },

        //course overview section
        {
          description: {},
          course_overview: {}
        },

        // Admission Details validations
        {
          attendance_type: {},
          learning_mode: {
            required
          },
          course_requirements: {},
          enrollment_details: {}
        },

        //course structure and accreditation details
        {
          course_duration_category: {},
          course_duration: {},
          course_structure_breakdown: {},
          accreditation_organization_url: {
            url
          }
        },

        //fees and scholarships details
        {
          standard_fee_billing_type: {},
          currency: {},
          ignore_first_year_fees_compute_based_on_total:{},
          standard_first_year_fee_payable_usd: {},
          standard_fee_payable: {},
          standard_fee_breakdown: {},
          foreign_student_fee_billing_type: {},
          foreign_student_fee_payable: {},
          foreign_student_first_year_fee_payable_usd: {},
          foreign_student_fee_breakdown: {},
          maximum_scholarship_available: {},
        },

        //course seo section
        {
          meta_description: {},
          meta_keywords: {}
        },
      ],
    }
  },
  created() {
    //get the course builder
    this.getCourseBuilder()
    this.rollbar_init();

    //check if it is an edit action
    let code = this.$route.query.code
    if (code) {
      //set the title and edit actions
      this.editAction = true
      this.title = `Update ${code}`
      this.courseCode = code

      //get the institution for editing
      this.getCourseToEdit(code);

    } else {
      this.editAction = false
      this.courseCode = null
    }
  },
  methods: {
    rollbar_init(){
      // include and initialize the rollbar library with your access token
      console.log("rollbar initialized....")
      var Rollbar = require('rollbar')
      console.log("rollbar_init require")
      var rollbar = new Rollbar({
        accessToken: process.env.ROLLBAR_TOKEN,
        captureUncaught: true,
        captureUnhandledRejections: true,
      })
      console.log("rollbar_init consolelog")
      // record a generic message and send it to Rollbar
      rollbar.log(console.error("hello from cms"));

    }, 
    getCourseBuilder() {
      let self = this;
      CoursesService.getCourseBuild().then(response => {
        let data = response.data
        if (data.status) {
          //assign the data
          self.courseTypes = data.data.types
          self.learningModes = data.data.learning_mode
          //self.academicDisciplines = data.data.academic_disciplines
          self.attendanceTypes = data.data.attendance_type
          self.standardFeeBillingTypes = data.data.standard_fee_billing_type
          self.courseDurationCategories = data.data.course_duration_category
          self.institutions = data.data.institutions
          self.graduateLevels = data.data.graduate_levels
          
          data.data.academic_disciplines.forEach((discipline) => {
             var item = { "id": discipline.id, "label": discipline.discipline_name }
             self.academicDisciplines.push({ "id": discipline.id, "label": discipline.discipline_name } )
          });
        }
      });

      //use institutions builder to get the country data
      InstitutionsService.getInstitutionBuild().then(response => {
        let data = response.data
        if (data.status) {
          self.countries = self.countries = data.data.countries
        }
      });
    },
    getCourseToEdit(code) {
      let self = this;
      CoursesService.getCourse(code).then(response => {
        let data = response.data
        if (data.status) {
          self.formData = data.data

          //set the enrollment dates
          if (data.data.enrollment_details) {
            try {
              let enrollment_dates = JSON.parse(data.data.enrollment_details);
              self.enrollmentFields = enrollment_dates
            } catch (e) {
              //check if this is a comma separated string
            }

          }

          //set the correct discipline code
          if (data.data.discipline_code) {
            try {
              //get the course code from the disciplines object
              let discipline = self.academicDisciplines.find((discipline) => {
                return discipline.discipline_name === data.data.discipline_code
                  || discipline.discipline_code === data.data.discipline_code
                  || discipline.id == data.data.discipline_code
              });
              if (discipline) {
                self.formData.discipline_code = discipline.id
              } else {
                self.formData.discipline_code = ""
              }
            } catch (e) {
              self.formData.discipline_code = ""
            }
          }

          //set the correct Learmimg Mode
          if (data.data.learning_mode) {
            try {
              //get the main learning mode
              let strMode = self.convertJsonToString(data.data.learning_mode)
              //get the course code from the disciplines object
              let learning_mode = self.learningModes.find((mode) => {
                return mode.name === strMode
                  || mode.id == strMode
              });
              if (learning_mode) {
                self.formData.learning_mode = learning_mode.id
              } else {
                self.formData.learning_mode = ""
              }
            } catch (e) {
              self.formData.learning_mode = ""
            }
          }
        }
      });
    },
    handleLogoUpload() {
      //check if we are updating or just creating
      if (this.editAction) {
        this.courseImage = this.$refs.course_image.files[0];
      } else {
        this.formData.course_image = this.$refs.course_image.files[0];
      }
    },
    addEnrollmentDates() {
      this.enrollmentFields.push({
        start_month: "",
        end_month: "",
        deadline: "",
      })
    },
    removeEnrollmentDate(index){
      this.enrollmentFields.splice(index,1)
    },
    convertJsonToString(value){
      try{
        if(isNaN(value)) {
          let jsonObj = JSON.parse(value);
          if (typeof jsonObj === 'object') {
            return jsonObj[0]
          }
        }else{
          return value
        }
      }catch (e) {
        return value
      }
    },
    updateSelectedLearningModes(learningModes) {
      //go throught the selected values and set the selected ids
      let modes = [];
      learningModes.forEach((learningMode) => {
        modes.push(learningMode.name);
      });
      this.formData.learning_mode = JSON.stringify(modes)
    },
    courseFormSubmit(e) {
      var courseData = new FormData();
      let self = this;

      this.formData.linked_course_categories=JSON.stringify(this.linked_disciplines);
      //set the enrollment data
      this.formData.enrollment_details = JSON.stringify(this.enrollmentFields)
      //update foreign fee with default value as standard fee
      if(this.formData.foreign_student_fee_payable === 0){
        this.formData.foreign_student_fee_payable = this.formData.standard_fee_payable
      }

      //get the form data
      for (let key in this.formData) {
        //unset afew keys
        //bad keys
        let badArr = ["content_completeness_score", "course_name_slug", "popularity",
          "created_by", "updated_by", "approved_by", "discipline", "discipline_name", "course_rating", "institution_summary",
          "deleted_by", "created_at", "updated_at", "approved_at", "deleted_at", "is_active", "is_deleted",
        "is_featured","is_picked_for_unpublishing","is_published","popularity","should_unpublish"]
        if (!badArr.includes(key)) {
          if(key === 'ignore_first_year_fees_compute_based_on_total'){
            //check the value before appending to convert it to true
            if(this.formData.ignore_first_year_fees_compute_based_on_total){
              courseData.append('ignore_first_year_fees_compute_based_on_total', 1);
            }else{
              courseData.append('ignore_first_year_fees_compute_based_on_total', 0);
            }
          }else{
            courseData.append(key, this.formData[key]);
          }
        }
      }

      //submit the data
      if (this.editAction) {
        if (this.courseImage) {
          courseData.set('course_image', this.courseImage);
        }
        CoursesService.updateCourse(courseData, this.courseCode).then(response => {
          let data = response.data;
          if (data.status) {
            //redirect to the listing page
            self.$router.push('/course-management/courses-list');
          } else {
            self.courseFormSubmittedError = true
            self.courseFormSubmittedErrorText = data.message
          }
        })
      } else {
        CoursesService.postCourse(courseData).then(response => {
          let data = response.data;
          if (data.status) {
            //redirect to the listing page
            self.$router.push('/course-management/courses-list');
          } else {
            self.courseFormSubmittedError = true
            self.courseFormSubmittedErrorText = data.message
          }
        })
      }
    },

  },
  middleware: 'authentication'
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

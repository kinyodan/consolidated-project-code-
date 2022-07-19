
<template>
  <div>

    <div class="row">

      <div class="col-12">

        <!--   Form   -->
        <div class="card">



          <div class="card-body">

              <!--   Link back to  list   -->
              <div>
                <router-link class="btn btn-success waves-effect waves-light mb-3" to="/course-management/courses-list"><i
                  class="mdi mdi-chevron-left mr-1"></i> Back to Courses
                </router-link>
              </div>
              <!--   End Link back to  list   -->

              <PageHeader :title="formData.course_name" :items="items"/>

              <ul class="nav nav-tabs nav-tabs-custom mt-3 mb-2 ecommerce-sortby-list">

                  <li class="nav-item">
                     <a class="nav-link active tablinks tab-update" @click="tabs_control($event,'main')" href="javascript:void(0)">:Update Course Information |</a>
                  </li>
                  <li class="nav-item">
                       <a class="nav-link tablinks tab-highlight" @click="tabs_control($event,'blogt')" href="javascript:void(0)">
                        <span class=""> Blogs </span>
                       </a>
                  </li>

              </ul>

    <div class="row">

      <!-- tab 1 ... -->
      <div id="main" class="tabcontent active">

      <div class="col-12">


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


    <!-- tab 2 ... -->
    <div id="blogt" class="tabcontent">

      <div class="col-12">

        <div class="card">
          <div class="card-body">

              <b-container class="bv-example-row">
                <div class="select_container_0" >

                   <form v-on:submit.prevent="submitBlogCategory" class="form-horizontal" role="form">

                      <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-right crect">
                          submit
                          <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;" v-if="loadingData"></b-spinner>
                      </button>
                      <div class="select_container_row_0">
                        <multiselect v-model="blog_form.blog_category" :id="blog_categories" :options="blogCategory_list" :multiple="false" placeholder="Select Category" label="category_name" track-by="category_slug" :preselect-first="false">
                        </multiselect>
                      </div>

                   </form>

                </div>
                <hr>

                <b-spinner label="Loading..." v-if="searchkeywordsloading"></b-spinner>

                <!-- start search container-->
                <div class="row mt-4">
                  <div class="col-sm-12 col-md-6"></div>

                  <!-- Search -->
                  <div class="col-sm-12">
                    <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                      <label class="d-inline-flex align-items-center">
                        Search:
                        <b-form-input v-model="search_keywords" v-on:keyup="blogPostSearch(current_blog_categoryName,search_keywords,number_of_posts_per_page)" type="search" placeholder="Search..."
                                      class="form-control form-control-sm ml-2"></b-form-input>
                      </label>
                    </div>
                  </div>
                  <!-- End search -->
                </div>
                <!-- start search container-->


              <b-row>

                <b-col sm="auto" class="list_sec_b1" >

                   <div class="row">
                          <div class="col-12">
                              <div class="card">
                                  <div class="card-body">



                                      <div class="row">
                                          <div class="col-12">

                                  <!-- not linked Search Result begin -->

                                              <div v-if="search_keywords" >
                                                  <li  class="blog_title"  v-if="notlinked(blogPost,current_blog_categoryName, true )" v-for="blogPost in searchDatablogPosts">
                                                      <nuxt-link
                                                      :to="{path:'#' }"
                                                      class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                                            <div>
                                                                <img  src="~/assets/images/blogx.png" alt class="avatar-xs  mr-2 iconDetails_linked iconDetails"/>
                                                            </div>

                                                            <div  style='margin-left:10px;'>
                                                               <div v-html="blogPost.title.rendered"></div>
                                                               <div style="font-size:.6em">

                                                               </div>
                                                            </div>
                                                      </nuxt-link>
                                                        <div></div>
                                                        <a href="javascript:void(0);"  class="px-2 move-right lnk_btn text-primary" @click="linkCourse(blogPost, blogPost.categories[0].name , true)"
                                                            v-b-tooltip.hover title="link">
                                                          <i class="uil uil-link font-size-18"></i>link with course
                                                          <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_linking==blogPost.post_id"></b-spinner>
                                                        </a></br></br>

                                                        <i class="uil uil-tag  font-size-18"></i><small>{{blogPost.categories[0].name}}</small>
                                                        <div></div>
                                                        <small class="move-right"><i>posted on: {{blogPost.date}}</i></small>
                                                        </br>
                                                        <div></div> </br>
                                                        <hr>


                                                  </li>
                                                  <div class="end-of-search"> <small > End of search result </small></div>
                                                  <hr>
                                              </div>

                                  <!-- Search resultQuery END  -->

                                          <!-- start display if search is on -->
                                          <div v-if="search_keywords" ></div>

                                          <!-- start display if search is off -->
                                          <div v-else>
                                              <li v-if="notlinked(blogPost,blogPost.categories[0].name,false )" class="blog_title"  v-for="blogPost in blogPosts">

                                                  <nuxt-link
                                                  :to="{path:'#' }"
                                                  class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                                        <div>
                                                            <img  :src="blogPost.featured_image" alt class="avatar-xs  mr-2 iconDetails"/>
                                                        </div>
                                                        <div style='margin-left:60px;'>
                                                           {{blogPost.title}}
                                                           <div style="font-size:.6em">

                                                           </div>
                                                        </div>
                                                  </nuxt-link>
                                                    <div></div>
                                                    <a href="javascript:void(0);"  class="px-2 move-right lnk_btn text-primary" @click="linkCourse(blogPost, blogPost.categories[0].name )"
                                                        v-b-tooltip.hover title="link">
                                                      <i class="uil uil-link font-size-18"></i>link with course
                                                      <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_linking==blogPost.post_id"></b-spinner>
                                                    </a></br></br>
                                                    <i class="uil uil-tag  font-size-18"></i><small>{{blogPost.categories[0].name}}</small>
                                                    <div></div>
                                                    <small class="move-right"><i>posted on: {{blogPost.post_date}}</i></small>
                                                    </br>
                                                    <div></div> </br>
                                                    <hr>

                                              </li></br>
                                          </div>
                                          <!-- END display if search is off -->


                                          </div>
                                      </div>
                                      <!-- end row -->
                                        <div class="row">
                                          <div class="col">
                                            <div class="dataTables_paginate paging_simple_numbers float-right">
                                              <ul class="pagination pagination-rounded">
                                                <!-- pagination -->
                                                <b-pagination @input="handlePageChange" v-model="currentPage" :total-rows="rows"
                                                              :per-page="perPage"></b-pagination>
                                              </ul>
                                            </div>
                                          </div>
                                        </div>
                                        </br></br>

                                  </div>
                              </div>
                              <!-- end card -->
                          </div>
                          <!-- end col -->
                      </div>
                      <!-- end row -->
                </b-col>
                <!--
                 ########### end left column ######## -->

                <b-col sm="auto" class="list_sec_b1">

                  <div class="row">
                    <div class="col-12">
                      <!-- Table -->
                      <h4 class="card-title">Linked blogs list</h4>
                      <hr>


            <!--  linked Search Result begin -->

                        <div v-if="search_keywords" >
                            <li  class="blog_title_l"  v-if="linked(blogPost,blogPost.categories[0].name, true )" v-for="blogPost in searchDatablogPosts">
                                <nuxt-link
                                :to="{path:'#' }"
                                class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                <div>
                                    <img  src="~/assets/images/blogx.png" alt class="avatar-xs  mr-2 iconDetails_linked iconDetails"/>
                                </div>

                                <div  style='margin-left:10px;'>
                                   <div v-html="blogPost.title.rendered"></div>
                                   <div style="font-size:.6em">

                                   </div>
                                </div>

                                </nuxt-link>
                                  <div></div>
                                <span class="unlnk_span">
                                  <a href="javascript:void(0);"  class="px-2 move-right unlnk_btn " @click="unlinkCourse(blogPost,current_blog_categoryName,formData.linked_blog_articles ,true)"
                                      v-b-tooltip.hover  title="link">
                                    <i class="uil uil-link-broken font-size-18"></i><span class="unlink_span">Unlink</span>
                                    <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_unlinking==blogPost.post_id"></b-spinner>

                                  </a></br></br>

                                  <i class="uil uil-tag  font-size-18"></i><small>{{blogPost.categories[0].name}}</small>
                                  <div></div>
                                  <small class="move-right"><i>posted on: {{blogPost.date}}</i></small>
                                  </br>
                                  <div></div> </br>
                                </span>
                                <hr>
                            </li>
                              <div class="end-of-search"> <small > End of search result </small></div>
                              <hr>

                        </div>

            <!-- linked Search resultQuery END  -->

                        <div class="col-sm-12" >

                               <!-- start display if search is on -->
                               <div v-if="search_keywords" ></div>

                                <!-- start display if search is off -->
                                <div v-else>
                                 <li v-if="linked(blogPost,blogPost.categories[0].name )"  class="blog_title_l" v-for="blogPost in blogPosts">
                                    <nuxt-link
                                    :to="{path:'#' }"
                                    class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                          <div>
                                              <img  :src="blogPost.featured_image" alt class="avatar-xs  mr-2 iconDetails_linked iconDetails"/>
                                          </div>
                                          <div style='margin-left:60px;'>
                                             {{blogPost.title}}
                                             <div style="font-size:.6em">
                                             </div>
                                          </div>

                                    </nuxt-link>

                                    <span class="unlnk_span">
                                        <a href="javascript:void(0);"  class="px-2 move-right unlnk_btn " @click="unlinkCourse(blogPost,blogPost.categories[0].name,formData.linked_blog_articles ,false)"
                                            v-b-tooltip.hover  title="link">
                                          <i class="uil uil-link-broken font-size-18"></i><span class="unlink_span">Unlink</span>
                                          <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_unlinking==blogPost.post_id"></b-spinner>

                                        </a></br></br>
                                        <i class="uil uil-tag  font-size-18"></i><small>{{blogPost.categories[0].name}}</small>
                                        <div></div>
                                        &nbsp;<small class="move-right"><i>posted on: {{blogPost.post_date}}</i></small>
                                    </span>

                                    </br>
                                    <hr>

                                </li>
                                </div>
                                <!-- END display if serch is off -->


                                <!-- start display for linked during serach on -->
                                <div v-if="linked_during_search ">
                                 <li v-if="linked(blogPost,blogPost.categories[0].name )"  class="blog_title_l" v-for="blogPost in blogPosts">
                                    <nuxt-link
                                    :to="{path:'#' }"
                                    class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                          <div>
                                              <img  :src="blogPost.featured_image" alt class="avatar-xs  mr-2 iconDetails_linked iconDetails"/>
                                          </div>
                                          <div style='margin-left:60px;'>
                                             {{blogPost.title}}
                                             <div style="font-size:.6em">
                                             </div>
                                          </div>

                                    </nuxt-link>

                                    <span class="unlnk_span">
                                        <a href="javascript:void(0);"  class="px-2 move-right unlnk_btn " @click="unlinkCourse(blogPost,blogPost.categories[0].name,formData.linked_blog_articles ,false)"
                                            v-b-tooltip.hover  title="link">
                                          <i class="uil uil-link-broken font-size-18"></i><span class="unlink_span">Unlink</span>
                                          <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_unlinking==blogPost.post_id"></b-spinner>

                                        </a></br></br>
                                        <i class="uil uil-tag  font-size-18"></i><small>{{blogPost.categories[0].name}}</small>
                                        <div></div>
                                        &nbsp;<small class="move-right"><i>posted on: {{blogPost.post_date}}</i></small>
                                    </span>

                                    </br>
                                    <hr>

                                </li>
                                </div>
                                <!-- END display for linked during serach on  -->




                        </div>

                      <div class="row">
                        <div class="col">
                          <div class="dataTables_paginate paging_simple_numbers float-right">
                            <ul class="pagination pagination-rounded">
                              <!-- pagination -->
                              <b-pagination @input="handlePageChange" v-model="currentPage" :total-rows="rows"
                                            :per-page="perPage"></b-pagination>
                            </ul>
                          </div>
                        </div>
                      </div>
                      </br></br>


                    </div>
                   </div>

                </b-col>
                <!--
                 ########### end right column ######## -->

              </b-row>
            </b-container>

            </div>
            </div>
            </div>


    </div>

    </div>

    </div>

    </div>

    </div>

  </div>
</template>

<script>
import CoursesService from "~/helpers/course-services/CoursesService";
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";
import BlogService from "~/helpers/services/BlogService";

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
      blogCategory_list:[],
      blogPosts:[],
      searchDatablogPosts:[],
      blogindex_list:[],
      blog_categories:[],
      blogsposts_forlinking:[],
      current_blog_category_slug:'',
      current_blog_categoryName:"",
      blog_form: {
         blog_category: '',
      },
      currentPage:1,
      perPage:10,
      totalRows:5,
      category_Blog_Post_count:0,
      linked_Blogs_count:0,
      search_keywords:null,
      search_keywords_linked:null,
      searchkeywordsloading:false,
      linked_during_search:false,
      number_of_posts_per_page:10,
      set_blog_discipline:'',
      set_blog_learningMode:'',
      filterlinkedblogs:null,
      filterblogs:null,
      linked_blogs_list:[],
      loadingData_linking:false,
      loadingData_unlinking:false,
      loadingData: false,
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
        linked_blog_articles:[],
        linked_course_categories:[],

        //basic course Details
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
          linked_disciplines:{
            required
          },
          country_code: {},
          institution_code: {},
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
    this.rollbar_init();
    //get the course builder
    this.getCourseBuilder();
    this.getBlogCategoryList();
    this.getIndexPosts();
    //this.prepare_linked_blogs();
  },
  computed: {
    resultQuery() {
      if (this.search_keywords) {
        this.blogPostSearch(this.current_blog_category_slug,this.search_keywords,this.number_of_posts_per_page)

      } else {
        return this.blogPosts;
      }
    },
    set_disciplines(){
        return this.formData.linked_course_categories=[];
    },
    rows() {
      return this.totalRows;
    },
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
        let data = response.data.data
        self.formData = data.course
        if(data.course.linked_blog_articles == null){
          self.formData.linked_blog_articles= [];
        }else{
          self.formData.linked_blog_articles= JSON.parse(self.formData.linked_blog_articles);
          //set linked blogs count
          self.linked_Blogs_count = self.formData.linked_blog_articles.length;
        }

        //set linked disciplines
        if(data.course.linked_disciplines){
        try {
          let linked_cats = JSON.parse(data.course.linked_disciplines)
          for(const discipline in linked_cats) {
            self.linked_disciplines.push(linked_cats[discipline].id)
          }
        }catch (e) {
        }
        }

        //set the enrollment dates
        if (data.course.enrollment_details) {
          try {
            let enrollment_dates = JSON.parse(data.course.enrollment_details);
            self.enrollmentFields = enrollment_dates
          } catch (e) {
            //check if this is a comma separated string
          }
        }

        self.set_blog_discipline = data.course.discipline;
        self.set_blog_learningMode= data.course.learning_mode;

        //set the correct discipline code
        if (data.course.discipline_code) {
          try {
            //get the course code from the disciplines object
            let discipline = self.academicDisciplines.find((discipline) => {
              return discipline.discipline_name === data.course.discipline_code
                || discipline.discipline_code === data.course.discipline_code
                || discipline.id == data.course.discipline_code
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
        if (data.course.learning_mode) {
          try {
            //get the main learning mode
            let strMode = self.convertJsonToString(data.course.learning_mode)
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

      //set linked_blog_articles data
      this.formData.linked_blog_articles = JSON.stringify(this.formData.linked_blog_articles);
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
    tabs_control(evt,el_name){

      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }

      document.getElementById(el_name).style.display = "block";
      evt.currentTarget.className += " active";
    },
    SelectName (option) {
      return `${option.keyword}`
    },
    onSelect (option, uid) {
    },
    addTag (newTag) {
      const parts = newTag.split(', ');

      const tag = {
        id: this.options.length + 1,
        keyword: parts.pop()
      }
      this.options.push(tag)
      this.formData.linked_course_categories.push(tag);
    },

//########## BLOGS TAB FUNCTIONALITY #######################################################
//
//
    getBlogCategoryList(){
        let self = this;
        let blogCategoryListRequest =  BlogService.getBlogCategories().then(response => {
          var blogCategorylist_response = response.data;
          self.blogCategory_list = blogCategorylist_response;
        })
    },
    getIndexPosts(){
        let self = this;
        let blogCategoryListRequest =  BlogService.getIndexPosts().then(response => {
          var blogindexlist_response = response.data;
          self.blogindex_list = blogindexlist_response;
          self.category_Blog_Post_count=self.blogindex_list.length;
        })
    },
    submitBlogCategory(){
      this.searchkeywordsloading = true;
      this.getBlogCategoryPosts(this.blog_form.blog_category.category_slug);
    },
    getBlogCategoryPosts(blog_category){

        this.search_keywords = null;
        let self = this;
        // categroy slug has been used as cetegory in this case to be set as blog category
        this.current_blog_category_slug=blog_category;
        this.setBlogCategoryName(this.blog_form.blog_category.category_slug);
        let blogCategoryListRequest =  BlogService.getBlogPosts(blog_category).then(response => {
          self.blogPosts = response.data.data;
          self.blogsposts_forlinking =[];
          this.searchkeywordsloading = false;
      })
    },
    setBlogCategoryName(category_slug){
      // setting the curent category name
      var indexofcategory = this.blogCategory_list.map(function(x) { return x.category_slug; }).indexOf(category_slug);
      this.current_blog_categoryName = this.blogCategory_list[indexofcategory].category_name;
    },
    linkCourse(blog_data, category,search){
    // BEGIN linkCourse method
       if (search==true){
          var postID=blog_data.id;
       }else{
          var postID=blog_data.post_id;
       }

      let self = this;
      // set the relevant field for course update
      this.formData.discipline_code = this.set_blog_discipline.id;
      this.formData.discipline_name = this.set_blog_discipline.discipline_name;
      this.formData.discipline_name = this.set_blog_discipline.discipline_name;
      this.formData.learning_mode = this.set_blog_learningMode;

      //ensure formData.linked_blog_articles its not stringyfied
      if (typeof this.formData.linked_blog_articles =="string"){
       this.formData.linked_blog_articles = JSON.parse(this.formData.linked_blog_articles);
      }
      //set linked_discipline data
      var set_linked_categories=[];
      this.formData.linked_course_categories.forEach((discipline) => {
        set_linked_categories.push(discipline.id)
      });
      this.formData.linked_course_categories=JSON.stringify(set_linked_categories);
      var linked_blog_articles_to_stringify = this.formData.linked_blog_articles
        if(this.formData.linked_blog_articles.length !== 0){
            // ##FIRST LEVEL CHECK:=> check if cetegory already exists in the linked article and get its index
            this.formData.linked_blog_articles.forEach(function (item, index) {
            if(item.category.name=== category){
              // ##FIRST LEVEL CHECK:=>  if cetegory exists find its selected_post_id field and push new post ids into it
              // ##SECOND LEVEL CHECK:=> check if blog post id exists in selcted_post_id array
              var postIdexists = item.category.selected_post_ids.includes(blog_data.post_id);
              if (postIdexists) {
                
              // ##SECOND LEVEL CHECK:=> if post id exists do nothing
              }else{
                // ##SECOND LEVEL CHECK:=> if post doesnt exists create field and push into selected+post id array
                self.blogsposts_forlinking.push(blog_data.post_id)
                Array.prototype.push.apply(item.category.selected_post_ids,self.blogsposts_forlinking);
              }
            }else{
              var contg_blogsposts_forlinking =[];
              // ##FIRST LEVEL CHECK:=>  if cetegory doest exists create  field and push new post ids into it
              // ##SECOND LEVEL CHECK:=> check if blog post id exists in selcted_post_id array
              var postIdexists = item.category.selected_post_ids.includes(blog_data.post_id);
              if (postIdexists) {
                // ##SECOND LEVEL CHECK:=> if post id exists do nothing
              }else{
                // ##SECOND LEVEL CHECK:=> if post doesnt exists create field and push into selected post id array
                self.blogsposts_forlinking.push(blog_data.post_id)

                var linkingBlogData = {"category":{"name": category,"slug":self.current_blog_category_slug,"selected_post_ids":self.blogsposts_forlinking}}
                var linked_blog_articles = [];
                linked_blog_articles.push(linkingBlogData)

                Array.prototype.push.apply(self.formData.linked_blog_articles,linked_blog_articles);
              }
            }
          })

        }else{ // ELSE FOR IF LENGTH IS ZERO(this.formData.linked_blog_articles)###########################

          self.blogsposts_forlinking =[];

          // ##SECOND LEVEL CHECK:=> if post doesnt exists create field and push into selected+post id array
          self.blogsposts_forlinking.push(blog_data.post_id) //({"post_id": blog_data.post_id,"post_slug": blog_data.slug,"title": blog_data.title});

          var linkingBlogData = {"category":{"name": category,"slug":self.current_blog_category_slug,"selected_post_ids":self.blogsposts_forlinking}}
          var linked_blog_articles = [];
          linked_blog_articles.push(linkingBlogData)

          Array.prototype.push.apply(self.formData.linked_blog_articles,linked_blog_articles);
        }

      this.formData.linked_blog_articles =[];
      //set linked_blog_articles object to stringify
      var courseData = new FormData();

      //set the enrollment data
      this.formData.enrollment_details = JSON.stringify(this.enrollmentFields)

      //set the linked_blog_articles data
      this.formData.linked_blog_articles = JSON.stringify(linked_blog_articles_to_stringify);

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

      CoursesService.updateCourse(courseData, this.courseCode).then(response => {
        let data = response.data;
        if (data.status) {
          this.loadingData_linking=false;
          if (typeof this.formData.linked_blog_articles =="string"){
             this.formData.linked_blog_articles = JSON.parse(this.formData.linked_blog_articles);

          }else{
          }
          // clear self.blogsposts_forlinking array
          self.blogsposts_forlinking=[];

        }else{
          this.formData.linked_blog_articles =JSON.parse(this.formData.linked_blog_articles);
          // set the linked blogs count
          this.linked_Blogs_count = this.formData.linked_blog_articles.length;
          // clear self.blogsposts_forlinking array
          self.blogsposts_forlinking=[];
          self.courseFormSubmittedError = true
          self.courseFormSubmittedErrorText = data.message
        }
      })
      // END linkCourse method
    },
    unlinkCourse(blog_data ,category,linked_blog_articles ,search){
      this.formData.linked_blog_articles = linked_blog_articles;
      var updatevalidated = false;
      let self = this;
      if (search==true){
        var postID=blog_data.id;
      }else{
        var postID=blog_data.post_id;
      }

      //set linked_discipline data
      var set_linked_categories=[];
      this.formData.linked_course_categories.forEach((discipline) => {
        set_linked_categories.push(discipline.id)
      });

      this.formData.linked_course_categories=JSON.stringify(set_linked_categories);
      // set the relevant field for course update
      this.formData.discipline_code = this.set_blog_discipline.id;
      this.formData.discipline_name = this.set_blog_discipline.discipline_name;
      this.formData.discipline_name = this.set_blog_discipline.discipline_name;
      this.formData.learning_mode = this.set_blog_learningMode;

      this.formData.linked_blog_articles.forEach(function (item, index) {
          if(item.category.name=== category){
            // ##FIRST LEVEL CHECK:=>  if cetegory exists find its selected_post_id field and push new post ids into it
            // ##SECOND LEVEL CHECK:=> check if blog post id exists in selcted_post_id array
            var postIdexists = item.category.selected_post_ids.includes(postID);
            if (postIdexists) {
              // ##SECOND LEVEL CHECK:=> if post id exists REMOVE FROM LIST
                 item.category.selected_post_ids.splice(index);
                 updatevalidated = true;
            }else{

            }
          }else{
          }
      });

      if(updatevalidated){
        var courseData = new FormData();
        this.formData.linked_blog_articles=[];
        //set the enrollment data
        this.formData.enrollment_details = JSON.stringify(this.enrollmentFields)

        //set linked_blog_articles
        this.formData.linked_blog_articles =JSON.stringify(this.formData.linked_blog_articles);

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

        CoursesService.updateCourse(courseData, this.courseCode).then(response => {
          let data = response.data;
          if (response.data.status) {
            //redirect to the listing page
            this.linked_Blogs_count = this.formData.linked_blog_articles.length;
            this.getCourseToEdit(this.$route.query.code);
          } else {
            this.getCourseToEdit(this.$route.query.code);
            this.formData.linked_blog_articles =JSON.parse(this.formData.linked_blog_articles);
            self.courseFormSubmittedError = true
            self.courseFormSubmittedErrorText = data.message
          }
        })
      }else{

      }
    },
    notlinked(post,category,search){
      if (search==true){
        var postID=post.id
      }else{
        var postID=post.post_id
      }

      var state = true;
      let self = this;
      if (typeof this.formData.linked_blog_articles =="string"){
         var linked_blog_articles = JSON.parse(this.formData.linked_blog_articles);

      }else{
         var linked_blog_articles = this.formData.linked_blog_articles;
      }

      linked_blog_articles.forEach(function (item, index) {

        if(item.category.slug=== self.current_blog_category_slug){
          var postIdexists = item.category.selected_post_ids.includes(postID);
          if (postIdexists){
            // ##SECOND LEVEL CHECK:=> if post id exists return false and dont show it in blogs feed
             state= false;

          }else{
             state = true;
          }
        }

      })
      return state;
    },
    linked(post,category,search){

      if (search==true){
        var postID=post.id
      }else{
        var postID=post.post_id
      }
      var state = false;
      let self = this;
      if (typeof this.formData.linked_blog_articles =="string"){
        var linked_blog_articles = JSON.parse(this.formData.linked_blog_articles);
      }else{
        var linked_blog_articles = this.formData.linked_blog_articles;
      }

      linked_blog_articles.forEach(function (item, index) {
        if(item.category.slug== self.current_blog_category_slug){
        var indexofpostId = item.category.selected_post_ids.indexOf(postID);
          if (indexofpostId !== -1) {
          // ##SECOND LEVEL CHECK:=> if post id exists return false and dont show it in blogs feed
            state= true;
          }else{
            state = false;
          }
        }
      })
      return state
    },
    prepare_linked_blogs(){
      let code = this.$route.query.code
      let self = this;
      CoursesService.getCourse(code).then(response => {
        let data = response.data
        if (data.status){
          if(data.data.linked_blog_articles == null){
            self.formData.linked_blog_articles= [];
          }else{
            self.formData.linked_blog_articles= JSON.parse(data.data.linked_blog_articles);
          }
        }
      })
    },
    loading_for_linking(post_id){
        this.loadingData_linking=post_id;
    },
    loading_for_unlinking(post_id){
        this.loadingData_unlinking=post_id;
    },
    handlePageChange(page){
      //get the data
      this.getBlogCategoryPosts(this.blog_form.blog_category.category_slug,page)
    },
    blogPostSearch(category_name,course_category_name,search_keywords,number_of_posts_per_page){
      let blogSearchRequest =  BlogService.blogSearch(category_name,course_category_name,search_keywords,number_of_posts_per_page).then(response => {
        this.searchDatablogPosts = response.data;
        this.blogsposts_forlinking =[];
      })
    }
//
//
//########## BLOGS TAB FUNCTIONALITY ####################################################
//
//


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

.iconDetails {
 margin-left:2%;
float:left;
height:40px;
width:40px;
}

.container2 {
    width:80%;
}

.move-right{
  margin-top:1em;
}

.crect{
  margin-top:0em;
}

.move-right a{
  color:black;
}

.move-right li a i{
  color:black;
}

.form_sec0{
  max-width:62%;
  min-width:62%;
}

.list_sec1{
  max-width:38%;
  min-width:38%;
}

.list_sec0{
  max-width:55%;
  min-width:55%;
}

.list_sec_b1{
  max-width:50%;
  min-width:50%;
}

.select_container_0{
  max-width:80%;
  min-width:80%;
}

.select_container_row_0{
  max-width:80%;
  min-width:80%;
}


.ckeditor_intab_container{
  max-width:100%;
}

.name-cont-1{
  width:100%;
}

.blog_title{
  max-width:90%;
  min-width:55%;
}

.blog_title_l{
  max-width:90%;
  min-width:55%;
}

.blog_title_l a{
  font-size: 0.8rem;
}

li{
  list-style:none;
}

.iconDetails{
  height:90px;
  width:90px;
}

.iconDetails_linked{
  height:55px;
  width:55px;
}

.lnk_btn{
  background: #cccccc;
  padding:2px;
  border-radius:4px;

}

.unlnk_btn{
  background: #9C1305;
  padding:2px;
  border-radius:4px;

}

.unlnk_span a {
  color: #ffffff !important;
}

.end-of-search{
  color:#cccccc;
  text-align: center;
}

</style>

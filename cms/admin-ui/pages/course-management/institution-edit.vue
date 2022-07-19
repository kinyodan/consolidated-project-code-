<template>
  <div>

    <div class="row">

      <div class="col-12">

        <!--   Form   -->
        <div class="card">
          <div class="card-body">
            <!--   Link back to  list   -->
            <div>
              <router-link class="btn btn-success waves-effect waves-light mb-3"
                           to="/course-management/institution-list"><i
                class="mdi mdi-chevron-left mr-1"></i> Back to Institutions
              </router-link>
            </div>
            <div v-for="i in disciplinesList" >
              {{i.discipline}}
            </div>
            <!--   End Link back to  list   -->

            <PageHeader :title="formData.institution_name" :items="items"/>
            {{ formData.course_name }}

            <ul class="nav nav-tabs nav-tabs-custom mt-3 mb-2 ecommerce-sortby-list">

              <li class="nav-item">
                <a class="nav-link active tablinks tab-update" @click="tabs_control($event,'main')"
                   href="javascript:void(0)">:Update Institution |</a>
              </li>
              <li class="nav-item">
                <a class="nav-link tablinks tab-accreditation" @click="tabs_control($event,'accreditation')"
                   href="javascript:void(0)">
                  <span class=""> <i class="uil uil-award font-size-18"></i>Add Accreditation </span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link tablinks tab-alumni" @click="tabs_control($event,'alumni')"
                   href="javascript:void(0)">
                  <span class=""> <i class="uil uil-user-plus font-size-18"></i>Add alumni </span>

                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link tablinks tab-highlight" @click="tabs_control($event,'highlights')"
                   href="javascript:void(0)">
                      <span class=""> <i class="fas fas fa-info"></i> <i class="fas fas fas fas fa-star"></i>
                      Add Highlights </span>

                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link tablinks tab-website" @click="tabs_control($event,'website')"
                   href="javascript:void(0)">
                      <span class=""> <i class="fas fas fa-info"></i> <i class="fas fas fas fas fa-star"></i>
                      website </span>

                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link tablinks tab-Virtual-tour" @click="tabs_control($event,'Virtual-tour')"
                   href="javascript:void(0)">
                      <span class=""> <i class="fas fas fa-info"></i> <i class="fas fas fas fas fa-star"></i>
                      Virtual tour </span>

                </a>
              </li>

            </ul>

            <div class="row">

              <!-- tab 1 ... -->
              <div id="main" class="tabcontent active">

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
                            <span
                              v-if="!$v.formData.institution_type.required">Please select the Institution type.</span>
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
                            <span
                              v-if="!$v.formData.country_code.required">Please select the institution country.</span>
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
                                 data-holder-rendered="true"/>
                          </div>
                          <input ref="institution_logo" v-on:change="handleLogoUpload()" type="file"
                                 class="form-control"
                                 placeholder="Logo" name="institution_logo"
                                 :class="{'is-invalid': hasError('institution_logo')}"/>
                          <div v-if="hasError('institution_logo')"
                               class="invalid-feedback">
                            <span
                              v-if="!$v.formData.institution_logo.required">Please enter the institution logo.</span>
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
                                 placeholder="Email" name="email_address" :class="{
                                'is-invalid': hasError('email_address')
                              }"/>
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
                            <span
                              v-if="!$v.formData.academic_office_email_address.email">Please enter a valid email.</span>
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
                            <span
                              v-if="!$v.formData.finance_office_email_address.email">Please enter a valid email.</span>
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
                            <span
                              v-if="!$v.formData.seo_description.required">Please enter a valid SEO description.</span>
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

          <!-- tab 2 ... -->
          <div id="accreditation" class="tabcontent">

            <b-container class="bv-example-row">
              <b-row>
                <b-col sm="auto" class="form_sec0">

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">

                          <a v-if="editingAccreditation" class="bck-to-0 move-right" href="javascript:void(0);"
                             @click="clearAccreditationForm" role="button" aria-expanded="false">
                            New Accreditation
                          </a></br>
                          <h4 class="card-title">{{ accreditation_form_title }} for |
                            {{ formData.institution_name }}</h4>
                          <p class="card-title-desc">

                          </p>


                          <div class="row">
                            <div>
                              <img v-if="editingAccreditation" :src="acrreditation_small_organization_image" alt
                                   class="avatar-xs rounded-circle mr-2 iconDetails"/>
                            </div>
                            <div class="col-12">
                              <form v-on:submit.prevent="submitAccreditationForm" class="form-horizontal" role="form">

                                <label class="pd-hld">Name</label>
                                <input v-model="accreditation_form.organization_name" type="text"
                                       class="form-control"
                                       placeholder="Name" name="organization_name"/>

                                <label class="pd-hld">Description</label>
                                <div class="ckeditor_intab_container">
                                  <ckeditor v-model="accreditation_form.accreditation_description"
                                            id="accreditation_description" :editor="editor"></ckeditor>
                                </div>

                                <label class="pd-hld">Acronym</label>
                                <input v-model="accreditation_form.organization_acronym" id="acronym" type="text"
                                       class="form-control"
                                       placeholder="Name" name="organization_acronym"/>


                                <div class="form-group position-relative">

                                  <label class="pd-hld">Organization Image</label>
                                  <input ref="organization_image"
                                         v-on:change="handleLogoAccreditationUpload()"
                                         id="file_e1"
                                         type="file"
                                         accept="image/*" class="form-control" placeholder="Logo"
                                         name="accreditation_form.organization_image"/>

                                </div>

                                <div class="col-md-12 mb-3" v-if="accreditation_form.organization_image">
                                  <img class="rounded mr-2" :alt="`new ${accreditation_form.organization_image} logo`"
                                       width="200" :src="accreditation_form.organization_image"
                                       data-holder-rendered="true"/>
                                </div>


                                <button type="submit" id="submit_accred" class="btn btn-primary w-md">
                                  {{ accredbutton_text }}
                                  <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;"
                                             v-if="loadingData"></b-spinner>
                                </button>

                              </form>
                              </br>
                              <div class="alert alert-danger" v-if="accreditationFormSubmittedError">
                                <p>{{ accreditationFormSubmittedErrorText }}</p>
                              </div>

                              <div class="alert alert-success" v-if="accreditationcreateRecordSuccess">
                                <p>{{ accreditationcreateRecordmessage }}</p>
                              </div>

                            </div>
                          </div>
                          <!-- end row -->
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

                <b-col sm="auto" class="list_sec1">

                  <div class="row">
                    <div class="col-12">
                      <!-- Table -->
                      <h4 class="card-title">Accreditations list</h4>

                      <div class="row mt-4">
                        <div class="col-sm-12 col-md-6"></div>

                        <!-- Search -->
                        <div class="col-sm-12 col-md-6">
                          <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                            <label class="d-inline-flex align-items-center">
                              Search:
                              <b-form-input v-model="filteraccreditation" type="search" placeholder="Search..."
                                            class="form-control form-control-sm ml-2"></b-form-input>
                            </label>
                          </div>
                        </div>
                        <!-- End search -->

                      </div>

                      <div class="table-responsive mb-0">
                        <b-table
                          table-class="table table-centered datatable table-card-list"
                          thead-tr-class="bg-transparent"
                          :items="accreditations_list"
                          :fields="accreditation_fields"
                          responsive="sm"
                          :filter="filteraccreditation"
                          :filter-included-fields="accreditationfilterOn" @filtered="accreditationonFiltered">
                          :sort-by.sync="sortBy"
                          :sort-desc.sync="sortDesc"
                          >

                          <template v-slot:cell(name)="data">

                            <div class="name-cont-1">
                              <ul class="list-inline mb-0 move-right">

                                <li class="list-inline-item ">
                                  <a href="javascript:void(0);" :data-id="`${data.item.id}`" class="px-2 text-primary"
                                     @click="getEditAccreditation(data.item.id)"
                                     :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Edit">
                                    <i class="uil uil-pen font-size-18"></i>
                                  </a></br>

                                </li>

                                <li class="list-inline-item ">
                                  <a href="javascript:void(0);" :data-id="`${data.item.name}`" class="px-2"
                                     @click="accreditationpop_confirm(data.item.id)"
                                     :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                    <i class="uil uil-trash-alt font-size-18"></i>
                                  </a></br>

                                </li>

                              </ul>

                              <span class="`confirm-bx-${data.item.id}` actions-edt-del-in0"
                                    v-if="accreditationconfirm_check==data.item.id">

                                                    <span class="px-2 text-primary" v-b-tooltip.hover
                                                          title="Cancel delete" @click="accreditationdelete_cancel()">
                                                      <span class="active-st-1"><i
                                                        class="uil uil-check font-size-18"></i> Cancel </span>
                                                    </span></br></br>

                                <span class="px-2 text-primary" :data-delete="`${data.item.name}`" v-b-tooltip.hover
                                      title="Confirm delete" @click="delete_this_accreditation(data.item.id)">
                                                      <span class="active-st-0"> <i
                                                        class="uil uil-trash-alt font-size-17"></i>
                                                        confirm
                                                        <b-spinner label="Loading..."
                                                                   style="width: 0.7rem; height: 0.7rem;"
                                                                   v-if="accreditationloadingData_del"></b-spinner>
                                                      </span>
                                                    </span>

                                                  </span>

                              <div class='container2 t-wrap'>
                                <nuxt-link
                                  :to="{path:'/pathways/pathways/pathway', query:{id:data.item.id} }"
                                  class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                  <div>
                                    <img v-if="data.item.small_organization_image"
                                         :src="data.item.small_organization_image" alt
                                         class="avatar-xs rounded-circle mr-2 iconDetails"/>
                                  </div>
                                  <div style='margin-left:60px;'>
                                    {{ data.item.organization_name }}
                                    <div style="font-size:.6em">
                                    </div>
                                  </div>

                                </nuxt-link>

                              </div>


                            </div>
                          </template>

                        </b-table>

                      </div>
                      </br></br>

                      <div class="row">
                        <div class="col">
                          <div class="dataTables_paginate paging_simple_numbers float-right">
                            <ul class="pagination pagination-rounded">
                              <!-- pagination -->
                              <b-pagination @input="accreditationhandlePageChange" v-model="accreditationcurrentPage"
                                            :total-rows="accreditationdrows"
                                            :per-page="accreditationperPage"></b-pagination>
                            </ul>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                </b-col>
                <!--
                 ########### end left column ######## -->

              </b-row>
            </b-container>

          </div>

          <!-- tab 3 ... -->
          <div id="alumni" class="tabcontent">

            <b-container class="bv-example-row">
              <b-row>
                <b-col class="list_sec0a" sm="auto">

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">


                          <a v-if="editingAlumni" class="bck-to-0 move-right" href="javascript:void(0);"
                             @click="clearAlumniForm" role="button" aria-expanded="false">
                            New Alumni
                          </a></br>
                          <h4 class="card-title">{{ alumni_form_title }} for | {{ formData.institution_name }}</h4>

                          <p class="card-title-desc">
                          </p>

                          <div class="row">
                            <div class="col-12">
                              <form v-on:submit.prevent="submitAlumniForm" class="form-horizontal" role="form">

                                <div class="col-md-12 mb-3" v-if="alumni_form.alumnus_image">
                                  <img class="rounded mr-2" :alt="`new ${alumni_form.alumni_name} logo`"
                                       width="200" :src="alumni_form.alumnus_image"
                                       data-holder-rendered="true"/>
                                </div>

                                <label class="pd-hld">Name</label>
                                <input v-model="alumni_form.alumni_name" type="text"
                                       class="form-control"
                                       placeholder="Name" name="alumni_name"/>

                                <label class="pd-hld">Graduation year</label>
                                <date-picker v-model="alumni_form.graduation_year" type="year" id="grad_year"
                                             name="graduation_year" valueType="format"></date-picker>

                                <label class="pd-hld">Course taken</label>
                                {{alumni_form.course_taken}}
                                <b-form-select
                                  v-model="alumni_form.course_taken"
                                  :options="disciplinesList"
                                  class="mb-3 select_ecp"
                                  value-field="id"
                                  text-field="discipline_name"
                                  disabled-field="notEnabled"
                                  placeholder="Current position"
                                >
                                  <b-form-select-option :value="alumni_form.current_position">
                                    {{ alumni_form.current_position_text }}
                                  </b-form-select-option>
                                </b-form-select>

                                <label class="pd-hld">Current employer</label>
                                <input v-model="alumni_form.current_employer" type="text"
                                       class="form-control"
                                       placeholder="Current employer" name="current_employer"/>

                                <label class="pd-hld" id="current_position">Current position</label>
                                <b-form-select
                                  v-model="alumni_form.current_position"
                                  :options="jobtitle_list"
                                  class="mb-3 select_ecp"
                                  value-field="job_title"
                                  text-field="job_title"
                                  disabled-field="notEnabled"
                                  placeholder="Current position"
                                >
                                  <b-form-select-option :value="alumni_form.current_position">
                                    {{ alumni_form.current_position_text }}
                                  </b-form-select-option>
                                </b-form-select>

                                <div class="form-group position-relative">

                                  <label class="pd-hld">Personal profile url</label>
                                  <input v-model="alumni_form.personal_profile_url" type="text"
                                         class="form-control"
                                         placeholder="Personal profile url(i.e LinkedIn profile etc)"
                                         name="alumni_form.personal_profile_url"/>

                                  <label class="pd-hld">Alumnus Image</label>
                                  <input ref="alumnus_image" v-on:change="handleLogoAlumniUpload()" id="file_e11"
                                         type="file" class="form-control"
                                         placeholder="Logo" name="alumni_form.alumnus_image"/>

                                </div>

                                <div class="col-md-12 mb-3" v-if="alumni_form.alumnus_image">
                                  <img class="rounded mr-2" :alt="`new ${alumni_form.alumni_name} logo`"
                                       width="200" :src="alumni_form.alumnus_image"
                                       data-holder-rendered="true"/>
                                </div>

                                <button type="submit" id="submit_alumni" class="btn btn-primary w-md">
                                  {{ alumnibutton_text }}
                                  <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;"
                                             v-if="loadingData"></b-spinner>
                                </button>

                              </form>
                              </br>
                              <div class="alert alert-danger" v-if="alumniFormSubmittedError">
                                <p>{{ alumniFormSubmittedErrorText }}-{{ alumni_form.alumni_name }}</p>
                              </div>

                              <div class="alert alert-success" v-if="alumnicreateRecordSuccess">
                                <p>{{ alumnicreateRecordmessage }}</p>
                              </div>

                            </div>
                          </div>
                          <!-- end row -->
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

                <b-col sm="auto" class="list_sec0">

                  <div class="row">
                    <div class="col-12">
                      <!-- Table -->
                      <h4 class="card-title">Alumni list</h4>

                      <div class="row mt-4">
                        <div class="col-sm-12 col-md-6"></div>

                        <!-- Search -->
                        <div class="col-sm-12 col-md-6">
                          <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                            <label class="d-inline-flex align-items-center">
                              Search:
                              <b-form-input v-model="filteralumni" type="search" placeholder="Search..."
                                            class="form-control form-control-sm ml-2"></b-form-input>
                            </label>
                          </div>
                        </div>
                        <!-- End search -->

                      </div>

                      <div class="table-responsive mb-0">
                        <b-table
                          table-class="table table-centered datatable table-card-list"
                          thead-tr-class="bg-transparent"
                          :items="alumni_list"
                          :fields="alumni_fields"
                          responsive="sm"
                          :filter="filteralumni"
                          :filter-included-fields="alumnifilterOn" @filtered="alumnionFiltered">
                          :sort-by.sync="sortBy"
                          :sort-desc.sync="sortDesc"
                          >

                          <template v-slot:cell(name)="data">

                            <div class="name-cont-1">

                              <div class='container2 t-wrap'>

                                <nuxt-link
                                  :to="{path:'#', query:{id:data.item.id} }"
                                  class="px-2 text-primary" v-b-tooltip.hover title="Open to show">

                                  <div>
                                    <img v-if="data.item.alumnus_image" :src="data.item.alumnus_image" alt
                                         class="avatar-xs rounded-circle mr-2 iconDetails"/>
                                  </div>
                                  <div style='margin-left:60px;'>
                                    {{ data.item.alumni_name }}
                                    <div style="font-size:.6em">
                                    </div>
                                  </div>

                                </nuxt-link>

                              </div>

                              <div class='container2 t-wrap'>
                                <div>
                                  Course taken &nbsp;
                                  <i class="uil uil-graduation-hat font-size-22" style="color:#579AE5;"></i>
                                  <a href="javascript:void(0);" :data-id="`${data.item.alumni_name}`"
                                     class="px-2" v-b-tooltip.hover title="">
                                    {{ data.item.course_taken }}
                                  </a>
                                </div>

                                <div style='margin-left:60px;'>

                                  <div style="font-size:.6em">
                                  </div>
                                </div>

                              </div>

                              <div class='container2 t-wrap'>

                                <div>
                                    <span>
                                     Current employer &nbsp;
                                        <i class="uil uil-suitcase-alt font-size-22"
                                           style="color:#579AE5;"></i>
                                        <a href="javascript:void(0);"
                                           :data-id="`${data.item.alumni_name}`"
                                           class="px-2" v-b-tooltip.hover title="Delete">
                                         {{ data.item.current_employer }}
                                        </a>
                                    </span>
                                </div>

                                <div style='margin-left:60px;'>
                                  <div style="font-size:.6em">
                                  </div>
                                </div>

                              </div>

                              <div class='container2 t-wrap'>

                                <div>
                                    <span>
                                     Current Position &nbsp;
                                        <i class="fas fas fa-user-tie font-size-17"
                                           style="color:#579AE5;"></i>
                                        <a href="javascript:void(0);"
                                           :data-id="`${data.item.alumni_name}`"
                                           class="px-2" v-b-tooltip.hover title="Delete">
                                         {{ data.item.current_position_name }}
                                        </a>
                                    </span>
                                </div>

                                <div style='margin-left:60px;'>
                                  <div style="font-size:.6em">
                                  </div>
                                </div>

                              </div>

                              <div class='container2 t-wrap full-slug'><!--  findit -->

                                <div>
                                  <span>
                                      Unique url &nbsp;
                                      <i class="fas fas fas fa-link font-size-17"
                                         style="color:#579AE5;"></i>
                                      <a href="javascript:void(0);"
                                         :data-id="`${data.item.alumni_name}`"
                                         class="px-2" v-b-tooltip.hover title="Delete">
                                        <input v-if="data.item.unique_url!==null"
                                               v-on:focus="$event.target.select()"
                                               ref="myinput"
                                               id="copy_url_btn"
                                               readonly
                                               :value="`${formatUrl(data.item.unique_url)}`"/>
                                        <i v-on:focus="$event.target.select()"
                                           ref="myinput"
                                           @click="copyToClipboard(data.item.unique_url)"
                                           class="uil uil-copy-alt font-size-17"></i>
                                      </a>
                                  </span>
                                </div>

                                <div>
                                  <span>
                                      Profile url &nbsp;
                                      <i class="fas fas fas fa-link font-size-17"
                                         style="color:#579AE5;"></i>
                                      <a href="javascript:void(0);"
                                         :data-id="`${data.item.alumni_name}`"
                                         class="px-2" v-b-tooltip.hover title="Delete">
                                       <small>{{ data.item.personal_profile_url }}</small>
                                      </a>
                                  </span>
                                </div>

                                <div style='margin-left:60px;'>
                                  <div style="font-size:.6em">
                                  </div>
                                </div>

                              </div>

                            </div>
                          </template>

                          <template v-slot:cell(actions)="data">

                                            <span class="`confirm-bx-${data.item.id}` actions-edt-del-in0"
                                                  v-if="alumniconfirm_check==data.item.id">
                                              <span class="px-2 text-primary" v-b-tooltip.hover title="Cancel delete"
                                                    @click="alumnidelete_cancel()">
                                                <span class="active-st-1"><i class="uil uil-check font-size-18"></i> Cancel </span>
                                              </span></br></br>

                                              <span class="px-2 text-primary" :data-delete="`${data.item.name}`"
                                                    v-b-tooltip.hover title="Confirm delete"
                                                    @click="delete_this_alumni(data.item.id)">
                                                <span class="active-st-0"> <i
                                                  class="uil uil-trash-alt font-size-17"></i>
                                                  confirm
                                                  <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;"
                                                             v-if="alumniloadingData_del"></b-spinner>
                                                </span>
                                              </span>

                                            </span>

                            <ul class="list-inline mb-0 move-right">
                              <li class="list-inline-item">
                                <a href="javascript:void(0);" :data-id="`${data.item.id}`" class="px-2 text-primary"
                                   @click="getEditAlumni(data.item.id,data.item.current_position_name)"
                                   :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Edit">
                                  <i class="uil uil-pen font-size-18"></i>
                                </a></br>
                              </li>

                              <li class="list-inline-item ">
                                <a href="javascript:void(0);" :data-id="`${data.item.name}`" class="px-2"
                                   @click=" alumnipop_confirm(data.item.id)"
                                   :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                  <i class="uil uil-trash-alt font-size-18"></i>
                                </a>
                                </br>
                              </li>

                            </ul>

                          </template>

                        </b-table>

                      </div>
                      </br></br>

                      <div class="row">
                        <div class="col">
                          <div class="dataTables_paginate paging_simple_numbers float-right">
                            <ul class="pagination pagination-rounded">
                              <!-- pagination -->
                              <b-pagination @input="alumnihandlePageChange" v-model="alumnicurrentPage"
                                            :total-rows="alumnirows"
                                            :per-page="alumniperPage"></b-pagination>
                            </ul>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                </b-col>
                <!--
                 ########### end left column ######## -->

              </b-row>
            </b-container>

          </div>

          <!-- tab 4 ... -->
          <div id="highlights" class="tabcontent">

            <b-container class="bv-example-row">
              <b-row>
                <b-col sm="auto" class="form_sec0">
                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <a v-if="editingHighlight" class="bck-to-0 move-right" href="javascript:void(0);"
                             @click="clearHighlightForm" role="button" aria-expanded="false">
                            New Highlight
                          </a></br>
                          <h4 class="card-title">{{ highlight_form_title }} for | {{ formData.institution_name }}</h4>
                          <p class="card-title-desc">
                          </p>
                          <div class="row">
                            <div class="col-12">
                              <form v-on:submit.prevent="submitHighlightForm" class="form-horizontal" role="form">
                                <label class="pd-hld">Key highlight</label>
                                <input v-model="highlights_form.key_highlight" type="text"
                                       class="form-control"
                                       placeholder="Name" name="key_highlight"/>
                                <label class="pd-hld">Description</label>
                                <div class="ckeditor_intab_container">
                                  <ckeditor v-model="highlights_form.key_highlight_description"
                                            :editor="editor"></ckeditor>
                                </div>
                                <label class="pd-hld">Display order</label>
                                <input v-model="highlights_form.display_order" type="number"
                                       class="form-control"
                                       placeholder="Enter number" name="display_order"/>
                                <label class="pd-hld"></br></label>
                                <button type="submit" class="btn btn-primary w-md" id="submit_highlight">
                                  Submit
                                  <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;"
                                             v-if="loadingData"></b-spinner>
                                </button>
                                </br>
                              </form>
                              </br>
                              <div class="alert alert-danger" v-if="highlightFormSubmittedError">
                                <p>{{ highlightFormSubmittedErrorText }}</p>
                              </div>
                              <div class="alert alert-success" v-if="highlightcreateRecordSuccess">
                                <p>{{ highlightcreateRecordmessage }}</p>
                              </div>

                            </div>
                          </div>
                          <!-- end row -->
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

                <b-col sm="auto" class="list_sec1">

                  <div class="row">
                    <div class="col-12">
                      <!-- Table -->
                      <h4 class="card-title">Highlights list</h4>

                      <div class="row mt-4">
                        <div class="col-sm-12 col-md-6"></div>

                        <!-- Search -->
                        <div class="col-sm-12 col-md-6">
                          <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                            <label class="d-inline-flex align-items-center">
                              Search:
                              <b-form-input v-model="filterhighlight" type="search" placeholder="Search..."
                                            class="form-control form-control-sm ml-2"></b-form-input>
                            </label>
                          </div>
                        </div>
                        <!-- End search -->

                      </div>

                      <div class="table-responsive mb-0">
                        <b-table
                          table-class="table table-centered datatable table-card-list"
                          thead-tr-class="bg-transparent"
                          :items="highlights_list"
                          :fields="highlight_fields"
                          responsive="sm"
                          :filter="filterhighlight"
                          :filter-included-fields="highlightfilterOn" @filtered="highlightonFiltered">
                          :sort-by.sync="sortBy"
                          :sort-desc.sync="sortDesc"
                          >

                          <template v-slot:cell(name)="data">

                            <div class="name-cont-1">

                              <div class='container2 t-wrap'>
                                                  <span class="`confirm-bx-${data.item.id}` actions-edt-del-in0"
                                                        v-if="highlightconfirm_check==data.item.id">
                                                    <span class="px-2 text-primary" @click="highlightdelete_cancel()">
                                                      <span class="active-st-1"><i
                                                        class="uil uil-check font-size-18"></i> Cancel </span>
                                                    </span></br></br>
                                                    <span class="px-2 text-primary" :data-delete="`${data.item.name}`"
                                                          @click="delete_this_highlight(data.item.id)">
                                                      <span class="active-st-0"> <i
                                                        class="uil uil-trash-alt font-size-17"></i>
                                                        confirm
                                                        <b-spinner label="Loading..."
                                                                   style="width: 0.7rem; height: 0.7rem;"
                                                                   v-if="highlightloadingData_del"></b-spinner>
                                                      </span>
                                                    </span>
                                                  </span>

                                <ul class="list-inline mb-0 move-right">
                                  <li class="list-inline-item">
                                    <a href="javascript:void(0);" :data-id="`${data.item.id}`" class="px-2 text-primary"
                                       @click="getEditHighlight(data.item.id,data.item.current_position_name)"
                                       :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Edit">
                                      <i class="uil uil-pen font-size-18"></i>
                                    </a></br>
                                  </li>

                                  <li class="list-inline-item ">
                                    <a href="javascript:void(0);" :data-id="`${data.item.name}`" class="px-2"
                                       @click="highlightpop_confirm(data.item.id)"
                                       :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                      <i class="uil uil-trash-alt font-size-18"></i>
                                    </a></br>
                                  </li>
                                </ul>

                                <div class='container2 t-wrap'>
                                  <nuxt-link :to="{path:'#' }" class="px-2 text-primary" v-b-tooltip.hover
                                             title="Open to show">
                                    <div></div>
                                    <div style='margin-left:10px;'>
                                      <i class="fas fas fas fas fa-star"></i>{{ data.item.key_highlight }}
                                      <div style="font-size:.6em">
                                      </div>
                                    </div>
                                    <div style='margin-left:10px;'>
                                      <div style="font-size:.6em">
                                      </div>
                                    </div>
                                  </nuxt-link>
                                </div>

                              </div>
                            </div>

                          </template>

                        </b-table>

                      </div>
                      </br></br>


                      <div class="row">
                        <div class="col">
                          <div class="dataTables_paginate paging_simple_numbers float-right">
                            <ul class="pagination pagination-rounded">
                              <!-- pagination -->
                              <b-pagination @input="highlighthandlePageChange" v-model="highlightcurrentPage"
                                            :total-rows="highlightrows"
                                            :per-page="highlightperPage"></b-pagination>
                            </ul>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                </b-col>
                <!--
                 ########### end left column ######## -->

              </b-row>
            </b-container>

          </div>

          <!-- tab 5 ... -->
          <div id="blogt" class="tabcontent">

            <b-container class="bv-example-row">
              <div class="select_container_0">
                <form v-on:submit.prevent="submitAlumniForm" class="form-horizontal" role="form">
                  <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-right crect">
                    {{ alumnibutton_text }}
                    <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;" v-if="loadingData"></b-spinner>
                  </button>
                  <div class="select_container_row_0">
                    <multiselect v-model="blog_form.blog_category" :id="blog_categories" :options="jobtitle_list"
                                 :multiple="false" placeholder="Select Employer" label="name" track-by="name"
                                 :preselect-first="false">
                    </multiselect>
                  </div>
                </form>
              </div>
              <hr>
              <b-row>

                <b-col sm="auto">

                  <div class="row">
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <a v-if="editingAlumni" class="bck-to-0 move-right" href="javascript:void(0);"
                             @click="clearAlumniForm" role="button" aria-expanded="false">
                            New Alumni
                          </a>
                          </br>
                          <h4 class="card-title">{{ alumni_form_title }} for | {{ formData.institution_name }}</h4>
                          <p class="card-title-desc">
                          </p>
                          <div class="row">
                            <div class="col-12"></div>
                          </div>
                          <!-- end row -->
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

                <b-col sm="auto" class="list_sec1">

                  <div class="row">
                    <div class="col-12">
                      <!-- Table -->
                      <h4 class="card-title">Alumni list</h4>
                      <div class="row mt-4">
                        <div class="col-sm-12 col-md-6"></div>
                      </div>
                      <div class="table-responsive mb-0">
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

          <!-- tab 6 ... -->
          <div id="website" class="tabcontent">

            <b-container class="bv-example-row">
              <div class="select_container_0">

              </div>
              <hr>
              <div v-if="institutionNameDetect(formData.institution_name)">
                <!-- <embed src="https://www.virginia.edu/" style="width:100%; height: 500px;"> -->
              </div>
            </b-container>

          </div>
          <!-- tab 7 ... -->
          <div id="Virtual-tour" class="tabcontent">

            <b-container class="bv-example-row">
              <div class="select_container_0">

              </div>
              <hr>
              <div>
                <embed src="https://www.youvisit.com/tour/auburn/142509?fromSearch=1&wph=1&skipPrompt=1&tourid=tour1"
                       style="width:100%; height: 500px;">
              </div>
            </b-container>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";
import AccreditationsService from "~/helpers/services/AccreditationsService";
import AlumniService from "~/helpers/services/AlumniService";
import BlogService from "~/helpers/services/BlogService";
import HighlightsService from "~/helpers/services/HighlightsService";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

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
    Multiselect,
    DatePicker,
    ckeditor: CKEditor.component
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
      loadingData: false,
      sortBy: "id",
      sortDesc: false,
      filter: "",
      countries: [],
      institution_types: [],
      code_param: "",
      ownership_types: [],
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
      accreditation_form_title: "New Accreditation",
      accredbutton_text: " Submit",
      accreditations_list: [],
      editingAccreditation: false,
      accreditationconfirm_check: false,
      accreditationloadingData_del: false,
      acrreditation_small_organization_image: "",
      accreditation_id_beingedited: "",
      accreditation_form: {
        organization_name: '',
        accreditation_description: "",
        organization_acronym: "",
        organization_image: '',
      },
      accreditation_fields: [
        {
          key: "Name",
          label: 'Name',
          sortable: true
        },

      ],
      accreditationcurrentPage: 1,
      accreditationperPage: 10,
      accreditationtotalRows: 1,
      accreditationpageOptions: [10, 25, 50, 100],
      filteraccreditation: null,
      accreditationfilterOn: [],

      alumni_form_title: "New Alumni",
      alumnibutton_text: " Submit",
      alumni_list: [],
      jobtitle_list: [],
      editingAlumni: false,
      alumniconfirm_check: false,
      alumniloadingData_del: false,
      alumni_small_alumnus_image_path: "",
      disciplinesList: [],
      alumni_id_beingedited: "",
      alumni_form: {
        alumni_name: '',
        graduation_year: "",
        course_taken:null,
        current_employer: '',
        current_position: "",
        personal_profile_url: "",
        alumnus_image: '',
        current_position_text: "",
        institution_alumni_id: "",
        institution_code: "",
        email:"",
        reviews: "",
        tncs_consent: true,
        profile_content_consent: true,
      },
      alumni_fields: [
        {
          key: "Name",
          label: 'Name',
          sortable: true
        },
        {
          key: "actions",
          label: '',
          sortable: true
        },

      ],

      alumnicurrentPage: 1,
      alumniperPage: 10,
      alumnitotalRows: 1,
      alumnipageOptions: [10, 25, 50, 100],
      filteralumni: null,
      alumnifilterOn: [],

      highlight_form_title: "New Highlight",
      highlightbutton_text: " Submit",
      highlights_list: [],
      editingHighlight: false,
      highlightconfirm_check: false,
      highlightloadingData_del: false,
      highlight_id_beingedited: "",
      highlights_form: {
        key_highlight: '',
        key_highlight_description: "",
        display_order: "",
        organization_image: '',
      },
      highlight_fields: [
        {
          key: "Name",
          label: 'Name',
          sortable: true
        },

      ],
      highlightcurrentPage: 1,
      highlightperPage: 10,
      highlighttotalRows: 1,
      highlightpageOptions: [10, 25, 50, 100],
      filterhighlight: null,
      highlightfilterOn: [],

      blogCategory_list: [],
      blog_categories: [],
      blog_form: {
        blog_category: '',
      },

      accreditationFormSubmitted: false,
      accreditationFormSubmittedError: false,
      accreditationFormSubmittedErrorText: "",
      updateaccreditationLogoFile: null,
      accreditationcreateRecordSuccess: false,
      accreditationcreateRecordmessage: "succesfully created",

      alumniFormSubmitted: false,
      alumniFormSubmittedError: false,
      alumniFormSubmittedErrorText: "",
      updatealumniLogoFile: null,
      alumnicreateRecordSuccess: false,
      alumnicreateRecordmessage: "succesfully created",

      highlightFormSubmitted: false,
      highlightFormSubmittedError: false,
      highlightFormSubmittedErrorText: "",
      updatehighlightLogoFile: null,
      highlightcreateRecordSuccess: false,
      highlightcreateRecordmessage: "succesfully created",

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
        profile_details: {
          required
        },
        institution_type: {
          required
        },
        ownership_type: {
          required
        },
        country_code: {
          required,
          maxLength: maxLength(2),
        },
        city: {},
        institution_logo: {
          required: requiredIf(function () {
            if (this.formData.logo_url || this.editAction) {
              return false
            }
            return true
          })
        },
        website_url: {},
        inquiry_form_url: {},
        date_registered: {}
      }, {
        email_address: {
          email
        },
        university_postal_address: {},
        phone_number: {},
      },
        {
          academic_office_phone_number: {},
          academic_office_email_address: {},
          academic_office_postal_address: {}
        },
        {
          finance_office_email_address: {},
          finance_office_phone_number: {}
        },
        {
          global_ranking: {},
          continental_ranking: {},
          regional_ranking: {},
          country_ranking: {},
          accredited_by: {},
          accredited_by_acronym: {},
          accreditation_body_url: {
            url
          }
        },
        {
          main_campus_latitude: {},
          main_campus_longtitude: {},
          main_campus_physical_location: {},
          seo_description: {},
          seo_keywords: {}
        },
      ],
      institutionFormSubmitted: false,
      institutionFormSubmittedError: false,
      institutionFormSubmittedErrorText: "",
      editAction: false,
      institutionCode: null,
      updateLogoFile: null,
    };
  },
  computed: {

    accreditationdrows() {
      return this.accreditationtotalRows;
    },
    alumnirows() {
      return this.alumnitotalRows;
    },
    highlightrows() {
      return this.highlighttotalRows;
    },
  },
  created() {
    this.rollbar_init();
    //get the form builder
    this.getFormBuilder()
    this.buildAlumni();

    this.getAccreditations();
    this.getAlumniList();
    this.getHighlightList();
    this.getAlumniInstitutionsList();
    this.getAcademicDisciplinesList();
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

    this.code_param = this.$route.query.code;


  },
  methods: {
    rollbar_init() {
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
    formatUrl(url){
      url.replace("online","com")
      console.log(url)
      return  url.replace("online","com")
    },
    institutionNameDetect(name) {
      if (name === "Shorelight - Auburn University at Montgomery") {
        return true;
      }
    },
    getAlumniInstitutionsList() {
      InstitutionsService.getAlumniInstitutions().then(response => {
        let data = response.data.data;
        //console.log(data);

        if (data.status) {
          //console.log(data);
          //redirect to the listing page
        } else {

        }
      })
    },
    getAcademicDisciplinesList() {
      InstitutionsService.getAcademicDisciplines().then(response => {
        let data = response.data.data;
        if (response.status) {
          this.disciplinesList = data.academic_disciplines;
          //redirect to the listing page
        } else {

        }
      })
    },
    copyToClipboard(data) {
      //this.$refs.myinput.focus();
      //document.execCommand('copy');
      /* Get the text field */
      var copyText = document.getElementById("copy_url_btn");

      /* Select the text field */
      copyText.select();
      copyText.setSelectionRange(0, 99999); /* For mobile devices */

      /* Copy the text inside the text field */
      navigator.clipboard.writeText(copyText.value);

      /* Alert the copied text */
    },
//########## INSTITUTION TAB FUNCTIONALITY ################################################
//
//
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
        let badArr = ['system_internal_ranking', 'has_updates',
          'indexing_error', 'approved_at',
          'approved_by', 'is_picked_for_indexing',
          'indexing_object_id', 'logo_cdn_upload_error',
          'time_picked_for_indexing', 'temp_log_path', 'time_taken_to_index', 'type', 'deleted_by']
        if (!badArr.includes(key)) {
          if (this.formData[key] && this.formData[key] !== 'null') {
            institutionData.append(key, this.formData[key]);
          }
        }
      }
      //append the logo on edit
      if (this.editAction) {
        institutionData.append('institution_logo', this.updateLogoFile);

        InstitutionsService.updateInstitution(institutionData, this.institutionCode).then(response => {
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
    /*
    */
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
    },
    tabs_control(evt, el_name) {
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

//
//
//########## INSTITUTION TAB FUNCTIONALITY ################################################

//########## ACCREDITATIONS TAB FUNCTIONALITY ##############################################
//
//
    getAccreditations() {
      let self = this;
      AccreditationsService.getAccreditationsList(this.$route.query.code).then(response => {
        let accreditation_list_data = response.data;
        if (accreditation_list_data.status) {
          self.accreditations_list = accreditation_list_data.data.items;
        } else {
          self.accreditationFormSubmittedError = true
          self.accreditationFormSubmittedErrorText = accreditation_list_data.message
        }
      })
    },
    clearAccreditationForm() {
      this.editingAccreditation = false;
      this.accreditation_form_title = "New Accreditation"
      this.accredbutton_text = " Submit";

      this.accreditation_form.organization_name = "";
      this.accreditation_form.accreditation_description = "";
      this.accreditation_form.organization_acronym = "";
      this.accreditation_form.organization_image = "";
      this.acrreditation_small_organization_image = "";
      this.$refs.organization_image = "";
    },
    submitAccreditationForm() {

      // Form Post Service post form data
      this.loadingData = true;
      var setData_accreditation = new FormData();
      let self = this;
      //get the form data
      for (let key in this.accreditation_form) {
        if (this.accreditation_form[key] && this.accreditation_form[key] !== 'null') {
          setData_accreditation.append(key, this.accreditation_form[key]);
        }
      }

      //append the logo on edit
      this.accredbutton_text = " Submit";
      if (this.editingAccreditation) {
        this.accreditationcreateRecordmessage = "Succesfully Updated";
        let accr_post = AccreditationsService.updateaccreditation(this.$route.query.code, this.accreditation_id_beingedited, setData_accreditation).then(response => {
          let accreditation_edata = response.data;
          if (accreditation_edata.status) {
            //redirect to page
            self.getAccreditations();
            self.accreditation_form.organization_name = "";
            self.accreditation_form.accreditation_description = "";
            self.accreditation_form.organization_acronym = "";
            self.accreditation_form.organization_image = "";

            this.loadingData = false;
            self.accreditationcreateRecordSuccess = true;
            self.accreditationFormSubmittedError = false;

            //timout the success message
            setTimeout(function (scope) {
              scope.accreditationcreateRecordSuccess = false;
            }, 3000, this);
          } else {
            self.accreditationFormSubmittedError = true;
            self.accreditationFormSubmittedErrorText = accreditation_edata.message;
            self.loadingData = false;
            self.accreditationcreateRecordSuccess = false
          }
        });

      } else {
        this.accreditationcreateRecordmessage = "Succesfully created";
        let accr_post = AccreditationsService.addaccreditations(this.$route.query.code, setData_accreditation).then(response => {
          let accreditation_edata = response.data;
          if (accreditation_edata.status) {

            //redirect to page
            self.getAccreditations();

            self.accreditation_form.organization_name = "";
            self.accreditation_form.accreditation_description = "";
            self.accreditation_form.organization_acronym = "";
            self.accreditation_form.organization_image = "";

            this.loadingData = false;
            self.accreditationcreateRecordSuccess = true;
            self.accreditationFormSubmittedError = false;

            //timout the success message
            setTimeout(function (scope) {
              scope.accreditationcreateRecordSuccess = false;
            }, 3000, this);
          } else {
            self.accreditationFormSubmittedError = true;
            self.accreditationFormSubmittedErrorText = accreditation_edata.message;
            self.loadingData = false;
            self.accreditationcreateRecordSuccess = false
          }
        });
      }

    },
    handleLogoAccreditationUpload() {
      //check if we are updating or just creating
      this.accreditation_form.organization_image = this.$refs.organization_image.files[0];
    },
    getEditAccreditation(itemid) {
      this.editingAccreditation = true;
      this.accreditation_form_title = "Update Accreditation"
      this.accredbutton_text = " Update";
      this.accreditation_id_beingedited = itemid;
      let self = this;
      let getAccreditationToEdit = AccreditationsService.getAccreditation(this.$route.query.code, itemid).then(response => {
        var response_data = response.data
        self.accreditation_form.organization_name = response_data.data.accreditation.organization_name;
        self.accreditation_form.accreditation_description = response_data.data.accreditation.accreditation_description;
        self.accreditation_form.organization_acronym = response_data.data.accreditation.organization_acronym;
        self.accreditation_form.organization_image = response_data.data.accreditation.organization_image;
        self.acrreditation_small_organization_image = response_data.data.accreditation.small_organization_image;
      })
    },
    accreditationhandlePageChange(value) {
      //get the data
      this.getacrreditationData(value)
    },
    getacrreditationData(page) {
      let setaccreditationFilterData = new FormData();
      for (let key in this.accreditationfilterData) {
        setaccreditationFilterData.append(key, this.accreditationfilterData[key] != null ? this.accreditationfilterData[key] : "");
      }
      this.loadingData = true
      let self = this

      AccreditationsService.getAccreditationsListPage(this.$route.query.code, page).then(response => {
        let accreditation_list_data = response.data;
        if (accreditation_list_data.status) {
          self.accreditations_list = accreditation_list_data.data.items;
          self.accreditationtotalRows = accreditation_list_data.data.items_count;
          var perpage_convert = parseInt(accreditation_list_data.data.items_per_page);
          self.accreditationperPage = perpage_convert;
          self.accreditationcurrentPage = accreditation_list_data.data.current_page;
          self.loadingData = false

        } else {
          self.accreditationFormSubmittedError = true
          self.accreditationFormSubmittedErrorText = accreditation_list_data.message
        }
      })
    },
    delete_this_accreditation(accreditation_id) {
      this.accreditationloadingData_del = true;
      let self = this;
      AccreditationsService.deleteAccreditation(this.$route.query.code, accreditation_id).then(response => {
        let deleted_accreditation_data = response.data;
        if (deleted_accreditation_data.status) {
          //timout the success message
          setTimeout(function (scope) {
            scope.accreditationloadingData_del = false;
          }, 2000, this);

          self.getacrreditationData(self.accreditationcurrentPage)
        } else {
          self.accreditationFormSubmittedError = true
          self.accreditationFormSubmittedErrorText = deleted_accreditation_data.message
        }
      })
    },
    accreditationpop_confirm(id) {
      this.accreditationconfirm_check = id;
    },
    accreditationdelete_cancel() {
      this.accreditationconfirm_check = false;
    },
    accreditationonFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.accreditationtotalRows = filteredItems.length;
      this.accreditationcurrentPage = 1;
    },

//
//
//########## ACCREDITATION TAB FUNCTIONALITY #########################################


//########## ALUMNI TAB FUNCTIONALITY #################################################
//
//
    submitAlumniForm() {
      var ensure_name_is_string = this.alumni_form.alumni_name.toString();
      this.alumni_form.alumni_name = ensure_name_is_string;
      // Form Post Service post form data
      this.loadingData = true;

      var setData_alumni = new FormData();
      let self = this;

      //get the form data
      for (let key in this.alumni_form) {

        if (this.alumni_form[key] && this.alumni_form[key] !== 'null') {
          setData_alumni.append(key, this.alumni_form[key]);
        }
      }

      this.button_text = " Submit";

      if (this.editingAlumni) {
        if(self.alumni_form.email==null){
          self.alumni_form = "";
        }
        let accr_post = AlumniService.updateAlumniProfile(this.alumni_form.institution_alumni_id, setData_alumni).then(response => {
          console.log(self.alumni_form.course_taken)
          let alumni_edata = response.data;
          if (alumni_edata.status) {

            //redirect to page
            self.getAlumniList();
            self.alumni_form.institution_alumni_id = null;
            self.alumni_form.alumni_name = "";
            self.alumni_form.graduation_year = "";
            self.alumni_form.course_taken = 0;
            self.alumni_form.current_employer = "";
            self.alumni_form.email="";

            self.alumni_form.current_position = "";
            self.alumni_form.personal_profile_url = "";
            self.alumni_form.alumnus_image = "";

            this.loadingData = false;
            self.alumnicreateRecordSuccess = true;
            self.alumniFormSubmittedError = false;
            self.alumnicreateRecordmessage = "succesfully Updated"

            //timout the success message
            setTimeout(function (scope) {
              scope.alumnicreateRecordSuccess = false;
            }, 3000, this);

          } else {
            self.alumniFormSubmittedError = true;
            self.alumniFormSubmittedErrorText = alumni_edata.message;
            self.loadingData = false;
            self.alumnicreateRecordSuccess = false
          }
        });

      } else {

        self.alumnicreateRecordmessage = "succesfully Created"

        let accr_post = AlumniService.addAlumni(this.$route.query.code, setData_alumni).then(response => {

          let alumni_edata = response.data;
          if (alumni_edata.status) {
            //redirect to page
            self.getAlumniList();
            self.alumni_form.alumni_name = "";
            self.alumni_form.graduation_year = "";
            self.alumni_form.course_taken = null;
            self.alumni_form.current_employer = "";

            self.alumni_form.current_position = "";
            self.alumni_form.personal_profile_url = "";
            self.alumni_form.alumnus_image = "";

            this.loadingData = false;
            self.alumnicreateRecordSuccess = true;
            self.alumniFormSubmittedError = false;

            //timout the success message
            setTimeout(function (scope) {
              scope.alumnicreateRecordSuccess = false;
            }, 3000, this);

          } else {
            self.alumniFormSubmittedError = true;
            self.alumniFormSubmittedErrorText = alumni_edata.message;
            self.loadingData = false;

            self.alumnicreateRecordSuccess = false

          }

        });

      }

    },
    handleLogoAlumniUpload() {
      this.alumni_form.alumnus_image = this.$refs.alumnus_image.files[0];
    },
    buildAlumni() {
      let self = this;
      let alumnibuildRequest = AlumniService.buildAlumni(this.$route.query.code).then(response => {
        var alumni_response = response.data;
        if (alumni_response.status) {
          self.jobtitle_list = alumni_response.data.job_titles;
        }
      })
    },
     async getAlumniList() {
      let self = this;
      await AlumniService.getAlumniList(this.$route.query.code).then(response => {
        var alumnilist_response = response.data;
        if (alumnilist_response.status) {
          self.alumni_list = alumnilist_response.data.items;
          self.alumnitotalRows = alumnilist_response.data.items_count;
          var perpage_convert = parseInt(alumnilist_response.data.items_per_page);
          self.alumniperPage = perpage_convert;
          self.alumnicurrentPage = alumnilist_response.data.current_page;
          self.loadingData = false
        }
      })
    },
    alumnihandlePageChange(value) {
      //get the data
      this.getalumniData(value)
    },
    getalumniData(page) {
      let setalumniFilterData = new FormData();
      for (let key in this.alumnifilterData) {
        setalumniFilterData.append(key, this.alumnifilterData[key] != null ? this.alumnifilterData[key] : "");
      }
      this.loadingData = true
      let self = this
      AlumniService.getAlumniListPage(this.$route.query.code, page).then(response => {
        let alumni_list_data = response.data;
        console.log(alumni_list_data);
        if (alumni_list_data.status) {
          self.alumni_list = alumni_list_data.data.items;
          self.alumnitotalRows = alumni_list_data.data.items_count;
          var perpage_convert = parseInt(alumni_list_data.data.items_per_page);
          self.alumniperPage = perpage_convert;
          self.alumnicurrentPage = alumni_list_data.data.current_page;
          self.loadingData = false
        } else {
          self.alumniFormSubmittedError = true
          self.alumniFormSubmittedErrorText = alumni_list_data.message
        }
      })
    },
    clearAlumniForm() {
      this.editingAlumni = false;
      this.alumni_form_title = "New Alumni"
      this.alumnibutton_text = " Submit";

      this.alumni_form.alumni_name = "";
      this.alumni_form.graduation_year = "";
      this.alumni_form.course_taken = null;
      this.alumni_form.current_employer = "";
      this.alumni_form.current_position = "";
      this.alumni_form.current_position_text = " Select current Position";
      this.alumni_form.personal_profile_url = "";
      this.alumni_form.alumnus_image = "";
      this.alumni_small_organization_image = "";

    },
    getEditAlumni(itemid, current_position_name) {

      this.editingAlumni = true;
      this.alumni_form_title = "Update Alumni"
      this.alumnibutton_text = " Update";
      this.alumni_id_beingedited = itemid;
      let self = this;

      let getAlumniToEdit = AlumniService.getAlumniDetail(this.$route.query.code, itemid).then(response => {
        var response_data = response.data
        console.log(response_data)
        self.alumni_form.institution_alumni_id=response_data.data.alumnus.id;
        self.alumni_form.alumni_name = response_data.data.alumnus.alumni_name;
        self.alumni_form.graduation_year = response_data.data.alumnus.graduation_year;
        //self.alumni_form.course_taken = response_data.data.alumnus.course_taken;
        self.alumni_form.course_taken=this.setCurrentCourseTaken(response_data.data.alumnus.course_taken);
        self.alumni_form.current_employer = response_data.data.alumnus.current_employer;
        self.alumni_form.current_position = response_data.data.alumnus.current_position;
        self.alumni_form.current_position_text = current_position_name;
        self.alumni_form.email = response_data.data.alumnus.email;
        self.alumni_form.personal_profile_url = response_data.data.alumnus.personal_profile_url;
        self.alumni_form.alumnus_image = response_data.data.alumnus.alumnus_image;
        self.alumni_small_alumnus_image_path = response_data.data.alumnus.small_alumnus_image_path;

      })
    },
    setCurrentCourseTaken(item){
      let list_discipline_item_index =this.disciplinesList.findIndex(discipline => discipline.discipline_name === item)
      console.log(list_discipline_item_index)
      if(list_discipline_item_index!==-1){
        return this.disciplinesList[list_discipline_item_index].id
      }else{
        return 0;
      }

    },
    delete_this_alumni(alumni_id) {

      let self = this;
      AlumniService.deleteAlumni(this.$route.query.code, alumni_id).then(response => {

        this.alumniloadingData_del = true;
        let deleted_alumni_data = response.data;
        if (deleted_alumni_data.status) {
          //timout the success message
          setTimeout(function (scope) {
            scope.alumniloadingData_del = false;
          }, 2000, this);

          self.getacrreditationData(self.alumnicurrentPage)

        } else {
          self.alumniFormSubmittedError = true
          self.alumniFormSubmittedErrorText = deleted_alumni_data.message
        }

      })

    },
    alumnipop_confirm(id) {
      this.alumniconfirm_check = id;
    },
    alumnidelete_cancel() {
      this.alumniconfirm_check = false;
    },
    alumnionFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      console.log("alumnionFiltered");
      this.alumnitotalRows = filteredItems.length;
      this.alumnicurrentPage = 1;
    },

//
//
//########## ALUMNI TAB FUNCTIONALITY ##########################################################


//########## HIGHLIGHT TAB FUNCTIONALITY #######################################################
//
//
    submitHighlightForm() {

      // Form Post Service post form data
      this.loadingData = true;

      var setData_highlight = new FormData();
      let self = this;

      //get the form data
      for (let key in this.highlights_form) {

        if (this.highlights_form[key] && this.highlights_form[key] !== 'null') {
          setData_highlight.append(key, this.highlights_form[key]);
        }
      }

      this.button_text = " Submit";

      if (this.editingHighlight) {

        let highlight_post = HighlightsService.updateHighlight(this.$route.query.code, self.highlight_id_beingedited, setData_highlight).then(response => {

          let highlight_edata = response.data;
          if (highlight_edata.status) {

            //referesh the item list
            self.getHighlightList();

            self.highlights_form.key_highlight = "";
            self.highlights_form.key_highlight_description = "";
            self.highlights_form.display_order = "";
            self.highlights_form.organization_image = "";

            this.loadingData = false;
            self.highlightcreateRecordSuccess = true;
            self.highlightFormSubmittedError = false;
            self.highlightcreateRecordmessage = "succesfully Updated"

            //timout the success message
            setTimeout(function (scope) {
              scope.highlightcreateRecordSuccess = false;
            }, 3000, this);

          } else {
            self.highlightFormSubmittedError = true;
            self.highlightFormSubmittedErrorText = highlight_edata.message;
            self.loadingData = false;

            self.highlightcreateRecordSuccess = false

          }


        });

      } else {

        let highlight_post = HighlightsService.addHighlight(this.$route.query.code, setData_highlight).then(response => {

          let highlight_edata = response.data;
          if (highlight_edata.status) {

            //referesh the item list
            self.getHighlightList();

            self.highlights_form.key_highlight = "";
            self.highlights_form.key_highlight_description = "";
            self.highlights_form.display_order = "";
            self.highlights_form.organization_image = "";

            this.loadingData = false;
            self.highlightcreateRecordSuccess = true;
            self.highlightFormSubmittedError = false;

            //timout the success message
            setTimeout(function (scope) {
              scope.highlightcreateRecordSuccess = false;
            }, 3000, this);

          } else {
            self.highlightFormSubmittedError = true;
            self.highlightFormSubmittedErrorText = highlight_edata.message;
            self.loadingData = false;

            self.highlightcreateRecordSuccess = false

          }

        });
      }

    },
    handleLogoHighlightUpload() {

      //check if we are updating or just creating
      this.highlights_form.organization_image = this.$refs.organization_image.files[0];

    },
    getHighlightList() {

      let self = this;

      let highlightListRequest = HighlightsService.getHighlightiList(this.$route.query.code).then(response => {
        var highlightlist_response = response.data;
        if (highlightlist_response.status) {

          self.highlights_list = highlightlist_response.data.items;

        }

      })
    },
    highlighthandlePageChange(value) {
      //get the data
      this.gethighlightData(value)
    },
    gethighlightData(page) {

      let sethighlightFilterData = new FormData();
      for (let key in this.highlightfilterData) {
        sethighlightFilterData.append(key, this.highlightfilterData[key] != null ? this.highlightfilterData[key] : "");
      }
      this.loadingData = true

      let self = this

      HighlightsService.getHighlightiListPage(this.$route.query.code, page, sethighlightFilterData).then(response => {

        let highlight_list_data = response.data;
        if (highlight_list_data.status) {
          self.highlights_list = highlight_list_data.data.items;
          self.highlighttotalRows = highlight_list_data.data.items_count;
          var perpage_convert = parseInt(highlight_list_data.data.items_per_page);
          self.highlightperPage = perpage_convert;
          self.highlightcurrentPage = highlight_list_data.data.current_page;
          self.loadingData = false

        } else {
          self.highlightFormSubmittedError = true
          self.highlightFormSubmittedErrorText = highlight_list_data.message
        }

      })

    },
    clearHighlightForm() {

      this.editingHighlight = false;
      this.highlight_form_title = "New Highlight"
      this.highlightbutton_text = " Submit";

      this.highlights_form.key_highlight = "";
      this.highlights_form.key_highlight_description = "";
      this.highlights_form.display_order = "";

    },
    getEditHighlight(itemid, current_position_name) {

      this.editingHighlight = true;
      this.highlight_form_title = "Update Highlight"
      this.highlightbutton_text = " Update";
      this.highlight_id_beingedited = itemid;
      let self = this;

      let getAlumniToEdit = HighlightsService.getHighlightDetail(this.$route.query.code, itemid).then(response => {
        var response_data = response.data

        self.highlights_form.key_highlight = response_data.data.highlight.key_highlight;
        self.highlights_form.key_highlight_description = response_data.data.highlight.key_highlight_description;
        self.highlights_form.display_order = response_data.data.highlight.display_order;

      })
    },
    delete_this_highlight(alumni_id) {

      this.highlightloadingData_del = true;
      let self = this;
      HighlightsService.deleteHighlight(this.$route.query.code, alumni_id).then(response => {

        let deleted_highlight_data = response.data;
        if (deleted_highlight_data.status) {
          //timout the success message
          setTimeout(function (scope) {
            scope.highlightloadingData_del = false;
          }, 2000, this);

          self.gethighlightData(self.highlightcurrentPage)

        } else {
          self.highlightFormSubmittedError = true
          self.highlightFormSubmittedErrorText = deleted_highlight_data.message
        }

      })

    },
    highlightpop_confirm(id) {
      this.highlightconfirm_check = id;
    },
    highlightdelete_cancel() {
      this.highlightconfirm_check = false;
    },
    highlightonFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.highlighttotalRows = filteredItems.length;
      this.highlightcurrentPage = 1;
    },

//
//
//########## HIGHLIGHT TAB FUNCTIONALITY ####################################################
//
//


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

.iconDetails {
  margin-left: 2%;
  float: left;
  height: 40px;
  width: 40px;
}

.container2 {
  width: 80%;
}

.move-right {
  margin-top: 1em;
}

.crect {
  margin-top: 0em;
}

.move-right a {
  color: black;
}

.move-right li a i {
  color: black;
}

.form_sec0 {
  max-width: 62%;
  min-width: 62%;
}

.list_sec1 {
  max-width: 38%;
  min-width: 38%;
}

.list_sec0 {
  max-width: 53%;
  min-width: 53%;
}

.list_sec0a {
  width: 45%;
}

.select_container_0 {
  max-width: 80%;
  min-width: 80%;
}

.select_container_row_0 {
  max-width: 80%;
  min-width: 80%;
}


.ckeditor_intab_container {
  max-width: 100%;
}

.name-cont-1 {
  width: 100%;
}

.full-slug {
  width: 100%;
}

.full-slug a {
  display: flex;
  gap: 5px;
  padding: 0 !important;
  margin-bottom: 10px;
}

.full-slug input {
  width: 100%;
}

</style>


<script>
/**
 * Basic table component
 */

import EligibilitiesService from "~/helpers/services/EligibilitiesService";
import GlobalService from "~/helpers/GlobalService";
import axios from 'axios';

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

import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
    head() {
        return {
          title: `${this.title} | Admin Dashboard`
        };
    },
    components: {
        FormWizard,
        TabContent,
        vSelect,
        ckeditor:CKEditor.component
    },
    data() {
        return {
            editor: ClassicEditor,
            title: "New Eligibility",
            countries: [],
            pathways: [],
            specializations: [],
            eligibility_types: [],
            academic_categories: [],
            eligibility_image:"",
            selected_country:"",
            loadingData: false,
            button_text: " Submit",
            editAction: false,
            countries: [],
            items: [{
                        text: "Eligibilites"
                    },
                    {
                        text: "Eligibilites",
                        active: true
                    }
            ],
            academic_categories: [],
            specFormSubmitted: false,
            specFormSubmittedError: false,
            specFormSubmittedErrorText: "",
            form_for: {
                name: '',
                country_id: "",
                eligibility_type_id: "",
                pathway_id: "",
                specialization_id: "",
                academic_categories: [],
                eligibility_image: '',
            },
            validationRules: [{
                name: {
                  required,
                },
                country_id: {
                  required,
                },
                pathway_id:{
                  required
                },
                specialization_id: {
                  required
                },
                academic_categories: {
                  required
                },
                eligibility_type: {
                  required
                }

            }],

        };

    },
    middleware: "authentication",
    created() {
      this.getFormBuilder();

        var id_val = this.$route.query.id

        if (id_val) {
          //set the title and edit actions
          this.editAction = true
          var id_val = this.$route.query.id
          this.button_text = " Update";

          //get the institution for editing
          this.getelgToEdit(id_val);

        } else {
          this.editAction = false
        }  


    },
    methods: {
        submitForm(e) {

             var setEligibilityData = new FormData();
             this.loadingData = true;
             this.button_text = "";

             let self = this
              //get the form data
              for (let key in this.form_for) {
                if(this.form_for[key] && this.form_for[key] !== 'null'){
                  setEligibilityData.append(key, this.form_for[key]);
                }
              }

              //append the logo on edit
              if (this.editAction) {
 
                  this.button_text = " Update";

                  // append upload to form data
                  setEligibilityData.append('this.eligibility_image', this.updateLogoFile);
                  var id_eligibility_edit = this.$route.query.id;

                  // form data post service request
                  EligibilitiesService.updateEligibilty(id_eligibility_edit,setEligibilityData).then(response => {

                    let data = response.data;
                    if (data.status) {
                      //redirect to the listing page
                         var path_id = this.$route.query.pid;

                          if (path_id){
                             GlobalService.redirect_to_pathway(path_id);
                          }else{
                           self.$router.push('/pathways/eligibilities/eligibilities');
                          }

                    } else {
                      self.specFormSubmittedError = true;
                      self.specFormSubmittedErrorText = data.message;
                      self.loadingData = false;

                    }

                  })


              } else {


                  // form data post service request 
                  EligibilitiesService.addEligibility(setEligibilityData).then(response => {

                    let data = response.data;
                    if (data.status) {
                      //redirect to the listing page

                       var path_id = this.$route.query.pid;

                        if (path_id){
                           GlobalService.redirect_to_pathway(path_id)
                        }else{
                         self.$router.push('/pathways/eligibilities/eligibilities');
                        }

                    } else {
                      self.specFormSubmittedError = true
                      self.specFormSubmittedErrorText = data.message
                      self.loadingData = false;

                    }
                  })


              }
        },    
        handleLogoUpload() {
         //check if we are updating or just creating
          if (this.editAction) {
            this.updateLogoFile = this.$refs.eligibility_image.files[0];
          } else {
            this.form_for.eligibility_image = this.$refs.eligibility_image.files[0];
          }
        },
        getPathways() {
           
           let self = this;
           PathwaysService.getPatwayData().then(response => {
              let data = response.data
              if (data.status) {
                self.countries = data.data.countries
              }
           })
        },
        // method to get record data to be edited form api
        getelgToEdit(id) {
            let self_in = this;

            EligibilitiesService.getEligibilty(id).then(response => {


              let elgb_data = response.data.data;

              if (response.data.status) {

                let i_data = elgb_data[0]; 

                self_in.form_for.name  = i_data.name;
                self_in.form_for.country_id = i_data.country_id;
                self_in.form_for.eligibility_type_id  = i_data.eligibility_type_id;
                self_in.form_for.pathway_id  = i_data.pathway_id;
                self_in.form_for.specialization_id = i_data.specialization_id;
                self_in.form_for.academic_categories  = i_data.academic_categories;
                self_in.form_for.eligibility_image  = i_data.eligibility_image;

              }

            });
        },
        getFormBuilder() {
          
          let self = this;
          EligibilitiesService.buildEligibilty().then(response => {
            let eligb_response_data = response.data
            if (eligb_response_data.status) {
              self.countries = eligb_response_data.data.countries
              self.pathways = eligb_response_data.data.pathways
              self.specializations = eligb_response_data.data.specialization
              self.eligibility_types = eligb_response_data.data.eligibility_type
              self.academic_categories = eligb_response_data.data.academic_categories
            }
          })

        },



    }, 

};
</script>

<template>
<div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <router-link to="/pathways/eligibilities/eligibilities">
                        <span class="bck-to-0"><i class="uil uil-arrow-left font-size-20"></i> back</span>
                    </router-link> 
                    
                    <h4 class="card-title" >{{title}}</h4>
                    <p class="card-title-desc">
                        <!-- Here are examples of -->
                    </p>

                    <div class="row">
                        <div class="col-12">

                            <form v-on:submit.prevent="submitForm" class="form-horizontal " role="form">

                                <label >Name</label>
                                <input v-model="form_for.name" type="text"
                                     class="form-control"
                                     placeholder="Name" name="Name"/>

                                <div class="row">

                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >Country </label>

                                          <b-form-select
                                            v-model="form_for.country_id"
                                            :options="countries"
                                            class="mb-3 select_e1"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field="notEnabled"
                                            >
                                          </b-form-select>
                                    
                                    </div>
                                  </div>

                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >Pathway</label>

                                          <b-form-select
                                            v-model="form_for.pathway_id"
                                            :options="pathways"
                                            class="mb-3 select_e2"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field="notEnabled"
                                            placeholder="select pathway"
                                            >
                                          </b-form-select>
                                    
                                    </div>
                                  </div>

                                </div>

                                <div class="row">

                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >Eligibility type</label>

                                          <b-form-select
                                            v-model="form_for.eligibility_type_id"
                                            :options="eligibility_types"
                                            class="mb-3 select_e3"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field="notEnabled"
                                            placeholder="select Eligibility"
                                            >
                                          </b-form-select>
                                    
                                    </div>
                                  </div>


                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >Specialization</label>

                                          <b-form-select
                                            v-model="form_for.specialization_id"
                                            :options="specializations"
                                            class="mb-3 select_e4"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field="notEnabled"
                                            placeholder="select Specialization"
                                            >
                                          </b-form-select>
                                    
                                    </div>
                                  </div>

                                 

                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >Academic categories</label>

                                          <b-form-select
                                            v-model="form_for.academic_categories"
                                            :options="academic_categories"
                                            class="mb-3 select_e5"
                                            value-field="discipline_code"
                                            text-field="discipline_name"
                                            disabled-field="notEnabled"
                                            placeholder="select pathway"
                                            >
                                          </b-form-select>
                                    
                                    </div>
                                  </div>

                                </div>


                                <div class="row">
                                 
                                  <div class="col-md-6">
                                      <div class="form-group position-relative">
                                        <label class="pd-hld" >Eligibility Image</label>
                                        <input ref="eligibility_image" v-on:change="handleLogoUpload()" id="file_e1" type="file" class="form-control"
                                               placeholder="eligibility_image" name="form_for.eligibility_image"/>
                                        </div>

                                        <div class="col-md-12 mb-3" v-if="this.editAction">
                                          <img class="rounded mr-2" :alt="`${form_for.name} logo`"
                                               width="200" :src="this.eligibility_image" />
                                        </div>

                                        <div class="col-md-12 mb-3" v-if="form_for.logo">
                                          <img class="rounded mr-2" :alt="`new ${form_for.name} logo`"
                                               width="200" :src="form_for.eligibility_image"
                                               data-holder-rendered="true" />
                                        </div>

                                    </div>


                                </div>




                                <button type="submit" class="btn btn-primary w-md"  >
                                    {{button_text}}
                                      <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;" v-if="loadingData"></b-spinner>
                                </button>

                            </form>
                            </br>
                            <div class="alert alert-danger" v-if="specFormSubmittedError">
                              <p>{{ specFormSubmittedErrorText }}</p>
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


</div>
</template>

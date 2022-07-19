



<script>
/**
 * Basic table component
 */

import TheEmployersService from "~/helpers/services/TheEmployersService";
import GlobalService from "~/helpers/GlobalService";

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

import vSelect from 'vue-select';
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
            title: "Add Employer",
            employer_types: [],
            loadingData: false,
            countries:[],
            pathways: [],
            editAction: false,
            old_logo: "",
            employer_id: "",
            button_text: " Submit",
            loadingData: false,
            error_alert: false,
            employerFormSubmitted: false,
            employerFormSubmittedError: false,
            employerFormSubmittedErrorText: "",
            form: {
                name: '',
                description: '',
                employer_type_id: '',
                keywords: '',
            },
            validationRules: [{
                name: {
                  required,
                },
                description: {
                  required,
                },
                keywords:{
                  required
                }
            }],

        };

    },
    middleware: "authentication",
    created() {
       this.getempFormBuilder();
    },
    created(){

          let employer_id_present = this.$route.query.id;

          //get the form builder
          this.getempFormBuilder(); 

          //check if it is an edit action
          if (employer_id_present) {
            //set the title and edit actions
            this.editAction = true;
            //get the institution for editing
            this.getEmployerToedit(employer_id_present);

          } else {
            this.editAction = false;
          }


    },
    methods: {
        empsubmitForm() {

             var setData = new FormData();

             this.loadingData = true;
             this.button_text = "";
              //get the form data
              for (let key in this.form) {

                  if(this.form[key] && this.form[key] !== 'null'){
                    setData.append(key, this.form[key]);
                  }
               }
              //append the logo on edit
               if (this.editAction) {

                    setData.append('institution_logo', this.updateLogoFile);
                    this.button_text = "Update"
                    var id_val_edit = this.$route.query.id

                    let employer_edit = TheEmployersService.updateEmployers(id_val_edit,setData).then(response => {
  
                        let data = response.data;
                        if (data.status) {
                            //redirect to the listing page
                             var path_id = this.$route.query.pid;

                              if (path_id){
                                 GlobalService.redirect_to_pathway(path_id)
                              }else{
                                 this.$router.push('/pathways/employers/employers');
                              }

                        } else {

                            this.employerFormSubmittedError = true
                            this.error_alert = true
                            this.employerFormSubmittedErrorText = data.message
                            this.loadingData = false;
                        }
                    });

                } else {

                    this.button_text = " Submit"
                    let employer_response = TheEmployersService.addEemployers(setData).then(response => {
 
                        let data = response.data;
                        if (data.status) {
                           //redirect to the listing page

                             var path_id = this.$route.query.pid;

                              if (path_id){
                                 GlobalService.redirect_to_pathway(path_id)
                              }else{                              
                                this.$router.push('/pathways/employers/employers');
                              } 

                        } else {

                            this.employerFormSubmittedError = true
                            this.error_alert = true
                            this.employerFormSubmittedErrorText = data.message
                            this.loadingData = false;

                        }
                    });
    
                }

        },    
        getPathways() {
           let self = this;
           PathwaysService.getPathways().then(response => {
              let data = response.data;
              if (data.status) {
                self.pathways = data.data.items;

              }
           });
        },
        handleLogoUpload() {

             //check if we are updating or just creating
             if (this.editAction) {
                this.updateLogoFile = this.$refs.employer_image.files[0];
             } else {
                this.form.employer_image = this.$refs.employer_image.files[0];
             }

        },
        getEmployerToedit(id) {

            let self = this;
            this.button_text = "Update";

            TheEmployersService.getemployerdetails(id).then(response => {
              let response_data = response.data;
              this.title = "Edit employer";

              if (response_data.status) {
                this.old_logo = response_data.data.logo;
                self.form = response_data.data;
                self.form.employer_type_id = response_data.data.type.id

              }
           
            });

        },
        getempFormBuilder() {

          let self = this;
          TheEmployersService.buildEmployer().then(response => {
            let data = response.data;
            if (data.status) {
              self.countries = data.data.countries;
              self.employer_types = data.data.employer_types;
            }
          });

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
                    <router-link to="/pathways/employers/employers">
                        <span class="bck-to-0"><i class="uil uil-arrow-left font-size-20"></i> back</span>
                    </router-link>

                    <h4 class="card-title" >{{title}}</h4>
                    <p class="card-title-desc">
                        <!-- Here are examples of -->
                    </p>

                    <div class="row">
                        <div class="col-12">
                            <form v-on:submit.prevent="empsubmitForm" class="form-horizontal" role="form">
                                  <label class="pd-hld" >Name</label>
                                  <input v-model="form.name" type="text"
                                     class="form-control"
                                     placeholder="Name" name="Name"/>

                                  <label class="pd-hld" >Description</label>
                                  <ckeditor v-model="form.description" :editor="editor"></ckeditor>

                                 
                                <div class="col-md-6">

                                    <div class="form-group position-relative">
                                      <label class="pd-hld" >Employer image</label>
                                      <input ref="employer_image" v-on:change="handleLogoUpload()" type="file" id="file_e1" class=" form-control"
                                             placeholder="Logo" name="form.employer_image"/>
                                      </div>

                                      <div class="col-md-12 mb-3" v-if="this.editAction">
                                        <img class="rounded mr-2" :alt="`${form.name} logo`"
                                             width="200" :src="this.old_logo" />
                                      </div>

                                      <div class="col-md-12 mb-3" v-if="form.employer_image">
                                        <img class="rounded mr-2" :alt="`new ${form.employer_image} logo`"
                                             width="200" :src="form.employer_image"
                                             data-holder-rendered="true" />
                                      </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group position-relative">

                                          <label class="pd-hld" >Country</label>
                                          <b-form-select
                                            v-model="form.country_id"
                                            :options="countries"
                                            class="mb-3 select_e1"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field=""
                                            placeholder="select Country"
                                            >
                                          </b-form-select>

                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                      <div class="form-group position-relative">
                                        <label class="pd-hld" >Employer type</label>

                                          <b-form-select
                                            v-model="form.employer_type_id"
                                            :options="employer_types"
                                            class="mb-3 select_e2"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field="notEnabled"
                                            placeholder="Employer type"
                                            >
                                            <b-form-select-option :value="form.employer_type_id">Select Employer Type</b-form-select-option>

                                          </b-form-select>
                                      </div>
                                    </div>

                                 </div>   

                                <button type="submit" class="btn btn-primary w-md">
                                    {{this.button_text}}
                                    <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;" v-if="loadingData"></b-spinner>
                                </button>

                            </form>
                            </br>
                            <div class="alert alert-danger" v-if="employerFormSubmittedError">
                              <p>{{ employerFormSubmittedErrorText }}</p>
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


    <!-- end row -->
</div>
</template>

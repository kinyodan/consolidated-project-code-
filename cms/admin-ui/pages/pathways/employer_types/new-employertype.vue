



<script>
/**
 * Basic table component
 */

import EmployertypeService from "~/helpers/services/EmployertypeService";
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

import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import '@progress/kendo-ui';
import { TabStrip } from '@progress/kendo-layout-vue-wrapper';
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";


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
        Multiselect,
        ckeditor:CKEditor.component
    },
    data() {
        return {
            editor: ClassicEditor,
            title: "Add Employer Type",
            employer_type_id: "",
            employer_pname: "",
            button_text: " Submit",
            loadingData: false,
            error_alert: false,
            specFormSubmitted: false,
            specFormSubmittedError: false,
            specFormSubmittedErrorText: "",
            keywords_raw_object:[],
            options:[],
            form_field_data: {
                name: '',
                country_id: '',
                description: '',
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

        //check if it is an edit action
        var id_val = this.$route.query.id

        if (id_val) {
          //set the title and edit actions
          this.editAction = true
          var id_val = this.$route.query.id

          //get the institution for editing
          this.getToEdit(id_val);

        } else {
          this.editAction = false
        }   

    },
    mounted: function () {
      this.$nextTick(function () {
        // Code that will run only after the
        // entire view has been rendered
      })
    },
    methods: {
            submitForm(e) {
            
             this.stringify_keywords(this.keywords_raw_object);

             this.loadingData = true;
             this.button_text = "";

             //testing global form post refactoring method 
             // FormPostService.post_form()
               var setData = new FormData();
               let self = this;

                //get the form data
                for (let key in this.form_field_data) {

                  if(this.form_field_data[key] && this.form_field_data[key] !== 'null'){
                    setData.append(key, this.form_field_data[key]);
                  }
                }

               //append the logo on edit
               if (this.editAction) {
                     var id_val_edit = this.$route.query.id
                     this.button_text = "Update"

                      let emp_post_edit = EmployertypeService.updateEmployertypes(id_val_edit,setData).then(response => {
                      let data = response.data;

                      if (data.status) {
                        //redirect to the listing page
                        self.$router.push( "/pathways/employer_types/employer-types");

                      } else {
                        self.specFormSubmittedError = true
                        self.error_alert = true
                        self.specFormSubmittedErrorText = data.message
                        self.loadingData = false;

                      }
                    })


                } else {

                    let emp_post = EmployertypeService.addEmployertypes(setData).then(response => {
                      let data = response.data;

                      if (data.status) {
                        //redirect to page
                        self.$router.push( "/pathways/employer_types/employer-types");
                      } else {
                        self.specFormSubmittedError = true
                        self.error_alert = true
                        self.specFormSubmittedErrorText = data.message
                        self.loadingData = false;
                      }

                    })

                }

            },  
            getToEdit(id) {
                let self = this;
                this.button_text = "Update"
                EmployertypeService.getEmployertype(id).then(response => {
                  let response_data = response.data
                  this.title = "Edit Employer type"

                  if (response_data.status) {
                    self.form_field_data = response_data.data;
                    self.options = JSON.parse(response_data.data.keywords);
                    self.keywords_raw_object=JSON.parse(response_data.data.keywords);

                  }
                });
            },
            SelectName_em (option) {
              return `${option.keyword}`
            },
            onSelect_em (option, uid) {
            },
            addTag_em (newTag) {
              const parts = newTag.split(', ');
              
              const tag = {
                uid: this.options.length + 1,
                keyword: parts.pop()
              }
              this.options.push(tag)
              this.keywords_raw_object.push(tag);
            },
            stringify_keywords(keywords_array){

              this.form_field_data.keywords=JSON.stringify(keywords_array)
              return this.form_field_data.keywords;

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

                    <router-link to="/pathways/employer_types/employer-types">
                        <span class="bck-to-0"><i class="uil uil-arrow-left font-size-20"></i> back</span>
                    </router-link> 

                    <h4 class="card-title" >{{title}}</h4>
                    <p class="card-title-desc">
                        <!-- Here are examples of -->
                    </p>

                    <div class="row">
                        <div class="col-12">
                            <form v-on:submit.prevent="submitForm" class="form-horizontal" role="form">

                                <label  class="pd-hld">Name</label>
                                <input v-model="form_field_data.name" type="text"
                                     class="form-control"
                                     placeholder="Name" name="name"/>

                                <label  class="pd-hld" >Description</label>
                                <ckeditor v-model="form_field_data.description" :editor="editor"></ckeditor>

                                    <label class="pd-hld" >keywords </label>
                                    <multiselect v-model="keywords_raw_object" 
                                      :options="options" 
                                       placeholder="Type keywords" 
                                      :custom-label="SelectName_em" 
                                       label="uid" track-by="uid" 
                                      :close-on-select="true" 
                                      :clear-on-select="false" 
                                      :hide-selected="true" 
                                      :preserve-search="true" 
                                      class="helo" 
                                      id="example" 
                                      @select="onSelect_em" 
                                      :taggable="true" 
                                      :multiple="true" 
                                      @tag="addTag_em"
                                    >
                                    </multiselect>

                                <label class="pd-hld" ></label>

                                <button type="submit" class="btn btn-primary w-md">
                                    {{this.button_text}}
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

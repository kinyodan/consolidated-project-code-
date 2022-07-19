
<script>
/**
 * Basic table component
 */
import SpecializationsService from "~/helpers/services/SpecializationsService";
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";
import PathwaysService from "~/helpers/services/PathwaysService";



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

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

export default {
    head() {
        return {
            title: "New pathways",
            items: [{
                    text: "Pathways"
                },
                {
                    text: "New pathways",
                    active: true,
                    root_url: "/pathways/pathways/pathways-list"
                }
            ]

        };
    },
    components: {
        FormWizard,
        TabContent,
        vSelect,
        DatePicker,
        Multiselect,
        ckeditor:CKEditor.component
    },
    data() {
        return {
            editor: ClassicEditor,
            countries: [],
            editAction: false,
            button_text: " Submit",
            loadingData: false,
            keywords_raw_object:[],
            specFormSubmitted: false,
            specFormSubmittedError: false,
            specFormSubmittedErrorText: "",
            options:[],
            pathway_form: {
                name: '',
                description: "",
                logo: '',
                keywords:'',
                specializations: 1,

            },
            validationRules: [{
                name: {
                  required,
                },
                description: {
                  required,
                },
                pathway_id:{
                  required,
                },
                country_id: {
                  required,
                }
            }],
        };

    },
    created() {

        //get the form builder
          this.get_pathway_FormBuilder();

          var pathway_id_val = this.$route.query.id;

          if (pathway_id_val) {
            //set the title and edit actions
            this.editAction = true;

            //get the institution for editing
            this.getpathwayToEdit(pathway_id_val);

          } else {
            this.editAction = false;
          } 

    },
    middleware: "authentication",
    methods: {
           submitForm(){

                this.stringify_keywords(this.keywords_raw_object);

                // Form Post Service post form data
                this.loadingData = true;

                var setData_pway = new FormData();
                let self = this;

                //get the form data
                for (let key in this.pathway_form) {

                  if(this.pathway_form[key] && this.pathway_form[key] !== 'null'){
                    setData_pway.append(key, this.pathway_form[key]);
                  }
                }

                //append the logo on edit
                if (this.editAction) {
        
                     self.title = "Edit Pathway";

                     var id_val_edit = this.$route.query.eid;
                     this.button_text = "Update";

                    let emp_post_edit = PathwaysService.updatePathway(id_val_edit,setData_pway).then(response => {
                      let pathway_edata = response.data;

                          if (pathway_edata.status) {
                            //redirect to the listing page

                            self.$router.push( "/pathways/pathways/pathways-list");

                          } else {
                            self.specFormSubmittedError = true;
                            self.error_alert = true;
                            self.specFormSubmittedErrorText = data.message;
                            self.loadingData = false;

                          }

                    });

                } else {

                    this.button_text = " Submit";

                    let emp_post = PathwaysService.addPathway(setData_pway).then(response => {
                      let data = response.data;
                      if (data.status) {

                        //redirect to page
                        self.$router.push( "/pathways/pathways/pathways-list");

                      } else {
                        self.specFormSubmittedError = true;
                        self.error_alert = true;
                        self.specFormSubmittedErrorText = data.message;
                        self.loadingData = false;

                      }

                    });

                }

        }, 
        handleLogoUpload() {
        
             //check if we are updating or just creating
             if (this.editAction) {
                this.updateLogoFile = this.$refs.pathway_image.files[0];
             } else {
                this.pathway_form.pathway_image = this.$refs.pathway_image.files[0];
             }

        },           
        get_pathway_FormBuilder() {

              //let self = this;
              PathwaysService.buildPathways().then(response => {
                let data_pathway = response.data;
                if (data_pathway.status) {

                }
              });

        },
        getpathwayToEdit(id) {

            let self = this;
            this.button_text = "Update";

            PathwaysService.getPathwayData(id).then(response => {
              let response_data = response.data;
              this.title = "Edit pathway";
              if (response_data.status) {
                self.pathway_form = response_data.data[0];
                self.keywords_raw_object = JSON.parse(response_data.data[0].keywords);
              }

            });

        },
        SelectName (option) {
            return `${option.keyword}`
        },
        onSelect (option, uid) {
              //console.log(option, uid)
        },
        addTag (newTag) {
            
          const parts = newTag.split(', ');
          const tag = {
            uid: this.options.length + 1,
            keyword: parts.pop()
          }
          this.options.push(tag)
          this.keywords_raw_object.push(tag);

        },
        stringify_keywords(keywords_array){

          this.pathway_form.keywords=JSON.stringify(keywords_array)
          return this.pathway_form.keywords;

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

                    <router-link to="/pathways/pathways/pathways-list">
                        <span class="bck-to-0"><i class="uil uil-arrow-left font-size-20"></i> back</span>
                    </router-link> 

                    <h4 class="card-title">New Pathway </h4>
                    <p class="card-title-desc">

                    </p>

                    <div class="row">
                        <div class="col-12">
                            <form v-on:submit.prevent="submitForm" class="form-horizontal" role="form">

                                <input type="hidden" name="pathway_form.specializations" class="form-control" v-model="pathway_form.specializations">

                                <label  class="pd-hld">Name</label>
                                <input v-model="pathway_form.name" type="text"
                                     class="form-control"
                                     placeholder="Name" name="pathway_form.name"/>

                                <label class="pd-hld" >Introduction</label>
                                <ckeditor v-model="pathway_form.description" :editor="editor"></ckeditor>


                                    <label class="pd-hld" >keywords</label>
                                    <multiselect v-model="keywords_raw_object" 
                                      :options="options" 
                                       placeholder="Type keywords" 
                                      :custom-label="SelectName" 
                                       label="uid" track-by="uid" 
                                      :close-on-select="true" 
                                      :clear-on-select="false" 
                                      :hide-selected="true" 
                                      :preserve-search="true" 
                                      class="helo" 
                                      id="example" 
                                      @select="onSelect" 
                                      :taggable="true" 
                                      :multiple="true" 
                                      @tag="addTag">

                                    </multiselect>

                                <div class="row">
                                
                                  <div class="col-md-6">

                                    <div class="col-md-6">
                                      <div class="form-group position-relative">
                                        <label class="pd-hld" >Pathway Image</label>
                                        <input ref="pathway_image" v-on:change="handleLogoUpload()" id="file_e1" type="file" class="form-control"
                                               placeholder="Logo" name="pathway_form.logo"/>
                                        </div>


                                        <div class="col-md-12 mb-3" v-if="pathway_form.logo">
                                          <img class="rounded mr-2" :alt="`new ${pathway_form.logo} logo`"
                                               width="200" :src="pathway_form.logo"
                                               data-holder-rendered="true" />
                                        </div>

                                    </div>

                                  </div>

                                  <div class="col-md-6">

                                  </div>

                                </div>


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


<script>
/**
 * Basic table component
 */
import SpecializationsService from "~/helpers/services/SpecializationsService";
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";
import PathwaysService from "@/helpers/services/PathwaysService";
import TheEmployersService from "~/helpers/services/TheEmployersService";
import Treeselect from '@riophae/vue-treeselect';
import '@riophae/vue-treeselect/dist/vue-treeselect.css';
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
import '@progress/kendo-ui';
import { TabStrip } from '@progress/kendo-layout-vue-wrapper';
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";


export default {
    head() {
        return {
            title: "New specialization",
            items: [{
                    text: "Tables"
                },
                {
                    text: "New pathways",
                    active: true,
                    root_url: "/pathways/specializations/specializations"
                }
            ]
        };
    },
    components: {
        FormWizard,
        TabContent,
        vSelect,
        Multiselect,
        ckeditor:CKEditor.component,
        'kendo-tabstrip': TabStrip,
        Treeselect ,
    },
    data() {
        return {
            submitted: false,
            editor: ClassicEditor,
            countries: [],
            employer_countries:[],
            academic_disciplines: [],
            loadingData: false,
            loadingData_next: false,
            loadingData_done:false,
            button_text: " Submit",
            multiselect_data: [],
            set_pathway_value: [{id:0,name:"select pathway"}],
            populated:false,
            editAction: false,
            tab: "subscribe",
            proceed: false,
            pathways_list: [],
            employers_empty: false,
            employers_link_list: [],
            employers: [],
            add_employers: false,
            selected_employers: [],
            preselected_employers: [],
            employer_filter_selected:[],
            current_global_employer_list:[],
            i_exist: false,
            selected_list_items: [],
            keywords_raw_object:[],
            setemployers: [],
            old_logo: "",
            source_check: 0,
            selected_linked_academic_discipline: [],
            selected_country: "",
            selected: '',
            selected_path: '',
            specFormSubmitted: false,
            specFormSubmittedError: false,
            specFormSubmittedErrorText: "",
            specializationform_field_data: { 
                name: "",
                description: "",
                keywords:[],
                linked_academic_discipline: "Select academic disciplines ",
                pathway_id: "",
                linked_employers:[],
                logo: '',
            },
            validationRules: [{
                name: {
                  required,
                },
                description: {
                  required,
                },
                keywords:{
                  required,
                },
                country_id: {
                  required,
                }
            }],
            options: [
            ]

        };

    },
    created() {
      
      this.getFormBuilder();
      this.get_pathways();
      
      let specialization_id_present = this.$route.query.id;
      if (specialization_id_present){
       this.source_check = 1;
      }

      //check if it is an edit action
      if (specialization_id_present) {
        //set the title and edit actions
        this.editAction = true;
        //get the institution for editing
        this.getSpecilizationToedit(specialization_id_present);

      } else {
        this.editAction = false;
      }

      this.multiselect_data_builder();


    },
    middleware: "authentication",
    methods: {
          submitForm(){
             let self = this;

             this.stringify_keywords(this.keywords_raw_object);
             this.stringify_disciplines(this.selected_linked_academic_discipline);

             var specializationData = new FormData();
             this.loadingData = true;
             this.button_text = "";

                //get the form data
                for (let key in this.specializationform_field_data) {

                  if(this.specializationform_field_data[key] && this.specializationform_field_data[key] !== 'null'){
                    specializationData.append(key, this.specializationform_field_data[key]);
                  }

                }
                //append the logo on edit
                if (this.editAction) {
     
                      specializationData.append('this.specialization_image', this.updateLogoFile);
                      var id_specval_edit = this.$route.query.id;
                      this.button_text = "Update";
        
                      SpecializationsService.updateSpecialization(id_specval_edit,specializationData).then(response => {

                          let data = response.data;
                          if (data.status) {
                       
                              var path_id = this.$route.query.pid;
                              if (path_id){
                                 GlobalService.redirect_to_pathway(path_id)
                              }else{
                               this.$router.push('/pathways/specializations/specializations');
                              }

                          } else {
                            self.specFormSubmittedError = true;
                            self.error_alert = true;
                            self.specFormSubmittedErrorText = data.message;
                            self.loadingData = false;

                          }

                      });

                      this.button_text = " Update";


                } else {

                      let self = this;
                      //post to create specialization api path
                      SpecializationsService.addSpecialization(specializationData).then(response => {
                        let data = response.data;
                        if (data.status) {

                            //redirect to the listing page
                             var path_id = this.$route.query.pid;

                              if (path_id){
                                 GlobalService.redirect_to_pathway(path_id)
                              }else{
                               self.$router.push('/pathways/specializations/specializations');
                              }

                        } else {
                          self.specFormSubmittedError = true;
                          self.specFormSubmittedErrorText = data.message;
                          self.loadingData = false;

                        }

                        this.button_text = " Submit";

                      });
                }
        },
        stringify_keywords(keywords_array){

          this.specializationform_field_data.keywords=JSON.stringify(keywords_array)
          return this.specializationform_field_data.keywords;

        }, 
        stringify_disciplines(disciplines_array){

          let disciplines_codes = [];

          disciplines_array.forEach(function (dsc) {

            disciplines_codes.push(dsc.discipline_code);

          })

          this.specializationform_field_data.linked_academic_discipline=disciplines_codes;
          return this.specializationform_field_data.linked_academic_discipline;

        },
        handleLogoUpload() {
  
           //check if we are updating or just creating
           if (this.editAction) {
              this.updateLogoFile = this.$refs.specialization_image.files[0];
           } else {
              this.specializationform_field_data.specialization_image = this.$refs.specialization_image.files[0];
           }

        },
        get_add_employers(country_id){

            this.proceed = true;
            this.add_employers = true;

            let self = this; 
            this.loadingData_next= true;

            SpecializationsService.getemployers(country_id).then(response => {


                if (response.data.data.items.length === 0){

                }else{
                   self.employers.push(response.data.data.items);
                }

                self.totalRows = response.data.data.items_count;
                var perpage_convert =parseInt(response.data.data.items_per_page);
                self.perPage = perpage_convert;
                self.currentPage = response.data.data.current_page;
                self.loadingData_next = true;

            })

            this.loadingData_next= true;

            //start loading
            this.loadingData_next = false;


        },
        getFormBuilder() {

                var option_item = [];
                var list_of_countries = [];
                var select_contries = [];

                let self = this;
                SpecializationsService.buildSpecialization().then(response => {

                  let data = response.data;
                  if (data.status) {

                        self.countries = data.data.countries;
                        self.employer_countries = data.data.countries;
                        self.academic_disciplines = data.data.academic_disciplines;

                        self.countries.forEach(function (country, index) {
                           
                            self.get_add_employers(country.id);

                            var select_contry = [];

                            var emp_prnt = {
                                          id: "{"+country.id+","+"all}",
                                          label: country.name,
                                          children: [],

                                      };

                            var id_setter = self.countries.length

                            let employers_filtered = self.employers.filter(function(employer) {
                                id_setter = id_setter+1

                                if (country.id === employer.country_id){
                                    var emp_rcd = 
                                          {
                                          id: "{id:"+employer.id+",name:"+employer.name+"}",
                                          label: employer.name,
                                          }

                                     emp_prnt.children.push(emp_rcd)

                                }

                            });

                            var empty_note = "country has no added employers"

                            if (emp_prnt.children.length === 0){
                              return empty_note
                            }else{  
                               self.multiselect_data.push(emp_prnt);
                            }
                              
                      });


                  }

              });
        },
        getSpecilizationToedit(id) {
            let self = this;
            this.button_text = "Update";

            SpecializationsService.getSpecialization(id).then(response => {
              let response_data = response.data;
              this.title = "Edit Specialization";

              if (response_data.status) {
                this.old_logo = response_data.data.logo;
                self.specializationform_field_data = response_data.data;
                self.preselected_employers = response_data.data.employers;
                self.specializationform_field_data.employers = response_data.data.employers;

                // avoid null values that cause cannot find value of undefined error
                if(response_data.data.linked_employers === null){
                self.specializationform_field_data.linked_employers = [];

                }else{  
                self.specializationform_field_data.linked_employers = response_data.data.employers;
                }

                if(response_data.data.name === null){
                self.specializationform_field_data.name = "";

                }else{  
                self.specializationform_field_data.name = response_data.data.name;
                }

                if(response_data.data.description === null){
                self.specializationform_field_data.description = "";

                }else{  
                self.specializationform_field_data.description = response_data.data.description;
                }

                if(response_data.data.keywords === null){
                self.specializationform_field_data.keywords = "";

                }else{  

                    self.specializationform_field_data.keywords = JSON.parse(response_data.data.keywords);
                    self.keywords_raw_object =response_data.data.keywords;

                }

                if(response_data.data.linked_academic_discipline === null){
                  self.specializationform_field_data.linked_academic_discipline = [{discipline_code:0,discipline_name:"select and academic discipline"}];

                }else{  

                  self.specializationform_field_data.linked_academic_discipline = response_data.data.disciplines; 
                }

                if(response_data.data.logo === null){
                self.specializationform_field_data.logo = "";

                }else{  
                self.specializationform_field_data.logo = response_data.data.logo;
                }


                if(response_data.data.pathway_id === null){
                  self.specializationform_field_data.pathway_id = "";

                }else{ 

                  self.specializationform_field_data.pathway_id = response_data.data.pathway_id;
                }


                if(response_data.data.pathway_id === null){
                  self.specializationform_field_data.linked_academic_discipline = []


                }else{  
                
                   self.specializationform_field_data.linked_academic_discipline = response_data.data.disciplines


                }

              }


              
            });
        },
        get_pathways() {

            let self = this;
            SpecializationsService.buildSpecializationPathwaysList().then(response => {
              let pathwayresponse_data = response.data;
              if (pathwayresponse_data.status) {
                self.pathways_list = pathwayresponse_data.data.pathways;

              }
            });
        },
        select_check(id){
          this.employers_ids.includes(id);
        },
        multiselect_data_builder(){

          var select_contries = [];
          var option_item = [];
          var list_of_countries = [];

          this.countries.forEach((x, i) => select_contries.push(x.id,x.name));
         
        },
        check_country_employers(country){

          if (country.children.length === 0){
           self.employers_empty = true;

          }else{  
           self.employers_empty = false;
          }

        },
        characterItemClick (characterIndex) {
          
          const characterInfoElement = document.querySelectorAll('[data-character-id="' + characterIndex + '"]')[0]
          if (characterInfoElement.classList.contains('block')) {
            characterInfoElement.classList.remove('block')
            characterInfoElement.classList.add('hidden')
          } else {
            characterInfoElement.classList.remove('hidden')
            characterInfoElement.classList.add('block')
          }

        },
        setcountryemployers(country_id){
              this.selected_employers=[];
              this.setemployers=[];

              let self = this
              var select_employer_list = []
        
              this.employers.forEach(function (employer, index) {

                  let employers_list = employer.filter(function(emp) {

                      if (country_id === emp.country_id){
                          var emp_rcd = emp

                           if (self.setemployers.includes(emp.name)){

                           }else{

                               self.setemployers.push(emp_rcd)

                           }

                      }

                  });

              });

              this.populated = true;

        },
        done_selecting_employers(current_selection){

          this.loadingData_done = true;
          this.prepare_employer_ids(current_selection)
          Array.prototype.push.apply(this.specializationform_field_data.linked_employers,this.employers_link_list);

        },
        prepare_employer_ids(employers_list){
            let self = this
            
            employers_list.forEach(function (employer) {
              self.employers_link_list.push(employer.id);

            });

            return this.employers_link_list;       

        },
        if_exist(employer){

          var i_exist = [];

          //let pass = this.preselected_employers.includes(employer.id);
          //console.log(pass);

          //if (pass.length > 0){
            //return true
          //}else{
            //return false;
          //}
        

        },
        SelectName (option) {
          return `${option.keyword}`
        },
        onSelect (option, uid) {
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
        parsing_discipline(discipline){

            let str = discipline.toString();

            //let str= discipline.replace(/^\s+|\s+$/gm,'');

            //let parsed_data = JSON.parse(discipline);
            //return parsed_data

        },
        dsc_lookup(discipline_code){

            let self = this;
            let preselected_discipline = "";
          
            this.academic_disciplines.forEach(function (dsc) {


                  if (dsc.discipline_code === discipline_code){

                      preselected_discipline=dsc
                      let tst = self.selected_linked_academic_discipline.find(key => key.discipline_code === discipline_code)
                      if (tst){
                 
                    
                      }else{
                      
                        self.selected_linked_academic_discipline.push(dsc);
                      
                      }

                  }

            });

        },
 


    },




};



</script>


<template>
<div>

<div>
<head>
<style v-for="employer in employers">
 .active-unselected-{{employer.id}}{
    border-radius: 30px;
    background-color:#06439C;
    padding-left:12px;
    padding-right:12px;
    padding-bottom:1.5px;
    color:#ffffff;
    text-alighn:center;

  }

 .active-selected-{{employer.id}}{
    border-radius: 30px;
    background-color:green;
    padding-left:12px;
    padding-right:12px;
    padding-bottom:1.5px;
    color:#ffffff;
    text-alighn:center;

  }
</style>


</head/>
</div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <router-link to="/pathways/specializations/specializations">
                        <span class="bck-to-0"><i class="uil uil-arrow-left font-size-20"></i> back</span>
                    </router-link> 
                    
                    <h4 class="card-title">New specialization </h4>
                    <p class="card-title-desc">
                        

                        A specialization the profession attaiend from and area of study
                        <code>i.e,</code>  <strong>Surgeon</strong> - Degree in medical surgery etc
                        <code></code>
                        <code></code>.
                    </p>

                    <div class="row">
                        <div class="col-12">

                            <form v-on:submit.prevent="submitForm" class="form-horizontal " role="form">


                                <div class="card">
                                <div class="card-body">

                                <label >Name</label>
                                <input v-model="specializationform_field_data.name" type="text"
                                     class="form-control"
                                     placeholder="Name" name="Name"/>

                                <label class="pd-hld" >Description</label>
                                <ckeditor v-model="specializationform_field_data.description" :editor="editor"></ckeditor>

                                </div>
                                </div>

                                <div class="card">
                                <div class="card-body">

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
                                      id="keywords" 
                                      @select="onSelect" 
                                      :taggable="true" 
                                      :multiple="true" 
                                      @tag="addTag"
                                    >
                                    </multiselect>

                                <div class="row">
                                 
                                    <div class="col-md-6">
                                        <div class="form-group position-relative">
                                        <label class="pd-hld" >Logo</label>
                                        <input ref="specialization_image" v-on:change="handleLogoUpload()" type="file"  id="file_e1" class="form-control"
                                               placeholder="Logo" name="specializationform_field_data.logo"/>
                                        </div>

                                        <div class="col-md-12 mb-3" v-if="this.editAction">
                                          <img class="rounded mr-2" :alt="`${specializationform_field_data.name} logo`"
                                               width="200" :src="this.old_logo" />
                                        </div>

                                        <div class="col-md-12 mb-3" v-if="specializationform_field_data.specialization_image">
                                          <img class="rounded mr-2" :alt="`new ${specializationform_field_data.name} logo`"
                                               width="200" :src="specializationform_field_data.logo"
                                               data-holder-rendered="true" />
                                        </div>
                                    </div>


                                </div>

                                </div>
                                </div>


                                <div class="card">
                                <div class="card-body">

                                <div class="row">

                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >linked Academic Discipline</label>

                                          <multiselect v-model="selected_linked_academic_discipline" 
                                          :options="academic_disciplines" 
                                          :multiple="true" 
                                           placeholder="Select Academic discipline" 
                                           label="discipline_name" 
                                           track-by="discipline_code"
                                           id="academic_disciplines" 
                                          :value="specializationform_field_data.linked_academic_discipline" 
                                          :preselect-first="true">
                                          </multiselect>

                                          <span  v-for="discipline in specializationform_field_data.linked_academic_discipline">
                                            {{ dsc_lookup(discipline.academic_discipline_name) }}
                                            
                                          </span>


                                    </div>
                                  </div>

                                  <div class="col-md-6" >
                                    <div class="form-group position-relative">
                                        <label class="pd-hld" >pathway</label>

                                          <b-form-select
                                            v-model="specializationform_field_data.pathway_id"
                                            :options="pathways_list"
                                            class="mb-3 select_e1"
                                            value-field="id"
                                            text-field="name"
                                            disabled-field="notEnabled"
                                            placeholder="select pathway"
                                            >

                                          </b-form-select>

                                     </div>
                                  </div>


                                </div>

                                <span  v-if="proceed"  >

                                </span>

                              <!--  <span  v-else class="btn btn-primary w-md" @click="get_add_employers(specializationform_field_data.pathway_id)"  >
                                    Next
                                      <b-spinner label="Loading..." style="width: 1.2rem; height: 1.2rem;" v-if="loadingData_next"></b-spinner>
                                </span>-->

                                <div class="row">
                                  <div class="col-12">
                                    
                                  </div> 
                                </div>   
                               

                               </div>
                               </div> 

                                    <div class="card">
                                        <div class="card-body">

                                            <div role="tablist">
                                                <b-card no-body class="mb-1 shadow-none">
                                                    <b-card-header header-tag="header" role="tab">
                                                        <h6 class="m-0">
                                                            <a v-b-toggle.accordion-1 class="text-dark" href="javascript: void(0);">
                            
                                                            <span type="submit" class="btn add_employers btn-primary w-md"  >
                                                               <i class="mdi mdi-chevron-down accor-down-icon text-primary mr-1"></i>Select employers ({{selected_employers.length}}) 
                                                            </span>
                                                            </a>
                                                        </h6>
                            
                                                    </b-card-header>
                                                    <b-collapse id="accordion-1"  accordion="my-accordion" role="tabpanel">
                                                          <b-card-body>
                                                                
                                                                <multiselect v-model="employer_filter_selected" :options="multiselect_data" class="helo"></multiselect></multiselect>
                                                                <div class="card">
                                                                    <div class="card-body in-tab-l-item">
                                                                        <h4 class="card-title">Select employers</h4>

                                                                        <b-tabs pills vertical nav-class="p-0" nav-wrapper-class="col-sm-3"  content-class="pt-0 px-2 text-muted">
                                                                            
                                                                            <b-tab v-for="country in countries"  v-if="" :title="country.name" :id="`emp_country-${country.name}`" active title-item-class="mb-2 " @click="setcountryemployers(country.id)" >
                                                                                
                                                                            <div v-if="populated" > 

                                                                                <b-card-text ><h4 style="color:green;">{{country.name}}</h4></b-card-text>
                                                                                
                                                                                 <multiselect v-model="selected_employers"  :id="`country_empl_select-${country.name}`" :options="setemployers" :multiple="true" placeholder="Select Employer" label="name" track-by="name" :preselect-first="false">
                                                                                 </multiselect>

                                                                                <div class="saved-selection">

                                                                                    </br>
                                                                                    <h4 class="card-title">Saved Employer Selection</h4>
                                                                                    <ul  v-for="employer_list in preselected_employers">
                                                                                    
                                                                                      <div v-if="employer_list[0] && employer_list[0].country_id===country.id" >

                                                                                          <li v-if="if_exist(employer_list)">

                                                                                               <div  class="iexist-selection">
                                                                                                 {{employer_list[0].name}} 
                                                                                               </div>

                                                                                          </li>

                                                                                          <li v-else>
                                                                                               <div  class="" v-for="emp in employer_list"  >
                                                                                                 {{emp.name}} 
                                                                                               </div>
                                                                                          </li>

                                                                                      </div>

                                                                                    </ul>

                                                                                </div>

                                                                                <div class="current-selection"> 
                                                                                    </br>
                                                                                    <h4 class="card-title">Current Selection
                                                                                        <small>({{selected_employers.length}})</small>
                                                                                    </h4>
                                                                                    
                                                                                    <ul  v-for="employer in selected_employers">
                                                                                      <li v-if="if_exist(employer)" >
                                                                                       
                                                                                      </li>

                                                                                      <li v-else >
                                                                                       {{employer.name}}

                                                                                      </li>

                                                                                    </ul>
                                                                                    <span @click="done_selecting_employers(selected_employers)" id="done_selecting_btn"  v-b-toggle.accordion-1 class="btn move-right select-done0 btn-primary w-md"   >
                                                                                      Done
                                                                                    </span>

                                                                                </div>

                                                                            </div>  

                                                                             <div v-else >
                                                                               <hr>
                                                                               <p>click on country to get employers </p>
                                                                               <hr>
                                                                             </div> 
                                                                                  

                                                                            </b-tab>

                                                                        </b-tabs>
                                                                    </div>
                                                                </div>

                                                        </b-card-body>
                                                    </b-collapse>
                                                </b-card>


                                            </div>
                                        </div>
                                    </div></br>

                                <button type="submit" id="submit_empl"  class="btn btn-primary w-md"  >
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


    <!-- end row -->
</div>
</template>

<script>
/**
 * Basic table component
 */
import StudentManagementService from "@/helpers/services/StudentManagementService";
import ClusterManagementService from "@/helpers/services/ClusterManagementService";
import CoursePathwaysManagementService from "@/helpers/services/CoursePathwaysManagementService";
import SubjectDisplayService from "@/helpers/services/SubjectDisplayService";

import PathwaysService from "@/helpers/services/PathwaysService";
import {BlockUI} from 'vue-blockui'
// import the component
import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import CKEditor from "@ckeditor/ckeditor5-vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";


import axios from "axios";

export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        };
    },
    data() {
        return {
            pathwayId: "",
            title: "Subject Clustering|",
            editor: ClassicEditor,
            spec_items: [],
            alternatives:[],
            cluster_subjects:[],
            built_clusters:[],
            current_discipline:null,
            current_cluster:null,
            editingSubject:false,
            subject_form:false,
            loading_items:true,
            loading_in_modal:true,
            loading_in_form_popup:false,
            current_delete_item:null,
            editing_item:false,
            confirm_action:false,
            data_loaded:false,
            current_educationtype_id:null,
            current_display_subject_id:null,
            current_educationtype:"",
            current_country_id:null,
            current_country:null,
            built_countries_list:[],
            display_subjects:[],
            subject_inputs:[],
            discipline_subjects:[],
            form_data:{
                 education_type_id:"",
                 country_id:false,
                 academic_disciplines_id:"",
                 subject_title:"",
                 subject_title_description:" Description",
                 display_order:1,

            },
            fields: [
                {
                  key: "check",
                  label: "",
                },
                {
                  key: "Name",
                  label: 'Name',
                  sortable: true
                },
                /*{
                  key: "Country",
                  label: 'country',
                  sortable: true
                },*/
                {
                  key: "Display order",
                  label: 'Type',
                  sortable: true
                },
                {
                  key: "actions",
                  label: 'Actions',
                  sortable: true
                },
            ],
            tab2_fields: [
                {
                  key: "check",
                  label: "",
                },
                {
                  key: "Name",
                  label: 'Name',
                  sortable: true
                },
                /*{
                  key: "Country",
                  label: 'country',
                  sortable: true
                },*/
                {
                  key: "Type",
                  label: 'Type',
                  sortable: true
                },
                {
                  key: "actions",
                  label: 'Actions',
                  sortable: true
                },
            ],
            build_data:{
                subjects:[],
                education_types:[],
                countries:[],
                academic_disciplines:[],
                grades:[
                    {grade:"A"},
                    {grade:"A-"},
                    {grade:"B+"},
                    {grade:"B"},
                    {grade:"B-"},
                    {grade:"C+"},
                    {grade:"C"},
                    {grade:"C-"},
                    {grade:"D+"},
                    {grade:"D"},
                    {grade:"D-"},
                    {grade:"E"}
                ],
                is_primary:[
                    {id: 1,value:"is_primary"},
                    {id: 2,value:"is_altervative"},
                ]
            },
            clusters:[],
            FormSubmittedSuccessText:"",
            FormSubmittedSuccess:false,
            FormSubmittedError:false,
            FormSubmittedErrorText:"",
            totalRows:1,
            currentPage: 1,
            perPage: 10,
            nextPage:2,
            totalRows_t2:1,
            currentPage_t2: 1,
            perPage_t2: 10,
            pageOptions: [10, 25, 50, 100],
            nextPage_t2:2,
            filter: null,
            filterOn: [],
            sortBy: "id",
            sortDesc: false,
            loading_in_modal: false,
            sliderPrice: 800,
            items: [{
                    text: "Subject Managemen"
                },
                {
                    text: "Subject Clustering | ",
                    active: true
                }
            ]
        };
    },
    components: {
      BlockUI,
      Treeselect,
      ckeditor:CKEditor.component
    },
    computed: {
        /**
         * Total no. of records
         */
        rows() {
          return this.totalRows;
        },

        rows_second_tab() {
          return this.totalRows_t2;
        }
    },
    created(){
      this.getCluster();
      this.build();
      this.buildGrades();
      if (this.$route.query.id){
        this.editing = true;
      }
    },
    middleware: "authentication",
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
          rollbar.log(console.error(""));
          rollbar.log(console.trace());
        },
        async build() {
            let self = this;

           //start loading
            this.$nextTick(() => {
            });

            let response = await ClusterManagementService.build();
            if (response.data.status) {
                this.build_data.education_types=response.data.data.education_types;
                this.build_data.countries=response.data.data.countries;

                var init_build_data = response.data.data.subjects;
                init_build_data.forEach((subject) => {
                 var item = { "id": subject.id, "label": subject.subject_name }
                 self.build_data.subjects.push({ "id": subject.id, "label": subject.subject_name } )
                });

                //start loading
                this.$nextTick(() => {
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }

            let response_i = await CoursePathwaysManagementService.build();
            if (response_i.data.status) {
                var init_build_data = response_i.data.data.disciplines;
                init_build_data.forEach((discipline) => {
                 var item = { "id": discipline.id, "label": discipline.discipline_name }
                 self.build_data.academic_disciplines.push({ "id": discipline.id, "label": discipline.discipline_name } )
                });
            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }
        },
        async buildGrades(){
            let self = this;
            let response = await ClusterManagementService.buildGrades();
            if (response.data.status) {
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        async submitform() {
            this.form_data.country_id= this.current_country_id
            this.form_data.academic_disciplines_id= this.current_discipline.id
            this.$nextTick(() => {
              this.loading_in_form_popup=true;
            });

            let self = this;
            let response = await SubjectDisplayService.create(this.form_data);
            if(response.status){
               this.get_sbd_data(this.form_data.education_type_id);
               this.form_data.country_id= ""
               this.form_data.academic_disciplines_id=""
               this.form_data.education_type_id=""
               this.form_data.subject_title=""
               this.form_data.subject_title_description=""
               this.form_data.display_order=1

               this.$nextTick(() => {
                 this.loading_in_form_popup=false;
               });
               //close form pop up
               this.subject_form=false;
            }
        },
        async updateform() {
            this.$nextTick(() => {
              this.loading_in_form_popup=true;
            });

            let self = this;
            let response = await SubjectDisplayService.update(this.current_display_subject_id,this.form_data);
            if (response.data.status) {
                self.FormSubmittedSuccess = response.data.status;
                self.FormSubmittedSuccessText = response.data.message;
                this.get_sbd_data(this.form_data.education_type_id);
                this.form_data.display_order=1
                this.form_data.subject_title_description=""
                this.form_data.subject_form=false;

                //close update form
                this.$nextTick(() => {
                    this.editing_item=false;
                    self.FormSubmittedSuccess=response.data.status;
                    self.FormSubmittedSuccessText=response.data.message;
                    this.loading_in_form_popup=false;
                });

            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }
        },
        async getCluster() {
            let self = this;
           //start loading
            this.$nextTick(() => {
            });

            let response = await ClusterManagementService.listClusters();
            if (response.data.status) {
                self.clusters = response.data.data.items;
                self.loading_items=false;
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        populate_data(discipline){
            this.current_discipline = discipline
            this.display_subjects=[];
            this.current_educationtype_id=null;
            this.current_display_subject_id=null;
            this.current_country_id=null;
            this.form_data.country_id="";
            this.form_data.id="";

            // get the subject display list for tab 1
            this.get_sbd_data(null,null,this.country_id)

            // get the academic discipline subject list for tab 2
            this.get_subjectlist_data(this.current_discipline);
        },
        get_sbd_data(id){
            let self = this;
            this.loading_in_modal=true;
            this.FormSubmittedSuccess = false;
            this.FormSubmittedError=false;
            this.FormSubmittedErrorText="";
            this.current_educationtype_id = id
            let formdata = new FormData

            formdata.append("country_id",this.current_country_id)
            formdata.append("education_type_id",this.current_country_id)
            formdata.append("country_id",this.current_country_id)

            SubjectDisplayService.getListDisplays(id,this.current_discipline.id,this.current_country_id,this.current_page).then(response => {
              console.log(response);
              if (response.data.status) {
                    this.display_subjects = response.data.data;
                    this.totalRows = response.data.data.items_count;
                    this.perPage = response.data.data.items_per_page;
                    this.currentPage = response.data.data.current_page;

                    this.loading_in_modal=false;
                    this.form_data.subject_title=""
                    this.form_data.subject_title_description=""
                    this.form_data.display_order=1

                    //start loading
                    this.$nextTick(() => {
                    });
                }else{
                    self.FormSubmittedError=response.data.status;
                    self.FormSubmittedErrorText=response.data.message;
                }
            });
        },
        get_subjectlist_data(id){
            let self = this;
            this.loading_in_modal=true;
            this.FormSubmittedSuccess = false;
            this.FormSubmittedError=false;
            this.FormSubmittedErrorText="";
            this.current_educationtype_id = id
            SubjectDisplayService.getList(this.current_discipline.id,this.currentPage_t2).then(response => {
                if (response.data.status) {
                    this.discipline_subjects = response.data.data.items[0].subjects;
                    this.loading_in_modal=false;
                    this.form_data.subject_title=""
                    this.form_data.subject_title_description=""
                    this.form_data.display_order=1

                    this.totalRows_t2 = response.data.data.items_count;
                    this.perPage_t2 = response.data.data.items_per_page;
                    this.currentPage_t2 = response.data.data.current_page;
                    //this.nextPage_t2 = response.data.data.next_page
                    //start loading
                    this.$nextTick(() => {
                    });
                }else{
                    self.FormSubmittedError=response.data.status;
                    self.FormSubmittedErrorText=response.data.message;
                }
            });
        },
        onChange(event) {
          this.form_data.education_type_id=event;
          this.current_educationtype_id=event;
          this.data_loaded = true;
          this.get_sbd_data(event);

            //get the current selected educationtype
            const index = this.build_data.education_types.findIndex((item) => {
              return item.id == event;
            });
            this.current_educationtype = this.build_data.education_types[index];

        },
        onChangeCountry(event) {
          this.current_country_id = event
          this.form_data.education_type_id=null;
          this.current_educationtype_id=null;
          this.data_loaded = false;
            //get the current selected educationtype
            const index = this.build_data.countries.findIndex((item) => {
              return item.id == event;
            });
            this.current_country=this.build_data.countries[index];
        },
        currentClusterSelected(){
            if (this.current_cluster_selected==true){
                return true;
            }else{
                return false;
            }
        },
        newSubjectPopup(){
           this.subject_form=true;
        },
        async editPopup(item){
            //start loading
            this.$nextTick(() => {
              this.loading_in_form_popup=true;
            });

            this.new_subject=true;
            this.editing=true;
            this.editing_item=true;

            let response = await SubjectDisplayService.getitem(item.id);
            let data = response.data.data;
            if(response.status){
                this.form_data.academic_disciplines_id= data.academic_disciplines_id;
                this.current_display_subject_id=data.id;
                this.form_data.country_id= data.country_id;
                this.form_data.display_order= data.display_order;
                this.form_data.education_type_id= data.education_type_id;
                this.form_data.subject_title= data.subject_title;
                this.form_data.subject_title_description=  data.subject_title_description;
                self.FormSubmittedSuccess=response.data.status;
                self.FormSubmittedSuccessText=response.data.message;
                  //start loading
                this.$nextTick(() => {
                  this.loading_in_form_popup=false;
                });

            }else{
                self.FormSubmittedError=response.data.status;
                self.FormSubmittedErrorText=response.data.message;
            }

        },
        new_subject_class_form(item){
           this.subject_inputs.push(item)
        },
        remove_form_box(index){
          delete this.subject_inputs[index];
        },
        newClose(){
            this.subject_form=false;
            this.editing_item=false;
            this.form_data.subject_title=""
            this.form_data.subject_title_description=""
            this.form_data.display_order=1
        },
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.items;
          //this.currentPage = this.current_page;
        },
        async delete_this(item) {

            this.$nextTick(() => {
              this.loading_in_form_popup=true;
            });
          let self = this;
          let response = await SubjectDisplayService.delete(item.id);
          if (response.data.status) {
              this.FormSubmittedSuccess = response.data.status;
              this.FormSubmittedSuccessText = response.data.message;
              this.get_sbd_data(this.current_educationtype_id,this.current_discipline.id,this.current_country_id);
              this.confirm_action=false;
              this.formData=[];
              //stop loading
              this.$nextTick(() => {
                this.loading_in_form_popup=false;
              });
          }else{
              self.FormSubmittedError=response.data.data.status;
              self.FormSubmittedErrorText=response.data.data.message;
          }
        },
        handlePageChange(page) {
          //get the data
          this.get_sbd_data(this.current_educationtype_id,page)
        },
        handlePageChangeSecondTab(page) {
          //get the data
          this.get_subjectlist_data(this.current_discipline,this.nextPage_t2);
        },
        confirmSubmitAction(item){
          this.current_delete_item=item;
          this.confirm_action=true;
        },
        cancelSubmitActions(){
          this.confirm_action=false;
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

    },

};
</script>

<template>
<div>

 <div class="row">

        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent border-bottom">

                    </br>
                    <h5 class="mb-0">Disciplines</h5>
                    <b-spinner label="Loading..." v-if="loading_items" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>
                </div>

                <div class="p-4">
                    <div class="custom-accordion" >
                       <div class="inaccordion-wrapper">
                        <a v-for="item in build_data.academic_disciplines" @click="populate_data(item)" class="text-body font-weight-semibold pb-2 d-block" data-toggle="collapse" href="javascript: void(0);" role="button" aria-expanded="false" >
                            <i class="mdi mdi-chevron-up accor-down-icon text-primary mr-1"></i>
                            {{item.label}}
                        </a>
                        </div>
                    </div>

                </div>
                <div class="custom-accordion"></div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8" style="width:58%;right:0.5rem">
            <div class="card" style=";">

                <div class="card-body" >

                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <h5>
                                   <!--  <nuxt-link
                                     :to="{path:'javascript: void(0);', query:{id:1} }"
                                      class="px-2 text-primary" v-b-tooltip.hover title="Edit" >
                                       <i class="uil uil-pen font-size-18"></i>
                                     </nuxt-link> -->

                                     <span >
                                      <span v-if="this.current_discipline!=null" class="pathway-nm-0"> {{current_discipline.label}} </span>
                                     </span>| Subject Requirements
                                    </h5>
                                </div>
                            </div>

                            <div v-if="data_loaded" class="move-right">
                                <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="newSubjectPopup()" >
                                    <span class="bck-to-0">&nbsp;&nbsp;Add new Subject display&nbsp;&nbsp;</span>
                                </a>
                            </div>

                            <BlockUI  v-if="editing_item">
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>
                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                  <p>{{FormSubmittedSuccessText}} </p>
                                </div>

                                <span class="close" @click="newClose()"></span>
                                <h4> Edit Subject Display</h4>
                                <b-spinner label="Loading..." v-if="loading_in_form_popup" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>

                                <div class="sb_form_wrapper">
                                <form v-on:submit.prevent="updateform" class="form-horizontal" role="form">
                                    <div class="form-group position-relative">
                                        <div class="item_holderx">
                                            <strong>Curent Country: </strong>&nbsp;{{current_country.name}}</br>
                                            <strong>Current Education type: &nbsp;</strong>{{current_educationtype.education_type_name}}</br>
                                            <div >
                                                <label class="pd-hld" id="current_position" >Subjects Details</label>

                                                <label class="pd-hld move-left" id="current_position" >Subjects Details</label>
                                                <input v-model="form_data.subject_title" type="text"
                                                 class="form-control"
                                                 placeholder="Subject classification Name" name="Subject-classification"/>
                                                </br>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="pd-hld move-left" id="current_position" >Subjects Details</label>
                                    <div class="item_holderx">
                                       <textarea v-model="form_data.subject_title_description" ></textarea>
                                       </br></br>
                                    </div>
                                    <hr>
                                    <div  class="col-xl-3">
                                      <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-left">
                                        Submit
                                      </button>
                                    </div>
                                </form>

                                </div>
                                </br>
                            </BlockUI>

                            <BlockUI  v-if="subject_form">
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>
                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                  <p>{{FormSubmittedSuccessText}} </p>
                                </div>

                                <span class="close" @click="newClose()"></span>
                                <h4> Add Subject Display</h4>
                                <div class="sb_form_wrapper">
                                <form v-on:submit.prevent="submitform" class="form-horizontal" role="form">
                                    <div class="form-group position-relative">
                                         <div  class="item_holderx"> </div>
                                         <strong>Curent Country: </strong>&nbsp;{{current_country.name}}</br>

                                         <strong>Current Education type: &nbsp;</strong>{{current_educationtype.education_type_name}}</br>
                                        <label class="pd-hld move-left" id="current_position" >Subject Title</label>
                                        <div class="item_holderx">
                                            <div>
                                                <input v-model="form_data.subject_title" type="text"
                                                 class="form-control"
                                                 placeholder="Subject Title" name="Subject-classification"/>
                                            </div>
                                        </div>

                                        <label class="pd-hld move-left" id="current_position" >Description</label>
                                        <div class="item_holderx">
                                            <div>
                                              <textarea v-model="form_data.subject_title_description" ></textarea>
                                            </div></br></br>
                                        </div>
                                    </div>
                                    <hr>
                                    <div  class="col-xl-3">
                                    </br></br>
                                      <button type="submit" id="submit_alumni" class="btn btn-primary w-md move-left">
                                        Submit
                                      </button>
                                    </div>
                                </form>

                                </div>
                                </br>
                            </BlockUI>
                        </div>

                        <ul class="nav nav-tabs nav-tabs-custom mt-3 mb-2 ecommerce-sortby-list">
                            <li class="nav-item">
                                <a class="nav-link active tablinks" @click="tabs_control($event,'eligibility-creteria')" href="javascript:void(0)">Subject Display Requirements:</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link  tablinks" @click="tabs_control($event,'academic-discipline-subjects')" href="javascript:void(0)">Reccomendation Engine subject list:</a>
                            </li>

                        </ul>

                        <div class="row">
                           <div id="eligibility-creteria" class="tabcontent active">
                            <b-container class="bv-example-row">
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>
                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                  <p>{{FormSubmittedSuccessText}} </p>
                                </div>
                            <b-row>
                              <b-col sm="auto" class="list_sec1">
                                  <div class="col-12">
                                    <!-- Table -->
                                    <div class="row mt-4">
                                       <div   class="table-responsive mb-0 table_holder">
                                       <b-spinner label="Loading..." v-if="loading_in_modal" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>
                                            <div v-if="current_discipline!=null">
                                            <div  class="item_holderx">
                                                <label class="pd-hld" id="current_position" >Country</label>
                                                <b-form-select
                                                v-model="form_data.country_id"
                                                :options="build_data.countries"
                                                class="mb-3 select_ecp"
                                                value-field="id"
                                                text-field="name"
                                                disabled-field="notEnabled"
                                                placeholder="Education types"
                                                @change="onChangeCountry($event)"
                                                >
                                                <b-form-select-option >
                                                 Educations type</b-form-select-option>
                                                </b-form-select>
                                            </div>

                                            <div  v-if="current_country_id!=null" class="item_holderx">
                                                <label class="pd-hld" id="current_position" >Education system</label>
                                                <b-form-select
                                                v-model="form_data.education_type_id"
                                                :options="build_data.education_types"
                                                class="mb-3 select_ecp"
                                                value-field="id"
                                                text-field="education_type_name"
                                                disabled-field="notEnabled"
                                                placeholder="Education types"
                                                @change="onChange($event)"
                                                >
                                                <b-form-select-option >
                                                 Educations type</b-form-select-option>
                                                </b-form-select>
                                            </div>
                                          </div>
                                       <!-- Table -->
                                        <div class="table-responsive mb-0 " >
                                            <b-table
                                            :items="display_subjects"
                                            :fields="fields"
                                            responsive="sm"
                                            :per-page="perPage"
                                            :current-page="currentPage"
                                            :sort-by.sync="sortBy"
                                            :sort-desc.sync="sortDesc"
                                            :filter="filter"
                                            :filter-included-fields="filterOn"
                                            @filtered="onFiltered">
                                                    <template v-slot:cell(name)="data">
                                                      <div class="details_field px-2">
                                                          <div style='margin-left:10px;'>
                                                            <span ><strong>{{ data.item.subject_title }}</strong>&nbsp;&nbsp;</span></br>
                                                            <span v-html="data.item.subject_title_description " >&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                            </br></br>
                                                          </div>
                                                      </div>
                                                    </template>

                                                    <template v-slot:cell(type)="data">
                                                        <div class="details_field px-2">
                                                          <div style='margin-left:10px;'>
                                                            {{ data.item.display_order}}</br>
                                                            <div style="font-size:.6em"></div>
                                                          </div>
                                                        </div>
                                                    </template>

                                                    <template v-slot:cell(actions)="data">
                                                        <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="editPopup(data.item)" v-b-tooltip.hover title="Edit">
                                                         <i class="uil uil-pen font-size-18"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" role="button" class="px-2 " @click="confirmSubmitAction(data.item)" v-b-tooltip.hover title="delete">
                                                          <i class="uil uil-trash-alt font-size-18"></i>
                                                        </a>
                                                    </template>
                                            </b-table>

                                            <BlockUI  v-if="confirm_action">
                                              <p class="block-iu-in-smgtex0">
                                                <i class="uil-exclamation-triangle font-size-100"></i></h3> </br>
                                                <strong>You are about to do a Delete action on selected item,</br>
                                                </br>
                                                are you sure you wish to complete this action!! </strong></br>

                                              </p>

                                              <div class="in-alert-cbtn_wrapper">
                                                <b-button variant="primary" class="btn btn-primary waves-effect waves-light mb-3" @click="cancelSubmitActions" >Cancel
                                                </b-button>
                                                <button  type="submit" @click="delete_this(current_delete_item)" id="submit_alumni" class="btn confirm-002 waves-effect waves-light mb-3">
                                                  I know what im doing
                                                </button>
                                              </div>
                                            </BlockUI>

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
                                      </div>

                                    </div>
                                 </div>
                                </div>
                             </b-col>

                            </b-row>
                          </b-container>
                        </div><!-- end first tab -->

                        <!-- TAB2 begin second tab -->
                           <div id="academic-discipline-subjects" class="tabcontent ">
                            <b-container class="bv-example-row">
                                <div class="alert alert-danger" v-if="FormSubmittedError">
                                  <p> {{FormSubmittedErrorText}} </p>
                                </div>
                                <div class="alert alert-success" v-if="FormSubmittedSuccess">
                                  <p>{{FormSubmittedSuccessText}} </p>
                                </div>
                            <b-row>
                              <b-col sm="auto" class="list_sec1">
                                  <div class="col-12">
                                    <!-- Table -->
                                    <div class="row mt-4">
                                       <div   class="table-responsive mb-0 table_holder">
                                       <b-spinner label="Loading..." v-if="loading_in_modal" style="margin-left:3rem;width: 1.7rem; height: 1.7rem;" ></b-spinner>

                                       <!-- Table -->
                                        <div class="table-responsive mb-0 " >
                                            <b-table
                                            :items="discipline_subjects"
                                            :fields="tab2_fields"
                                            responsive="sm" :per-page="perPage"
                                            :current-page="currentPage"
                                            :sort-by.sync="sortBy"
                                            :sort-desc.sync="sortDesc"
                                            :filter="filter"
                                            :filter-included-fields="filterOn"
                                            @filtered="onFiltered">
                                                    <template v-slot:cell(name)="data">
                                                      <div class="details_field px-2">
                                                          <div style='margin-left:10px;'>
                                                            <span >{{ data.item.subject }}&nbsp;&nbsp;</span></br>
                                                            <span v-html="data.item.subject_title_description " >&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                          </div>
                                                      </div>
                                                    </template>

                                                    <template v-slot:cell(Type)="data">
                                                        <div class="details_field px-2">
                                                          <div v-if="data.item.is_primary==1" style='margin-left:10px;'>
                                                            Manadatory</br>
                                                            <div style="font-size:.6em"></div>
                                                          </div>
                                                          <div v-if="data.item.is_primary==2" style='margin-left:10px;'>
                                                            Alternative</br>
                                                            <div style="font-size:.6em"></div>
                                                          </div>
                                                        </div>
                                                    </template>

                                                   <!-- <template v-slot:cell(actions)="data">
                                                        <a  href="javascript: void(0);" role="button" aria-expanded="false" @click="editPopup(data.item)" v-b-tooltip.hover title="Edit">
                                                         <i class="uil uil-pen font-size-18"></i>
                                                        </a>
                                                        <a href="javascript:void(0);" role="button" class="px-2 " @click="confirmSubmitAction(data.item)" v-b-tooltip.hover title="delete">
                                                          <i class="uil uil-trash-alt font-size-18"></i>
                                                        </a>
                                                    </template> -->
                                            </b-table>

                                            <div class="row">
                                              <div class="col">
                                                <div class="dataTables_paginate paging_simple_numbers float-right">
                                                  <ul class="pagination pagination-rounded">
                                                    <!-- pagination -->
                                                    <b-pagination @input="handlePageChangeSecondTab" v-model="currentPage_t2" :total-rows="rows_second_tab"
                                                                  :per-page="perPage_t2"></b-pagination>
                                                  </ul>
                                                </div>
                                              </div>
                                            </div>

                                            <BlockUI  v-if="confirm_action">
                                              <p class="block-iu-in-smgtex0">
                                                <i class="uil-exclamation-triangle font-size-100"></i></h3> </br>
                                                <strong>You are about to do a Delete action on selected item,</br>
                                                </br>
                                                are you sure you wish to complete this action!! </strong></br>

                                              </p>

                                              <div class="in-alert-cbtn_wrapper">
                                                <b-button variant="primary" class="btn btn-primary waves-effect waves-light mb-3" @click="cancelSubmitActions" >Cancel
                                                </b-button>
                                                <button  type="submit" @click="delete_this(current_delete_item)" id="submit_alumni" class="btn confirm-002 waves-effect waves-light mb-3">
                                                  I know what im doing
                                                </button>
                                              </div>
                                            </BlockUI>


                                      </div>

                                    </div>
                                 </div>
                                </div>
                             </b-col>

                            </b-row>
                          </b-container>
                        </div><!-- end second tab -->

                     </div>

                </div>
            </div>
        </div>
    </div>

  </div>

</div>
</template>

<style scoped>

.form_sec0{
  max-width:100%;
  min-width:100%;
}

.search_header_wrapper{
    min-height:3rem;
    width:100%;
}
.move-right{
    width:50%;
}

.dynamic_input{
    padding:1rem;
}

.item_holderx{
    width:45%;
    float:left;
    padding-left:1rem;
    margin-top:0px !important;
}

.list_sec1{
    width:100% !important;
}

.close {
  position: absolute;
  right: 32px;
  top: 10px;
  width: 20px;
  height: 20px;
  opacity: 0.3;
}
.close:hover {
  opacity: 1;
}
.close:before, .close:after {
  position: absolute;
  left: 15px;
  content: ' ';
  height: 20px;
  width: 2px;
  background-color: #333;
}
.close:before {
  transform: rotate(45deg);
}
.close:after {
  transform: rotate(-45deg);
}

.move-left{
  float:left;
  margin-left:1rem;
}

.btn-primary{
   max-width:90%;
}

.sb_form_wrapper .item_holderx{
  width:95%;
}
.sb_form_wrapper{
    min-height:400px;
    max-height:400px;
    min-width:500px;
}

textarea{
     min-height:300px;
    max-height:400px;
    min-width:500px;
}

.ck-editor .ck-editor__main .ck-content {
    min-height: 200px;
}

.inaccordion-wrapper{
    max-height:450px;
    width:112%;
    overflow:scroll;
    overflow-x:hidden;
}
</style>

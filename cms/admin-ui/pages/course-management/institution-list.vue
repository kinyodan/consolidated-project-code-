<template>
  <div>

    <div class="row">

      <div class="col-12">

        <div class="card">

          <!-- START MAIN CONTAINER CARD BODY -->
          <div class="card-body">

            <PageHeader :title="title" :items="items"/>
            <div class="row">
              <div class="col-12">
                <div>
                  <router-link class="btn btn-success waves-effect waves-light mb-3 mr-3"
                               to="/course-management/institution-add"><i class="mdi mdi-plus-box-outline mr-1"></i> Add Single
                    Institution
                  </router-link>
                  <router-link class="btn btn-primary waves-effect waves-light mb-3"
                               to="/course-management/institution-upload-bulk"><i
                    class="mdi mdi-plus-box-multiple-outline mr-1"></i> Bulk Upload Institutions
                  </router-link>

                  <router-link class="btn btn-primary waves-effect waves-light mb-3"
                               to="/course-management/alumni-upload-bulk"><i
                    class="mdi mdi-plus-box-multiple-outline mr-1"></i> Bulk Alumni Upload
                  </router-link>

                </div>
                <div class="row mt-4">
                  <div class="card w-100">
                    <div class="card-body"> 
                      <h4 class="card-title">Filters</h4>
                      <div>
                        <b-form inline>
                          <b-form-select class="mr-3" v-model="filterData.country_code" value="filterData.country_code">
                            <b-form-select-option :value="null">Select a country</b-form-select-option>
                            <b-form-select-option v-for="country in countries" :key="country.name" :selected="selectedCountry && selectedCountry === country.iso_code" :value="country.iso_code">
                              {{ country.name }}
                            </b-form-select-option>
                          </b-form-select>
                          <b-button variant="primary" class="mr-3" @click="handleFilters" :disabled="loadingData">Filter
                          </b-button>
                          <b-button variant="secondary" class="mr-3" @click="handleClearFilters" :disabled="loadingData">Clear
                          </b-button>
                          <b-spinner class="m-2" variant="success" role="status" v-if="loadingData"></b-spinner>
                        </b-form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-sm-12 col-md-6">

                  </div>
                  <!-- Search -->
                  <div class="col-sm-12 col-md-6">
                    <div id="tickets-table_filter" class="dataTables_filter text-md-right">
                      <label class="d-inline-flex align-items-center">
                        Search:
                        <b-form-input v-model="filter" type="search" placeholder="Search..."
                                      class="form-control form-control-sm ml-2"></b-form-input>
                      </label>
                    </div>
                  </div>
                  <!-- End search -->
                </div>
                <!-- Table -->
                <div class="table-responsive mb-0">
                  <b-table
                    table-class="table table-centered datatable table-card-list"
                    thead-tr-class="bg-transparent"
                    :items="tableData"
                    :fields="fields"
                    responsive="sm"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :filter="filter"
                    :filter-included-fields="filterOn" @filtered="onFiltered">
                    <template v-slot:cell(check)="data">
                      <div class="custom-control custom-checkbox text-center">
                        <input type="checkbox" class="custom-control-input" :id="`contacusercheck${data.item.id}`"/>
                        <label class="custom-control-label" :for="`contacusercheck${data.item.id}`"></label>
                      </div>
                    </template>

                    <template v-slot:cell(institution_details)="data">
                        <div class="details_field px-2">
                          <router-link class=" text-primary " v-b-tooltip.hover title="Open to show"
                                       :to="{path:'/course-management/institution-edit/', query:{code:data.item.institution_code} }">      
                              <div>
                                <img v-if="data.item.logo_url" :src="data.item.logo_url" alt class="avatar-xs iconDetails_linked mr-2"/>   
                                <img v-else :src="require('@/assets/images/6640292.jpg')" alt class="avatar-xs iconDetails_linked mr-2"/>   
                              </div>
                          </router-link>
                          <router-link class=" text-primary " v-b-tooltip.hover title="Open to show"
                                       :to="{path:'/course-management/institution-edit/', query:{code:data.item.institution_code} }">      
                            <div style='margin-left:10px;'>
                              <strong>{{ data.item.institution_name }}</strong></br></br>
                              <strong>Institution:</strong><i> {{ data.item.type.name }}</i></br>
                              <strong>Phone no: </strong>{{ data.item.phone_number }}</br>
                              <strong>Course code:</strong> {{ data.item.institution_code }}</br>                                     
                              <strong>Country: </strong>{{ data.item.country.name }}</br></br>
                              <div style="font-size:.6em"></div>
                            </div>
                          </router-link>
                       </div>
                       <div class="card action0_wrapper">
                            <div>
                              <nuxt-link :to="{path:'/course-management/institution-gallery/', query:{code:data.item.institution_code}}"
                                         class="btn btn-sm waves-effect waves-light etd-gallery px-2">
                                <strong>Gallery <i class="uil uil-images"></i></strong>
                              </nuxt-link>
                            </div>
                            <div>
                              <nuxt-link
                                :to="{path:'/course-management/institution-edit/', query:{code:data.item.institution_code} }"
                                class="px-2 text-primary etd-edit" v-b-tooltip.hover title="Edit"><i class="uil uil-pen font-size-18"></i>
                              </nuxt-link>
                            </div>
                            <div>
                                <a href="javascript:void(0);" class="px-2 etd-featured" @click="setFeatured(data.item)"
                                   v-b-tooltip.hover title="Set Featured">
                                  <i class="uil uil-star font-size-18"></i>
                                </a>
                            </div>
                            <div>
                                <a href="javascript:void(0);" class="px-2" @click="delete_institution(data.item.course_code)"
                                   v-b-tooltip.hover title="delete">
                                  <i class="uil uil-trash-alt font-size-18"></i>
                                </a>
                            </div>  
                            <div> 
                              <strong>Publish</strong>&nbsp;
                              <toggle-button @change="publishonChangeEventHandler(data.item.institution_code,data.item.is_published )" :value="check_state(data.item.is_published)"
                                           color="green"
                                           :sync="true"
                                           :labels="{checked: 'On', unchecked: 'Off'}"/>
                            </div>
                        </div>
                        
                    </template>
                    <!--<template v-slot:cell(id)="data">
                      <a href="javascript: void(0);" class="text-dark font-weight-bold">{{ data.item.id }}</a>
                    </template>-->
                    <!--
                    <template v-slot:cell(institution_code)="data">
                      <a href="#" class="text-body font-weight-bold "><span class="etd-code">{{ data.item.institution_code }}</span></a>
                    </template> -->
                    <!--
                    <template v-slot:cell(institution_type)="data">
                      <a href="#" class="text-body "><span class="etd-type">{{ data.item.type.name }}</span></a>
                    </template> -->
                    <!--
                    <template v-slot:cell(institution_name)="data">
                      <img v-if="data.item.logo_url" :src="data.item.logo_url" alt class="avatar-xs rounded-circle mr-2"/>
                      <a href="#" class="text-body "><span class="etd-name">{{ data.item.institution_name }}</span></a>
                    </template> -->
                    <!--            <template v-slot:cell(email_address)="data">
                                  <a href="#" class="text-body">{{ data.item.email_address }}</a>
                                </template>-->
                    <template v-slot:cell(phone_number)="data">
                      <a href="#" class="text-body "><span class="etd-phone">{{ data.item.phone_number }}</span></a>
                    </template>
                    <template v-slot:cell(gallery)="data">
                      <nuxt-link :to="{path:'/course-management/institution-gallery/', query:{code:data.item.institution_code}}"
                                 class="btn btn-sm btn-primary waves-effect waves-light etd-gallery">
                        <i class="uil uil-images"></i>
                      </nuxt-link>
                    </template>
                  </b-table>
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
              </div>
            </div>

          </div>
         <!-- END MAIN CONTAINER CARD BODY -->
         
        </div>
      </div>
    </div>


  </div>
</template>

<script>
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";
import {mapActions, mapState} from "vuex";
import { ToggleButton } from 'vue-js-toggle-button'

export default {
  head() {
    return {
      title: `${this.title} | Institutions`
    };
  },
  data() {
    return {
      tableData: [],
      loadingData: false,
      title: "Institutions",
      items: [{
        text: "Course Management"
      },
        {
          text: "Institutions",
          active: true
        }
      ],
      totalRows: 1,
      countries: [],
      FormSubmittedSuccess:false,
      FormSubmittedSuccessText:"",
      FormSubmittedError:false,
      FormSubmittedErrorText:"",      
      filterData: {
        country_code: null
      },
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50, 100],
      filter: null,
      filterOn: [],
      sortBy: "id",
      sortDesc: false,
      fields: [{
        key: "check",
        label: "",
      },
        /*{
          key: "id",
          label:'ID',
          sortable: true
        },*/
        {
          key: "institution_details",
          label: 'Institution Details',
          sortable: true
        },
        
      ]
    };
  },
  components: {
    ToggleButton,
  },  
  computed: {
    ...mapState('modules/institution', {
      selectedCountry: state => state.filterCountry
    }),
    /**
     * Total no. of records
     */
    rows() {
      return this.totalRows;
    }
  },
  created() {
    this.getFormBuilder()
    this.rollbar_init();
    this.getData("")
  },
  methods: {
    ...mapActions('modules/institution',[
      'setFilterCountry'
    ]),
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
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    getFormBuilder() {
      let self = this;
      InstitutionsService.getInstitutionBuild().then(response => {
        let data = response.data
        if (data.status) {
          self.countries = data.data.countries
        }
      })
    },
    handlePageChange(value) {
      //get the data
      this.getData(value)
    },
    getData(page) {
      console.log("response get data")
      let institutionFilterData = new FormData();
      for (let key in this.filterData) {
        institutionFilterData.append(key, this.filterData[key] != null ? this.filterData[key] : "");
      }
      this.loadingData = true

      let self = this
      InstitutionsService.getInstitutions(page, institutionFilterData).then(response => {
        console.log(response)
        let data = response.data
        if (data.status) {
          self.tableData = data.data.items;
          self.totalRows = data.data.items_count;
          self.perPage = data.data.items_per_page;
          self.currentPage = data.data.current_page;
          self.loadingData = false
        }
      })
    },
    handleFilters() {
      //set the filtered country state
      this.setFilterCountry({countryCode:this.filterData.country_code})

      //get the data
      this.getData()
    },
    handleClearFilters() {
      this.filterData.country_code = null
      this.getData()
    },
    setFeatured(data) {
      let institution_code = data.institution_code
      let self = this
      InstitutionsService.setFeatured(institution_code).then(response => {
        if (response.data.status) {
          location.reload()
        } else {
        }
      })
    },
    delete_course(institution_code){
      let self = this;
      InstitutionsService.deleteInstitution(institution_code).then(response => {
        if (response.data.status) {
          this.handlePageChange(self.currentPage);
        } else {
          this.handlePageChange(self.currentPage);
        }
      });
    },
    publishonChangeEventHandler(institution_code, current_status){
      if (current_status==0){
        InstitutionsService.publishInstitution(institution_code).then(response => {
          if (response.data.status) {
            this.handlePageChange(this.currentPage);
          } else {

          }
        });
        
      }else{
        InstitutionsService.unpublishInstitution(institution_code).then(response => {
          if (response.data.status) {
            this.handlePageChange(this.currentPage);
          }
        });
      }
    },
    displaySuccess(message){
      this.FormSubmittedSuccess = true;
      this.FormSubmittedSuccessText = message

      //timout the success message
      setTimeout(function(scope) {
          scope.FormSubmittedSuccess = false;
      }, 7000, this);
    },
    displayError(message){
      this.FormSubmittedError = false;
      this.FormSubmittedErrorText = message

      //timout the success message
      setTimeout(function(scope) {
          scope.FormSubmittedError = false;
      }, 7000, this);
    },
    check_state(publish_state){
      if(publish_state==1){
        return true;
      }else{
        return false;
      }
    }
  },
  middleware: "authentication",
  name: "InstitutionList",
}
</script>

<style scoped>
.details_field{
    display: grid;
    grid-template-columns: 25% 70%;
    height:180px;
    overflow:hidden;
    border-radius:10px;
}
td a{
  color:#495057 !important;
}

.mainaction_wrapper{
  width:200px;
}
.action0_wrapper{
  width:45%;
  float:right;
  display: grid;
  grid-template-columns: 20% 10% 10% 32% 30%; 
  margin-bottom: 0 !important;
  vertical-alighn:bottom;
  padding:5px;
}

.action1_wrapper{
  display: grid;
  grid-template-columns: 32% 30% 55%; 
  margin-bottom: 0 !important;
  vertical-alighn:bottom;
}

.current_action0{
  padding-top:5px;
}

.selectpicker{
  width:150px;
  height:50px;
  border-radius:10px;
  font-size:15px;
}

.selectpicker:after{
   content:"";
    width:0;
    height:0;
    border:5px solid transparent;
    border-color:black transparent transparent transparent;
    position:absolute;
    top:9px;
    right:6px;
}

.publish{
  background:#1C3367 !important;
}

unpublish{
 
}
.avatar-xs{
  height:100%;
  width:100%;
  border-radius:6px;
}
.w-md{
  min-width: 25px !important;
  padding:0.27rem 0.45rem !important;
  height: 2.56rem;
  margin-left:5px;
}

.top_rowmt{
  margin-top:3px !important;
}

.bulk_confirm_bx0{
  width:100%;
  height:180px;
  margin-left:11px;
  border-radius:5px;
  border-style:solid;
  border-color:#cccccc;
  border-width:thin;
}

.bulk_confirm_bx0 p{
  padding-left:9px;
}

.corner_point{
  width:16.5px;
  height:16.5px;
  margin-top:0px;
  margin-left:-10px;
  margin-top:10px;
  transform: rotate(45deg);
  border-style:solid;
  border-color:#cccccc;
  border-width:thin;
  border-right:none;
  border-top:none;
  z-index:1000;
  background:#ffffff;
}

.table-centered td{
  padding:0.2rem !important;
}

.actions-edt-del-in01 {
    width:100%;
    padding: 5px;
    padding-left: 10px;
    background: #ffffff;
    border-radius: 4px;
    border-width: thin;
}

.modal-dialog{
  display:block !important;
}

.choose_notify{
  color:red;
}

.mr-3{
  margin-right:3px !important;
}

hr{
  margin-top:0.7rem;
  margin-bottom:0.2rem;
}

.s_all_o1{
  cursor:pointer;
  float:right
}
</style>

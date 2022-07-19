<script>
/**
 * Basic table component
 */
import SpecializationsService from "@/helpers/services/SpecializationsService";
import axios from "axios";

export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        }; 
    },
    data() {
        return {
            title: "Specializations",
            spec_items_list: [],
            countries:[],
            loadingData: true,
            loadingData_del: false,
            confirm_check: false,
            filterData: {
                country_code: null
            },
            items: [{
                    text: "Tables"
                },
                {
                    text: "Specializations",
                    active: true
                }
            ],
            successMsg: false,
            successMsgText: "",
            totalRows: 1,
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
            {
              key: "image",
              label:'',
              sortable: true
             },
            {
              key: "Name",
              label: 'Name',
              sortable: true
            },
              {
              key: "status",
              label: 'status',
              sortable: true
            },
            "",
            "action",
          ],

        };
    },
    middleware: "authentication",
    computed: {
      /**
       * Total no. of records
       */
      rows() {
        return this.totalRows;
      }
    },
    created(){

      this.getSpecializations();

    },
    methods: {
        async getSpecializations() {

         //start loading
          this.loadingdata = true;
          let response = await SpecializationsService.getlist(this);
          if (response.data.status) {
            this.spec_items_list = response.data.data.items;
            this.totalRows = response.data.data.items_count;
             var perpage_convert =parseInt(response.data.data.items_per_page);
            this.perPage = perpage_convert;
            this.currentPage = response.data.data.current_page

            //start loading
            this.loadingData = false;
          }
        },
        getSpecializationData(page) {
          let specializationFilterData = new FormData();
          for (let key in this.filterData) {
            specializationFilterData.append(key, this.filterData[key] != null ? this.filterData[key] : "");
          }

          this.loadingData = true;
          let self = this;
          SpecializationsService.getpage(page, specializationFilterData).then(response => {
            let response_data = response.data
            if (response_data.status) {
              self.spec_items_list = response_data.data.items;
              self.totalRows = response_data.data.items_count;
              self.perPage = response_data.data.items_per_page;
              self.currentPage = response_data.data.current_page
              self.loadingData = false
            }
          })
        },
        handlePageChange(value) {
          this.getSpecializationData(value)
        },
        deleteSpecialization(specialization_id){
          let self = this;
          this.loadingData_del = true;
          SpecializationsService.deleteSpecialization(specialization_id).then(response => {
            let response_data = response.data
            if (response_data.status) {
                self.getSpecializationData(self.currentPage);
                self.loadingData = false
            }
            this.loadingData_del= false;
          })
         },
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = filteredItems.length;
          this.currentPage = 1;
        },
        handleFilters() {
          //set the filtered country state
          this.setFilterCountry({countryCode:this.filterData.country_code})

          //get the data
          this.getSpecializationData()
        },
        handleClearFilters() {
          this.filterData.country_code = null
          this.getSpecializationData()
        },
        pop_confirm(id){
          this.confirm_check = id;
        },
        delete_cancel(){
          this.confirm_check = false;
        }


    },    

};
</script>
<template>
<div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    
                    <router-link class="btn btn-primary move-right waves-effect waves-light mb-3" to="/pathways/specializations/new-specialization">
                        <i class="mdi mdi-plus-box-multiple-outline mr-1"></i> Add new {{items[1].text}}
                    </router-link>
                      
                    <h4 class="card-title">{{items[1].text}}</h4>
                    <p class="card-title-desc">
                    </p>
                  

                    <div class="row mt-4">
                      <div class="card w-100">
                        <div class="card-body">
                          <h4 class="card-title">Filters</h4>
                          <div>
                            <b-form inline>
                              <b-form-select class="mr-3" v-model="filterData.country_code" value="filterData.country_code">
                                <b-form-select-option :value="null">Select a filter</b-form-select-option>
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
                        :items="spec_items_list"
                        :fields="fields"
                        responsive="sm"
                        :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc"
                        :filter="filter"
                        :filter-included-fields="filterOn" @filtered="onFiltered">

                        <template v-slot:cell(image)="data">

                              <div class="list-img-fill">
                              <img v-if="data.item.logo" :src="data.item.logo" alt class="avatar-xs rounded-circle mr-2"/>
                              <img v-else="data.item.temp_logo_image" :src="data.item.temp_logo_image" alt class="avatar-xs rounded-circle mr-2"/>
                              </div>

                        </template>

                        <template v-slot:cell(status)="data">

                              <nuxt-link
                                :to="{path:'/pathways/specializations/new-specialization', query:{id:data.item.id} }"
                                class="px-2  etd-active text-primary" v-b-tooltip.hover title="Click to de-activate" v-if="data.item.is_active===1" >
                                <span class="active-st-1"><i class="uil uil-check font-size-18"></i> active</span>            
                              </nuxt-link>

                              <nuxt-link
                                :to="{path:'javascript:void(0)'}"
                                class="px-2 etd-inactive text-primary" v-b-tooltip.hover title="Click to activate" v-else>  
                                <span class="active-st-0"> <i class="uil uil-check font-size-18"></i>in-active </span>                            
                              </nuxt-link>

                        </template>

                        <template v-slot:cell(name)="data">
                            <div class="">
                                <nuxt-link
                                  :to="{path:'/pathways/specializations/new-specialization', query:{id:data.item.id} }"
                                  class="px-2 etd-name text-primary "  v-b-tooltip.hover title="Open to show">
                                  {{data.item.name}}
                                </nuxt-link>

                            </div>
                        </template>

                        <template v-slot:cell(action)="data">
                          <ul class="list-inline mb-0"  id='del-section-anchor'>

                            <li class="list-inline-item">
                              <nuxt-link
                                :to="{path:'/pathways/specializations/new-specialization', query:{id:data.item.id} }"
                                class="px-2 etd-edit text-primary" v-b-tooltip.hover title="Edit"><i class="uil uil-pen font-size-18"></i>
                              </nuxt-link>
                            </li>

                            <li class="list-inline-item actions-edt-del-1" >
                              <a href="javascript:void(0);" :data-id="`${data.item.name}`" class="px-2 etd-delete" @click="pop_confirm(data.item.id)"
                                 :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                <i class="uil uil-trash-alt font-size-18"></i>
                              </a></br>

                            </li>

                          </ul>
                              <span class="`confirm-bx-${data.item.id}` actions-edt-del-in0"  v-if="confirm_check==data.item.id">

                                <span class="px-2 etd-cancel text-primary" v-b-tooltip.hover  @click="delete_cancel()" >
                                  <span class="active-st-1"><i class="uil uil-check font-size-18"></i> Cancel </span>            
                                </span></br></br>

                                <span class="px-2 etd-confirm text-primary" :data-delete="`${data.item.name}`" v-b-tooltip.hover @click="deleteSpecialization(data.item.id)">  
                                  <span class="active-st-0"> <i class="uil uil-trash-alt font-size-17"></i>
                                    confirm 
                                    <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_del"></b-spinner>
                                  </span>                            
                                </span>
                              </span>
                              <span v-else></span>

                        </template>



                      </b-table>

                    </div></br></br>
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
  </div>
</template>





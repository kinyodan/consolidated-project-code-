<script>
/**
 * Basic table component
 */
import Vue from 'vue'
import PathwaysService from "@/helpers/services/PathwaysService";
//import ToggleButton from 'vue-js-toggle-button'
//Vue.component('ToggleButton', ToggleButton)
import axios from "axios";



export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        };
    },
    data() {
        return {
            title: "Pathways",
            pathways_list: [],
            confirm_check: false,
            countries:[],
            loadingData: false,
            loadingData_del: false,
            filterData: {
                country_code: null
            },
            pageitems: [{
                    text: "Pathways list"
                },
                {
                    text: "Pathways",
                    active: true
                }
            ],
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
                /*{
                  key: "id",
                  label:'ID',
                  sortable: true
                },*/
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
    created() {
        this.getlistPathways()
    },
    computed: {
        rows() {
          return this.totalRows;
        }
    },
    methods: {
        async getlistPathways() {

            let self = this; 
            let token_i = localStorage.getItem('_token')
            const apiPostClient = axios.create({
              baseURL: `${process.env.APIURL}`,
              withCredentials: false, // This is the default
              headers: {
                'token': token_i,
                'locale': 'en',
                'Content-Type': 'multipart/form-data'
              }
            })

            this.loadingData= true;
            let response_pathways = apiPostClient.get( `/pathways/admin`)
            response_pathways.then(response => {
              console.log(response);
              self.pathways_list = response.data.data.items;
              self.totalRows = response.data.data.items_count;
              var perpage_convert =parseInt(response.data.data.items_per_page);
              self.perPage = perpage_convert;
              self.currentPage = response.data.data.current_page
              self.loadingData = false
              self.loadingData_del = false

            })
            //start loading
            this.loadingdata = false;
        },
        delete_this_pathway(id){
            
            this.pathways_list = [];
            this.loadingData_del = true;
            let self = this;

            let token = localStorage.getItem('_token')
            const apiPostClient = axios.create({
              baseURL: `${process.env.APIURL}`,
              withCredentials: false, // This is the default
              headers: {
                'token': token,
                'locale': 'en',
                'Content-Type': 'multipart/form-data'
              }
            })

            let post_request = apiPostClient.post(`/pathways/admin/${id}/delete`)

            this.getData(this.currentPage)

              if (post_request.data) {
                 //start loading

                  PathwaysService.getpathways_page(this.currentPage).then(response => {
                    let data = response.data
                    if (data.status) {
                    
                        self.pathways_list = response.data.data.items;
                        self.totalRows = response.data.data.items_count;
                        var perpage_convert =parseInt(response.data.data.items_per_page);
                        self.perPage = perpage_convert;
                        self.currentPage = response.data.data.current_page
                        self.loadingData = false

                    }
                  })

              }

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
          this.getData()
        },
        handleClearFilters() {
          this.filterData.country_code = null
          this.getData()
        },
        getData(page) {

          let pathwayFilterData = new FormData();
          for (let key in this.filterData) {
            pathwayFilterData.append(key, this.filterData[key] != null ? this.filterData[key] : "");
          }
          this.loadingData = true
          let self = this;

          PathwaysService.getpathways_page(page, pathwayFilterData).then(response => {
            let data = response.data
            if (data.status) {
                self.pathways_list = response.data.data.items;
                self.totalRows = response.data.data.items_count;
                var perpage_convert =parseInt(response.data.data.items_per_page);
                self.perPage = perpage_convert;
                self.currentPage = response.data.data.current_page
                self.loadingData = false
            }
          })

        },
        handlePageChange(value) {
          this.getData(value)
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
                
                      <router-link class="btn btn-primary move-right waves-effect waves-light mb-3"
                                   to="/pathways/pathways/new-pathway"><i
                        class="mdi mdi-plus-box-multiple-outline mr-1"></i> Add new {{pageitems[1].text}}
                      </router-link>
                      
                       <h4 class="card-title">{{pageitems[1].text}}</h4>
        
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
                      <div class="col-sm-12 col-md-6"></div>
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
                        :items="pathways_list"
                        :fields="fields"
                        responsive="sm"
                        :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc"
                        :filter="filter"
                        :filter-included-fields="filterOn" @filtered="onFiltered">

                        <template v-slot:cell(status)="data">
                              <nuxt-link
                                :to="{path:'javascript:void(0)'}"
                                class="px-2 text-primary" v-b-tooltip.hover title="Click to de-activate" v-if="data.item.is_active===1" >
                                <span class="active-st-1"><i class="uil uil-check font-size-18"></i> active</span>            
                              </nuxt-link>

                              <nuxt-link
                                :to="{path:'javascript:void(0)'}"
                                class="px-2 text-primary" v-b-tooltip.hover title="Click to activate" v-else>  
                                <span class="active-st-0"> <i class="uil uil-check font-size-18"></i>in-active </span>                            
                              </nuxt-link>

                        </template>

                        <template v-slot:cell(name)="data">
                              <div class="name-cont-1">

                                <img v-if="data.item.logo" :src="data.item.logo" alt class="avatar-xs rounded-circle mr-2"/>
                                <nuxt-link
                                  :to="{path:'/pathways/pathways/pathway', query:{id:data.item.id} }"
                                  class="px-2 text-primary" v-b-tooltip.hover title="Open to show">
                                  {{ data.item.name }}
                                </nuxt-link>
                                
                              </div>
                        </template>

                        <template v-slot:cell(action)="data">
                          <ul class="list-inline mb-0">

                            <li class="list-inline-item">
                              <nuxt-link
                                :to="{path:'/pathways/pathways/new-pathway', query:{eid:data.item.id} }"
                                class="px-2 text-primary" v-b-tooltip.hover title="Edit"><i class="uil uil-pen font-size-18"></i>
                              </nuxt-link>
                            </li>

                            <li class="list-inline-item actions-edt-del-1" >
                              <a href="javascript:void(0);" :data-id="`${data.item.name}`" class="px-2" @click="pop_confirm(data.item.id)"
                                 :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                <i class="uil uil-trash-alt font-size-18"></i>
                              </a></br>

                            </li>

                          </ul>
                              <span class="`confirm-bx-${data.item.id}` actions-edt-del-in0"  v-if="confirm_check==data.item.id">

                                <span class="px-2 text-primary" v-b-tooltip.hover title="Cancel delete"  @click="delete_cancel()" >
                                  <span class="active-st-1"><i class="uil uil-check font-size-18"></i> Cancel </span>            
                                </span></br></br>

                                <span class="px-2 text-primary" :data-delete="`${data.item.name}`" v-b-tooltip.hover title="Confirm delete" @click="delete_this_pathway(data.item.id)">  
                                  <span class="active-st-0"> <i class="uil uil-trash-alt font-size-17"></i>
                                    confirm 
                                    <b-spinner label="Loading..." style="width: 0.7rem; height: 0.7rem;" v-if="loadingData_del"></b-spinner>
                                  </span>                            
                                </span>

                              </span>
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




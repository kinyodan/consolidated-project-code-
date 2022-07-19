<script>

import Vue from 'vue'
import EligibilityTypeService from "@/helpers/services/EligibilityTypeService";
import ToggleButton from 'vue-js-toggle-button'


export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        };
    },
    components: {ToggleButton },
    data() {
        return {
              title: "Eligility type",
              eligibility_type_list: [],
              confirm_check: false,
              add_index: 0,
              countries: [],
              loadingData: false,
              filterData: {
                country_code: null
              }, 
              currentPage: 1,
              perPage: 10,
              totalRows: 1,
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
            items: [{
                    text: "Tables"
                },
                {
                    text: "Eligility types",
                    active: true
                }
            ]

        };
    },
    middleware: "authentication",
    created() {
        this.getEligibilityTypes()
    },
    computed: {
        rows() {
          return this.totalRows;
        }
    },
    methods: {
        async getEligibilityTypes() {

           //start loading
              this.$nextTick(() => {
                this.loadingData = true;
              });

            let response = await EligibilityTypeService.getlist(this);
            if (response.data.status) {
                this.eligibility_type_list = response.data.data;
                //start loading
                this.$nextTick(() => {
                  this.loadingData = false;
                });
            }

        },
        delete_me(id){

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

            let delete_request = apiPostClient.post(`/pathways/admin/employer-types/${id}/delete`)
            if (post_request.data) {
                //start loading
                this.$nextTick(() => {
                 location.reload;
                });
            }

            let response_elgb = EligibilityTypeService.delete(id);
            if (response_elgb.data) {
                //start loading
                this.$nextTick(() => {
                 location.reload;
                });
            }
        },
        onFiltered(filteredItems) {
          // Trigger pagination to update the number of buttons/pages due to filtering
          this.totalRows = 10;
          this.currentPage = 1;
        },
        handleClearFilters() {
          this.filterData.country_code = null
          this.getData()
        },
        handlePageChange(value) {
          //get the data
          this.getData(value)
        },
        getData(page) {
        // get data per page

          let elgtypFilterData = new FormData();
          for (let key in this.filterData) {
            elgtypFilterData.append(key, this.filterData[key] != null ? this.filterData[key] : "");
          }
          this.loadingData = true

          let self = this
          EligibilityTypeService.getlist(page, elgtypFilterData).then(response => {
            let data = response.data
            if (data.status) {
              self.eligibility_type_list = data.data.items;
              self.totalRows = data.data.items_count;
              self.perPage = data.data.items_per_page;
              self.currentPage = data.data.current_page;
              self.loadingData = false

            }
          })

        },

    },  
 

};
</script>


<template>
<div>

   <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                     
                      <!--<router-link class="btn btn-primary move-right waves-effect waves-light mb-3"
                                   to="/pathways/eligibility_types/new-eligibility-type"><i
                        class="mdi mdi-plus-box-multiple-outline mr-1"></i> Add new {{items[1].text}}
                      </router-link> -->

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
                                <b-form-select-option :value="null">Select a country</b-form-select-option>
                                <b-form-select-option v-for="country in countries" :key="country.name" :selected="selectedCountry && selectedCountry === country.iso_code" :value="country.iso_code">
                                  {{ country.name }}
                                </b-form-select-option>
                              </b-form-select>
                              <b-button variant="primary" class="mr-3"  :disabled="loadingData">Filter
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
                        :items="eligibility_type_list"
                        :fields="fields"
                        responsive="sm"
                        :sort-by.sync="sortBy"
                        :sort-desc.sync="sortDesc"
                        :filter="filter"
                        :filter-included-fields="filterOn" @filtered="onFiltered">

                        <template v-slot:cell(status)="data">

                              <nuxt-link
                                :to="{path:'#' }"
                                class="px-2 text-primary" 
                                v-b-tooltip.hover 
                                title="Click to de-activate" 
                                v-if="data.item.is_active===1" >

                                <span class="active-st-1"><i class="uil uil-check font-size-16"></i> active</span>           

                              </nuxt-link>

                              <nuxt-link
                                :to="{path:'#' }"
                                class="px-2 etd-active text-primary" 
                                v-b-tooltip.hover 
                                title="Click to activate" 
                                v-else >  
                                <span class="active-st-0"> <i class="uil uil-ban font-size-14"></i>in-active </span>                             
                              </nuxt-link>

                        </template>
                        
                        <template v-slot:cell(name)="data">
                              <nuxt-link
                                :to="{path:'#' }"
                                class="px-2 etd-name text-primary" v-b-tooltip.hover title="open to view">
                                {{ data.item.name }}
                              </nuxt-link>
                        </template>

                        <template v-slot:cell(action)="data">
                          <ul class="list-inline mb-0">

                          </ul>
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
    </div>
  </div>
</template>




























<script>
/**
 * Basic table component
 */
import SpecializationsService from "@/helpers/services/SpecializationsService";
import EmployersService from "@/helpers/services/EmployertypeService";

export default {
    head() {
        return {
            title: `${this.title} | Craydel Admin Dashboard`
        };
    },
    data() {
        return {
            pathwayId: "",
            title: "Pathway|",
            spec_items: [],
            employers: [],
            employer_countries: [],
            items: [{
                    text: "Tables"
                },
                {
                    text: "pathway name | ",
                    active: true
                }
            ]
        };
    },
    created(){
      this.set_params_id()
    },
    middleware: "authentication",
    methods: {
        async getSpecializations() {

           //start loading
            this.$nextTick(() => {
            });

            let response = await SpecializationsService.getlist(this);
            if (response.data.status) {
                this.spec_items = response.data.data;
                //start loading
                this.$nextTick(() => {
                });
            }
        },
        set_params_id(){
            let param_id = this.$route.query.code;
            this.pathwayId = param_id;
        },
        collapse(){
            this.$refs.collapsible.map(c => c.collapsed = true)
        },
        expand(){
            this.$refs.collapsible.map(c => c.collapsed = false)
        },
        getEmployerCountries(employer_id) {
            let response = EmployersService.getEmployerCountries(employer_id);
            if (response.data.status) {
                this.employer_countries = response.data.data;
            }
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
                                   to="/pathways/new-specilaizations"><i
                        class="mdi mdi-plus-box-multiple-outline mr-1"></i> Add new {{items[1].text}}
                      </router-link>

                    <h4 class="card-title">{{items[1].text}}</h4>
                    <p class="card-title-desc">
                    </p>

                    <div class="row mt-4">
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
                        :items="spec_items"
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

                        <template v-slot:cell(name)="data">
                          <a href="`/pathways/employer-type?emp_id=${data.item.id}`" class="text-body">{{ data.item.name }}</a>
                        </template>

                        <template v-slot:cell(action)="data">
                          <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                              <nuxt-link
                                :to="{path:'/pathways/new-employertype/', query:{id:data.item.id} }"
                                class="px-2 text-primary" v-b-tooltip.hover title="Edit"><i class="uil uil-pen font-size-18"></i>
                              </nuxt-link>
                            </li>
                            <li class="list-inline-item">
                              <a href="javascript:void(0);" class="px-2" @click="delete(data.item.id)"
                                 :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                <i class="uil uil-star font-size-18"></i>
                              </a>
                            </li>

                      <!--  <li class="list-inline-item">
                              <a href="javascript:void(0);" class="px-2" @click="setFeatured(data.item)"
                                 :class="{'text-success':data.item.is_featured}" v-b-tooltip.hover title="Set Featured">
                                <i class="uil uil-star font-size-18"></i>
                              </a>
                            </li>-->

                          </ul>
                        </template>

                      </b-table>

                    </div>



                    <div class="table-responsive mb-0">
                      <b-table
                        table-class="table table-centered datatable table-card-list"
                        thead-tr-class="bg-transparent"
                        :items="spec_items"
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

                        <template v-slot:cell(name)="data">
                          <a href="`/pathways/employer-type?emp_id=${data.item.id}`" class="text-body">{{ data.item.name }}</a>
                        </template>

                        <template v-slot:cell(action)="data">
                          <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                              <nuxt-link
                                :to="{path:'/pathways/new-employertype/', query:{id:data.item.id} }"
                                class="px-2 text-primary" v-b-tooltip.hover title="Edit"><i class="uil uil-pen font-size-18"></i>
                              </nuxt-link>
                            </li>
                            <li class="list-inline-item">
                              <a href="javascript:void(0);" class="px-2" @click="delete(data.item.id)"
                                 :class="{'text-success':data.item.name}" v-b-tooltip.hover title="Delete">
                                <i class="uil uil-star font-size-18"></i>
                              </a>
                            </li>

                      <!--  <li class="list-inline-item">
                              <a href="javascript:void(0);" class="px-2" @click="setFeatured(data.item)"
                                 :class="{'text-success':data.item.is_featured}" v-b-tooltip.hover title="Set Featured">
                                <i class="uil uil-star font-size-18"></i>
                              </a>
                            </li>-->

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




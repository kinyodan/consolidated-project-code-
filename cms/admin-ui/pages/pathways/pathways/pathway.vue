<script>
/**
 * Basic table component
 */
import SpecializationsService from "@/helpers/services/SpecializationsService";
import PathwaysService from "@/helpers/services/PathwaysService";

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
            title: "Pathway|",
            spec_items: [],
            currentPage: 1,
            pathway_specializations: [],
            specialization_data: [],
            current_pathway: [],
            current_specialization: [],
            elibigility_creteria: [],
            employer_countries_exist: false,
            academic_disciplines:[],
            employer_countries:[],
            querypathway:"",
            spec_employers_list: [],
            elg_check: 0,
            empl_check:0,
            acdm_check:0,
            country_symbol:"",
            disciplines: [],
            currentPage: 1,
            perPage: 10,
            pageOptions: [10, 25, 50, 100],
            filter: null,
            filterOn: [],
            sortBy: "id",
            sortDesc: false,
            loading_in_modal: false,
            sliderPrice: 800,
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
      this.set_params_id();
      this.get_pathway(this.$route.query.id);
      this.getSpecializations();
      this.getBuilder();
      this.getAcademicsBuilder();
      this.setcurrent_pathway();
    },
    middleware: "authentication",
    methods: {
        setcurrent_pathway(){
            this.querypathway = this.$route.query.id; 
        },
        async get_pathway(id) {
            let self = this; 

           //start loading
            this.$nextTick(() => {
            });

            //console.log("getting pathway data")
            let response = await PathwaysService.getPathwayData(this.$route.query.id);
            if (response.data) {
                self.current_pathway = response.data.data;
                
                //start loading
                this.$nextTick(() => {
                });
            }


        },
        async getSpecializations() {
            let self = this; 

           //start loading
            this.$nextTick(() => {
            });

            let response = await SpecializationsService.getPathwaySpecialization(this.$route.query.id);

            if (response.data.status) {
                self.pathway_specializations = response.data.data.items;
                //start loading
                this.$nextTick(() => {
                });
            }

        },
        set_params_id(){
            let param_id = this.$route.query.id;
            this.pathwayId = param_id;
            console.log(this.pathwayid)
        },
        get_spec_data(specialization_id){

           //start loading

             this.loading_in_modal = true;

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

            let response_pathway_specs = apiPostClient.get( `pathways/admin/specializations/${specialization_id}`)

              response_pathway_specs.then(response => {

                self.elibigility_creteria = response.data.data.eligibility;

                if(response.data.data.disciplines.length!==0){
                  self.disciplines =response.data.data.disciplines; 

                }else{
                  self.disciplines =[];

                }
                
                self.spec_employers_list = response.data.data.employers; 

                self.elg_check = self.elibigility_creteria.length;
                self.empl_check = self.spec_employers_list.length;
                self.acdm_check = self.disciplines.length;

                self.current_specialization = response.data.data.name
                self.totalRows = response.data.data.items_count;
                var perpage_convert =parseInt(response.data.data.items_per_page);
                self.perPage = perpage_convert;
                self.currentPage = response.data.data.current_page
                self.loadingData = false
                this.loading_in_modal = false;


              })

              //start loading
              this.loadingdata = false;
              //start loading

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
        set_current_specialization(specialization_name){

         this.current_specialization = specialization_name

        },
        getBuilder() {

            let self = this;
            SpecializationsService.buildSpecialization().then(response => {

                let data = response.data;
                if (data.status) {

                  self.employer_countries = data.data.countries;

                }

            });

        },
        getAcademicsBuilder() {

            let self = this;
            SpecializationsService.buildSpecialization().then(response => {

                  let data = response.data;
                  if (data.status) {

                    self.academic_disciplines = data.data.academic_disciplines;

                  }

            });

        },
        discipline_lookup(discipline_code){

            let self = this;
            let set_discipline = "";

            self.academic_disciplines.forEach(function (dsc) {

              if (dsc.discipline_code === discipline_code){

               set_discipline = dsc

              }

            });

            return set_discipline.discipline_name;

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
                    <router-link to="/pathways/pathways/pathways-list">
                        <span class="bck-to-0"><i class="uil uil-arrow-left font-size-20"></i> back</span>
                    </router-link> </br></br>


                    <a :href="`/pathways/specializations/new-specialization?pid=${this.$route.query.id}`" class="px-2 move-right" v-b-tooltip.hover title="Add specialization">
                     <i class="uil uil-plus-square font-size-20"></i>
                    </a></br>

                    <h5 class="mb-0">Specializations</h5>

                </div>

                <div class="p-4">
                    <div class="custom-accordion" v-for="specialization in pathway_specializations" :key='specialization.name'>
                        
                       
                        <a class="text-body font-weight-semibold pb-2 d-block" v-if="querypathway==specialization.pathway_id" href="javascript: void(0);" role="button" aria-expanded="false" @click="get_spec_data(specialization.id)" >
                            <i class="mdi mdi-chevron-up accor-down-icon text-primary mr-1"></i>
                            {{specialization.name}}
                        </a>

                        <!--
                        <b-collapse visible id="`categories-collapse${link.id`">
                            <div class="card p-2 border shadow-none">
                                <ul class="list-unstyled categories-list mb-0">
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-circle-medium mr-1"></i> 
                                            
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="#">
                                            <i class="mdi mdi-circle-medium mr-1"></i>
                                            
                                        </a>
                                    </li>

                                </ul> 

                            </div>
                        </b-collapse> -->
                    </div>

                </div>


                <div class="custom-accordion">

                </div>

            </div>
        </div>


        <div class="col-xl-9 col-lg-8" style="width:58%;right:0.5rem">
            <div class="card" style=";">

                <div class="card-body" >
                    <b-spinner class="m-2" variant="success" role="status" v-if="loading_in_modal"></b-spinner>

                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                
                                    <h5>

                                     <nuxt-link
                                     :to="{path:'/pathways/pathways/new-pathway', query:{id:this.$route.query.id} }"
                                      class="px-2 text-primary" v-b-tooltip.hover title="Edit">
                                       <i class="uil uil-pen font-size-18"></i>
                                     </nuxt-link> 

                                     <span v-for="pathway in current_pathway" :key='pathway.name'>
                                      <span class="pathway-nm-0"> {{pathway.name}} Pathway </span>
                                     </span>|
                                     <span v-if="current_specialization[0]!= null">
                                      {{current_specialization}}
                                     </span> details 

                                    </h5>

                                    <ol class="breadcrumb p-0 bg-transparent mb-2">
                                    </ol>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-inline float-md-right">
                                    <div class="search-box ml-2">
                                        <div class="position-relative">
                                            <input type="text" class="form-control bg-light border-light rounded" placeholder="Search..." />
                                            <i class="mdi mdi-magnify search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs nav-tabs-custom mt-3 mb-2 ecommerce-sortby-list">

                            <li class="nav-item">
                                <a class="nav-link active tablinks" @click="tabs_control($event,'eligibility-creteria')" href="javascript:void(0)">Eligibility creteria:</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tablinks" @click="tabs_control($event,'employers')" href="javascript:void(0)">Employers</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link tablinks" @click="tabs_control($event,'disciplines')" href="javascript:void(0)">Academic disciplines 
                                 </a>
                            </li>

                        </ul>


                        <div class="row">
                      
                           <div id="eligibility-creteria" class="tabcontent active">

                                <a class="bck-to-0 move-right" :href="`/pathways/eligibilities/new-eligibility?pid=${this.$route.query.id}`" role="button" aria-expanded="false" >
                                    <i class="mdi mdi-chevron-up accor-down-icon text-primary mr-1"></i>
                                    Add Eligibility
                                </a></br>

                                <div v-if="elg_check > 0">
                                  <span v-for="elibigility in elibigility_creteria" :key='elibigility.name'>
                                    <strong>
                                    <i class="uil uil-graduation-hat font-size-22" style="color:#579AE5;"></i>
                                    {{elibigility.name}}
                                    </strong>
                                    </br>
                                     <hr>
                                   </span></br>
                                </div>
                                  
                                <div v-else>
                                  <span >
                                   nothing to see here 
                                  </span></br>
                                </div>
                            </div>


                            <div id="employers" class="tabcontent">
                                    <a class="bck-to-0 move-right" :href="`/pathways/employers/employer?pid=${this.$route.query.id}`" role="button" aria-expanded="false" >
                                        <i class="mdi mdi-chevron-up accor-down-icon text-primary mr-1"></i>
                                        Add employer
                                    </a></br>

                                    <div>
                                      
                                        <div  v-for="emp_c in employer_countries">

                                            <span v-if="spec_employers_list[emp_c.name] && spec_employers_list[emp_c.name].length >0 ">
                                                
                                                <strong>
                                                 {{emp_c.name}} 
                                                </strong></br>

                                                <span v-for="employer in spec_employers_list[emp_c.name]" >
                                                 <i class="uil uil-suitcase-alt font-size-22" style="color:#579AE5;"></i>
                                                 {{employer.name}}</br>
                                                </span>
                                                <hr>

                                            </span></br>

                                        </div>  

                                      <div v-if="employer_countries_exist" class="country-exist-0">
                                       
                                      </div>
                                    </div>
                                      
                                <!--    <div >
                                      <span >
                                       nothing to see here 
                                      </span></br>
                                    </div> -->

                            </div>

                            <div id="disciplines" class="tabcontent">

                                   <div v-if="acdm_check > 0">
                                      <span v-for="discipline in disciplines" :key='discipline.academic_discipline_name'>
                                       <i class="uil uil-graduation-hat font-size-22" style="color:#579AE5;"></i>
                                       {{discipline_lookup(discipline.academic_discipline_name)}}
                                       <hr>
                                      </span></br>
                                    </div>
                                      
                                    <div v-else>
                                      <span >
                                       nothing to see here 
                                      </span></br>
                                    </div>

                            </div>

                         </div>   

                </div>
            </div>
        </div>
    </div>

  </div>     

</div>
</template>





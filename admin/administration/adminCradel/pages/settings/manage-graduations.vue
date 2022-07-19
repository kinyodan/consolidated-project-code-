<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <div class="wrapper-main">
    <template>
      <v-expansion-panels>
        <v-expansion-panel >
          <v-expansion-panel-header>
            <template>
              <div class="text-center btn-float-left" >
                <v-btn
                  rounded
                  color="primary"
                  dark
                >
                  Add Graduations
                </v-btn>
              </div>
            </template>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <template>
              <validation-observer
                ref="observer"
                v-slot="{ invalid }"
              >
                <form @submit.prevent="submit">
                  <validation-provider
                    v-slot="{ errors }"
                    name="Class Name"
                    rules="required|max:10"
                  >
                    <v-text-field
                      v-model="curriculum_name"
                      :counter="10"
                      :error-messages="errors"
                      label="Curriculum Name"
                      required
                    ></v-text-field>
                  </validation-provider>

                  <validation-provider
                    v-slot="{ errors }"
                    name="select"
                    rules="required"
                  >
                    <v-select
                      v-model="country_code"
                      :items="items"
                      :error-messages="errors"
                      label="Select"
                      data-vv-name="select"
                      required
                    ></v-select>
                  </validation-provider>

                  <v-btn
                    class="mr-4"
                    type="submit"
                    :disabled="invalid"
                  >
                    submit
                  </v-btn>
                  <v-btn @click="clear">
                    clear
                  </v-btn>
                </form>
              </validation-observer>
            </template>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </template>
    <v-data-table
      :headers="headers"
      :items="listItems"
      item-key="name"
      class="elevation-1"
      :search="search"
      :custom-filter="filterOnlyCapsText"
    >
      <template v-slot:top>
        <v-text-field
          v-model="search"
          label="Search (UPPER CASE ONLY)"
          class="mx-4"
        ></v-text-field>
      </template>
      <template v-slot:body.append>
        <tr>
          <td></td>
          <td>
            <v-text-field
              v-model="page"
              type="number"
              label="Less than"
            ></v-text-field>
          </td>
          <td colspan="4"></td>
        </tr>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import variousCountryListFormats from '@/variousCountryListFormats'
import {required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'
import GraduationsService from "@/services/GraduationsService";


export default {
  name: 'graduations',
  layout:'Default',
  head(){},
  components: {
  },
  created() {
    this.setCountryList()
    this.getGraduationYearsList()
  },
  data() {
    return {
      curriculum_name: "",
      country_code: null,
      curriculum_code: null,
      dataList:[],
      successMessage:"",
      year_id:null,
      submitSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      is_global: false,
      countries: [],
      page:1,
      search:null,
      headers:[
        {}
      ],
      listItems:[],
    }
  },
  methods: {
    setCountryList() {
      this.countries = variousCountryListFormats.setCountries()
    },
    async getGraduationYearsList(){
      console.log("submitted ")
      let response = await GraduationsService.listGraduations()
      if(response.status){
        this.dataList=response.data
        this.successMessage = response.message
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async updateGraduationYear(){
      let formData =  new FormData()
      formData.append("year", this.year)
      formData.append("description", this.description)
      formData.append("is_global", this.is_global)

      let response =await GraduationsService.updateGraduations(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async deleteGraduationYears(year_id){
      let response =await GraduationsService.deleteGraduations(year_id)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async submitForm(){
      console.log("submitted ")
      let formData =  new FormData()
      formData.append("year", this.year)
      formData.append("description", this.description)
      formData.append("is_global", this.is_global)

      let response =await GraduationsService.addGraduations(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    filterOnlyCapsText(){

    }
  }
}
</script>
<style scoped>
.wrapper-main{
  width:60% !important;
  margin-left: 15%;
  margin-top:4%;
}
</style>

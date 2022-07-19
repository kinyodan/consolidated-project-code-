<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
  <div class="wrapper-main">
    <template>
      <v-expansion-panels>
        <v-expansion-panel >
          <v-expansion-panel-header>
            <h3> Click to Add Streams</h3>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <template>
              <validation-observer
                ref="observer"
                v-slot="{ invalid }"
              >
                <form @submit.prevent="submitForm">
                  <validation-provider
                    v-slot="{ errors }"
                    name="Class Name"
                    rules="required|max:10"
                  >
                    <v-text-field
                      v-model="stream_name"
                      :counter="10"
                      :error-messages="errors"
                      label="Class Name"
                      required
                    ></v-text-field>
                  </validation-provider>

                  <validation-provider
                    v-slot="{ errors }"
                    name="select"
                    rules="required"
                  >
                    <v-select
                      v-model="school_id"
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
import CurriculumService from "@/services/CurriculumService";
import {required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'

export default {
  name: 'manage-streams',
  layout:'Default',
  head(){},
  created() {
    this.setCountryList()
  },
  data() {
    return {
      stream_name: "",
      page:1,
      school_id: null,
      submitSuccess:false,
      successMessage:"",
      responseError:false,
      responseErrorMessage:"",
      is_global: false,
      countries: [],
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
    async submitForm(){
      console.log("submitted ")
      let formData =  new FormData()
      formData.append("school_id", this.school_id)
      formData.append("stream_name", this.stream_name)
      formData.append("is_global", this.is_global)

      let response = await CurriculumService.addCurriculum(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
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

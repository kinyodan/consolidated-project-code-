<template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <div class="wrapper-main">
      <template>
        <v-expansion-panels>
          <v-expansion-panel >
            <v-expansion-panel-header>
              <template>
                <div class="text-center btn-float-left">
                  <v-btn
                    rounded
                    color="primary"
                    dark
                  >
                    Add Classes
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
                        v-model="class_name"
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
                        v-model="curriculum_id"
                        :items="items"
                        :error-messages="errors"
                        label="Select Curriculum"
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
import ClassesService from "@/services/ClassesService";
import { ValidationObserver } from 'vee-validate';
import { ValidationProvider } from 'vee-validate';


export default {
  name: 'Classes',
  layout:'Default',
  components:{ValidationObserver,ValidationProvider},
  head(){},
  created() {
    this.setCountryList()
    },
  data() {
    return {
      headers:[
        {}
      ],
      listItems:[],
      class_name: "",
      class_id:null,
      curriculum_id: null,
      status: null,
      submitSuccess:false,
      successMessage:"",
      responseError:false,
      responseErrorMessage:"",
      is_global: false,
      countries: [],
      page:1,
      search:null,
    }
  },
  methods: {
    setCountryList() {
      this.countries = variousCountryListFormats.setCountries()
    },

    async submitForm(){
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError=true
        this.validationErrorMessage="kindly fill required field "

      }else {
        this.submitSuccess = true
        this.successMessage = "Class submitted successfully"

        let formData =  new FormData()
        formData.append("class_name", this.class_name)
        formData.append("curriculum_id", this.curriculum_id)
        let response = await ClassesService.addClass(formData)
        if (response.status) {
          this.successMessage = response.message
          this.submitSuccess = true
        } else {
          this.responseError = true
          this.responseErrorMessage = response.message
        }
      }
    },
    async updateClass(){
      let formData =  new FormData()
      formData.append("class_name", this.class_name)
      formData.append("curriculum_id", this.class_id)
      let response =await ClassesService.updateClass(this.class_id,formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async deleteClass(curriculum_id){
      let response =await ClassesService.deleteClass(curriculum_id,formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async showClass(curriculum_id){
      let response =await ClassesService.showClass(curriculum_id)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async listClasses(){
      let response =await ClassesService.listClasses()
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

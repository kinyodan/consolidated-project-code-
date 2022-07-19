  <template xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <div class="wrapper-main">
      <template>
        <v-expansion-panels>
          <v-expansion-panel >
            <v-expansion-panel-header>
              <template>
                <div class="text-center">
                  <v-btn
                    rounded
                    color="primary"
                    dark
                  >
                    Add Curriculum
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
  name: 'curriculum',
  layout:'Default',
  head(){},
  created(){
   this.setCountryList()
  },
  data() {
    return {
      curriculum_name: "",
      country_code:null,
      curriculum_code:null,
      successMessage:"",
      submitSuccess:false,
      responseError:false,
      responseErrorMessage:"",
      validationError:false,
      validationErrorMessage:"",
      is_global:false,
      countries:[],
      page:1,
      search:null,
      headers:[
        {}
      ],
      listItems:[],
    }
  },
  validations () {
    return {
      curriculum_name: { required },
      country_code: { required },
      curriculum_code:{required},
    }
  },
  methods: {
    setCountryList(){
      this.countries=variousCountryListFormats.setCountries()
    },
    async submitForm(){
      console.log(this.$v.$touch)
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.validationError=true
        this.validationErrorMessage="kindly fill required field "

      }else {
        console.log("submitted ")
        this.submitSuccess = true
        this.successMessage = "Curriculum submitted successfully"

        let formData = new FormData()
        formData.append("curriculum_name", this.curriculum_name)
        formData.append("curriculum_code", this.curriculum_code)
        formData.append("country_code", this.country_code)
        formData.append("is_global", this.is_global)
        let response = await CurriculumService.addCurriculum(formData)
        if (response.status) {
          this.successMessage = response.message
          this.submitSuccess = true
        } else {
          this.responseError = true
          this.responseErrorMessage = response.message
        }
      }
    },
    async updateCurriculum(){
      console.log("submitted ")
      let formData =  new FormData()
      formData.append("curriculum_name", this.curriculum_name)
      formData.append("curriculum_code", this.curriculum_code)
      formData.append("country_code", this.country_code)
      formData.append("is_global", this.is_global)

      let response =await CurriculumService.updateCurriculum(this.curriculum_id,formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async deleteCurriculum(curriculum_id){
      let response =await CurriculumService.deleteCurriculum(curriculum_id,formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async showCurriculum(curriculum_id){
      let response =await CurriculumService.showCurriculum(curriculum_id)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }else{
        this.responseError=true
        this.responseErrorMessage=response.message
      }
    },
    async listCurriculums(){
      let response =await CurriculumService.listCurriculum()
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

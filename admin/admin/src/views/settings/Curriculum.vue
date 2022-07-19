<template>
  <CCard style="width: 98%">
    <CAccordion>
      <CAccordionItem :item-key="1">
        <CAccordionHeader>
          <CButton color="success" variant="outline">Add Curriculum</CButton>
        </CAccordionHeader>
        <CAccordionBody>
          <CCard style="width: 70%">
            <CCardBody>
              <CContainer>
                <CForm  @submit.prevent="submitForm" >
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">Curriculum Name</CFormLabel>
                    <CFormInput type="text" id="exampleFormControlInput1" v-model="curriculum_name" placeholder="Enter Curriculum Name"/>
                  </div>
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">Country</CFormLabel>
                    <CFormSelect
                      aria-label="Default select example"
                      :options="countries"  v-model="country_code">
                    </CFormSelect>
                  </div>
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">Curriculum code</CFormLabel>
                    <CFormInput type="email" id="exampleFormControlInput1" placeholder="Curriculum Code"/>
                  </div>
                  <div class="col-auto">
                    <CButton type="submit" color="primary">Button</CButton>
                  </div>
                </CForm>
                <CAlert v-if="submitSuccess" color="success">{{successMessage}}!</CAlert>
                <CAlert  v-if="responseError" color="danger">{{responseErrorMessage}}}!</CAlert>
                <CAlert  v-if="validationError" color="danger">{{validationErrorMessage}}}!</CAlert>

              </CContainer>
            </CCardBody>
          </CCard>
        </CAccordionBody>
      </CAccordionItem>
    </CAccordion>

    <CCardBody>
      <CContainer>
      <CTable striped>
        <CTableHead>
          <CTableRow>
            <CTableHeaderCell scope="col">#</CTableHeaderCell>
            <CTableHeaderCell scope="col">Class</CTableHeaderCell>
            <CTableHeaderCell scope="col">Heading</CTableHeaderCell>
            <CTableHeaderCell scope="col">Heading</CTableHeaderCell>
          </CTableRow>
        </CTableHead>
        <CTableBody>
          <CTableRow>
            <CTableHeaderCell scope="row">1</CTableHeaderCell>
            <CTableDataCell>Mark</CTableDataCell>
            <CTableDataCell>Otto</CTableDataCell>
            <CTableDataCell>@mdo</CTableDataCell>
          </CTableRow>
          <CTableRow>
            <CTableHeaderCell scope="row">2</CTableHeaderCell>
            <CTableDataCell>Jacob</CTableDataCell>
            <CTableDataCell>Thornton</CTableDataCell>
            <CTableDataCell>@fat</CTableDataCell>
          </CTableRow>
          <CTableRow>
            <CTableHeaderCell scope="row">3</CTableHeaderCell>
            <CTableDataCell colspan="2">Larry the Bird</CTableDataCell>
            <CTableDataCell>@twitter</CTableDataCell>
          </CTableRow>
        </CTableBody>
      </CTable>
      </CContainer>
    </CCardBody>

  </CCard>
</template>

<script>
import CAlert, { CForm } from '@coreui/vue';
import { CFormLabel } from '@coreui/vue';
import { CFormInput } from '@coreui/vue';
import { CContainer } from '@coreui/vue';
import { CTable } from '@coreui/vue';
import { CTableHead } from '@coreui/vue';
import { CTableRow } from '@coreui/vue';
import { CTableHeaderCell } from '@coreui/vue';
import { CTableBody } from '@coreui/vue';
import variousCountryListFormats from '@/variousCountryListFormats'
import CurriculumService from "@/services/CurriculumService";
import {required} from 'vuelidate/lib/validators'
import Vue from 'vue'
import Vuelidate from 'vuelidate'


export default {
  name: 'curriculum',
  components: { CForm,CFormLabel,CFormInput,CContainer,CTable ,CTableHead,CTableRow,CTableHeaderCell,CTableBody,CAlert},
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
    }
  }
}
</script>

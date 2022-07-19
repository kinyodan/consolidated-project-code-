<template>
  <CCard style="width: 98%">
    <CAccordion>
      <CAccordionItem :item-key="1">
        <CAccordionHeader>
          <CButton color="success" variant="outline">Add Classes</CButton>
        </CAccordionHeader>
        <CAccordionBody>
          <CCard style="width: 70%">
            <CCardBody>
              <CContainer>
                <CForm  @submit.prevent="submitForm" >
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">Class Name</CFormLabel>
                    <CFormInput type="text" id="exampleFormControlInput1" v-model="class_name" placeholder="Enter Class Name"/>
                  </div>
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">Curriculum</CFormLabel>
                    <CFormSelect
                      aria-label="Select Curriculum "
                      :options="[]"  v-model="curriculum_id">
                    </CFormSelect>
                  </div>
                  <div class="col-auto">
                    <CButton type="submit" color="primary">Button</CButton>
                  </div>
                </CForm>
                <CAlert v-if="submitSuccess" color="success">{{successMessage}}!</CAlert>
                <CAlert  v-if="responseError" color="danger">{{responseErrorMessage}}}!</CAlert>
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
              <CTableHeaderCell scope="col">Class Name</CTableHeaderCell>
              <CTableHeaderCell scope="col">School</CTableHeaderCell>
              <CTableHeaderCell scope="col">Status</CTableHeaderCell>
            </CTableRow>
          </CTableHead>
          <CTableBody>
            <CTableRow>
              <CTableHeaderCell scope="row">1</CTableHeaderCell>
              <CTableDataCell>Form 1a</CTableDataCell>
              <CTableDataCell>Mark secondary school</CTableDataCell>
              <CTableDataCell>enrolled </CTableDataCell>
            </CTableRow>
            <CTableRow>
              <CTableHeaderCell scope="row">1</CTableHeaderCell>
              <CTableDataCell>Form 1b</CTableDataCell>
              <CTableDataCell>Mark secondary school</CTableDataCell>
              <CTableDataCell>enrolled </CTableDataCell>
            </CTableRow>
            <CTableRow>
              <CTableHeaderCell scope="row">1</CTableHeaderCell>
              <CTableDataCell>Form 1c</CTableDataCell>
              <CTableDataCell>Mark secondary school</CTableDataCell>
              <CTableDataCell>enrolled </CTableDataCell>
            </CTableRow>
          </CTableBody>
        </CTable>
      </CContainer>
    </CCardBody>
  </CCard>
</template>

<script>
import CAlert, {CForm} from '@coreui/vue';
import {CFormLabel} from '@coreui/vue';
import {CFormInput} from '@coreui/vue';
import {CContainer} from '@coreui/vue';
import {useStore} from "vuex";
import {CTable} from '@coreui/vue';
import {CTableHead} from '@coreui/vue';
import {CTableRow} from '@coreui/vue';
import {CTableHeaderCell} from '@coreui/vue';
import {CTableBody} from '@coreui/vue';
import variousCountryListFormats from '@/variousCountryListFormats'
import ClassesService from "@/services/ClassesService";
export default {
  name: 'Classes',
  components: {
    CForm,
    CFormLabel,
    CFormInput,
    CContainer,
    CTable,
    CTableHead,
    CTableRow,
    CTableHeaderCell,
    CTableBody,
    CAlert
  },
  created() {
    this.setCountryList()
  },
  data() {
    return {
      class_name: "",
      curriculum_id: null,
      status: null,
      submitSuccess:false,
      successMessage:"",
      responseError:false,
      responseErrorMessage:"",
      is_global: false,
      countries: [],
    }
  },
  methods: {
    setCountryList() {
      this.countries = variousCountryListFormats.setCountries()
    },
    async submitForm(){
      console.log("submitted ")
      let formData =  new FormData()
      formData.append("class_name", this.class_name)
      formData.append("curriculum_id", this.curriculum_id)
      let response = await ClassesService.addClass(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }
    }
  }
}
</script>

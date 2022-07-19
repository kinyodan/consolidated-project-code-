<template>
  <CCard style="width: 98%">
    <CAccordion>
      <CAccordionItem :item-key="1">
        <CAccordionHeader>
          <CButton color="success" variant="outline">Add Streams</CButton>
        </CAccordionHeader>
        <CAccordionBody>
          <CCard style="width: 70%">
            <CCardBody>
              <CContainer>
                <CForm  @submit.prevent="submitForm" >
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">Stream Name</CFormLabel>
                    <CFormInput type="text" id="exampleFormControlInput1" v-model="stream_name" placeholder="Enter Stream Name"/>
                  </div>
                  <div class="mb-3">
                    <CFormLabel for="exampleFormControlInput1">School</CFormLabel>
                    <CFormSelect
                      aria-label="Default select example"
                      :options="[]"  v-model="school_id">
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
import CurriculumService from "@/services/CurriculumService";

export default {
  name: 'manage-streams',
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
      stream_name: "",
      school_id: null,
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
      formData.append("school_id", this.school_id)
      formData.append("stream_name", this.stream_name)
      formData.append("is_global", this.is_global)

      let response = await CurriculumService.addCurriculum(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
      }
    }
  }
}
</script>

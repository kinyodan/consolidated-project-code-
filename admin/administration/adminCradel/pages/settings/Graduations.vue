<!--<template>-->
<!--  <CCard style="width: 98%">-->
<!--    <CCardBody>-->
<!--      <CContainer>-->
<!--        <CAlert v-if="submitSuccess" color="success">{{successMessage}}!</CAlert>-->
<!--        <CAlert  v-if="responseError" color="danger">{{responseErrorMessage}}}!</CAlert>-->

<!--        <CTable striped>-->
<!--          <CTableHead>-->
<!--            <CTableRow>-->
<!--              <CTableHeaderCell scope="col">#</CTableHeaderCell>-->
<!--              <CTableHeaderCell scope="col">Class</CTableHeaderCell>-->
<!--              <CTableHeaderCell scope="col">Heading</CTableHeaderCell>-->
<!--              <CTableHeaderCell scope="col">Heading</CTableHeaderCell>-->
<!--            </CTableRow>-->
<!--          </CTableHead>-->
<!--          <CTableBody>-->
<!--            <CTableRow>-->
<!--              <CTableHeaderCell scope="row">1</CTableHeaderCell>-->
<!--              <CTableDataCell>Mark</CTableDataCell>-->
<!--              <CTableDataCell>Otto</CTableDataCell>-->
<!--              <CTableDataCell>@mdo</CTableDataCell>-->
<!--            </CTableRow>-->
<!--            <CTableRow>-->
<!--              <CTableHeaderCell scope="row">2</CTableHeaderCell>-->
<!--              <CTableDataCell>Jacob</CTableDataCell>-->
<!--              <CTableDataCell>Thornton</CTableDataCell>-->
<!--              <CTableDataCell>@fat</CTableDataCell>-->
<!--            </CTableRow>-->
<!--            <CTableRow>-->
<!--              <CTableHeaderCell scope="row">3</CTableHeaderCell>-->
<!--              <CTableDataCell colspan="2">Larry the Bird</CTableDataCell>-->
<!--              <CTableDataCell>@twitter</CTableDataCell>-->
<!--            </CTableRow>-->
<!--          </CTableBody>-->
<!--        </CTable>-->
<!--      </CContainer>-->
<!--    </CCardBody>-->

<!--  </CCard>-->
<!--</template>-->

<!--<script>-->
<!--import {CForm} from '@coreui/vue';-->
<!--import {CFormLabel} from '@coreui/vue';-->
<!--import {CFormInput} from '@coreui/vue';-->
<!--import {CContainer} from '@coreui/vue';-->
<!--import {useStore} from "vuex";-->
<!--import {CTable} from '@coreui/vue';-->
<!--import {CTableHead} from '@coreui/vue';-->
<!--import {CTableRow} from '@coreui/vue';-->
<!--import {CTableHeaderCell} from '@coreui/vue';-->
<!--import {CTableBody} from '@coreui/vue';-->
<!--import {CAlert} from '@coreui/vue';-->
<!--import variousCountryListFormats from '@/variousCountryListFormats'-->
<!--import CurriculumService from "@/services/CurriculumService";-->
<!--import GraduationsService from "@/services/GraduationsService";-->

<!--export default {-->
<!--  name: 'graduations',-->
<!--  components: {-->
<!--    CContainer,-->
<!--    CTable,-->
<!--    CTableHead,-->
<!--    CTableRow,-->
<!--    CTableHeaderCell,-->
<!--    CTableBody,-->
<!--    CAlert-->
<!--  },-->
<!--  created() {-->
<!--    this.setCountryList()-->
<!--    this.getGraduationYearsList()-->
<!--  },-->
<!--  data() {-->
<!--    return {-->
<!--      curriculum_name: "",-->
<!--      country_code: null,-->
<!--      curriculum_code: null,-->
<!--      dataList:[],-->
<!--      successMessage:"",-->
<!--      year_id:null,-->
<!--      submitSuccess:false,-->
<!--      responseError:false,-->
<!--      responseErrorMessage:"",-->
<!--      is_global: false,-->
<!--      countries: [],-->
<!--    }-->
<!--  },-->
<!--  methods: {-->
<!--    setCountryList() {-->
<!--      this.countries = variousCountryListFormats.setCountries()-->
<!--    },-->
<!--    async getGraduationYearsList(){-->
<!--      console.log("submitted ")-->
<!--      let response = await GraduationsService.listGraduations()-->
<!--      if(response.status){-->
<!--        this.dataList=response.data-->
<!--        this.successMessage = response.message-->
<!--      }else{-->
<!--        this.responseError=true-->
<!--        this.responseErrorMessage=response.message-->
<!--      }-->
<!--    },-->
<!--    async updateGraduationYear(){-->
<!--      let formData =  new FormData()-->
<!--      formData.append("year", this.year)-->
<!--      formData.append("description", this.description)-->
<!--      formData.append("is_global", this.is_global)-->

<!--      let response =await GraduationsService.updateGraduations(formData)-->
<!--      if(response.status){-->
<!--        this.successMessage = response.message-->
<!--        this.submitSuccess=true-->
<!--      }else{-->
<!--        this.responseError=true-->
<!--        this.responseErrorMessage=response.message-->
<!--      }-->
<!--    },-->
<!--    async deleteGraduationYears(year_id){-->
<!--      let response =await GraduationsService.deleteGraduations(year_id)-->
<!--      if(response.status){-->
<!--        this.successMessage = response.message-->
<!--        this.submitSuccess=true-->
<!--      }else{-->
<!--        this.responseError=true-->
<!--        this.responseErrorMessage=response.message-->
<!--      }-->
<!--    },-->
<!--    async submitForm(){-->
<!--      console.log("submitted ")-->
<!--      let formData =  new FormData()-->
<!--      formData.append("year", this.year)-->
<!--      formData.append("description", this.description)-->
<!--      formData.append("is_global", this.is_global)-->

<!--      let response =await GraduationsService.addGraduations(formData)-->
<!--      if(response.status){-->
<!--        this.successMessage = response.message-->
<!--        this.submitSuccess=true-->
<!--      }else{-->
<!--        this.responseError=true-->
<!--        this.responseErrorMessage=response.message-->
<!--      }-->
<!--    }-->
<!--  }-->
<!--}-->
<!--</script>-->

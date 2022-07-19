<template>
  <div>
    <template>
      <v-row no-gutters class="mb-3">
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-spacer></v-spacer>

        <v-col class="" >
          <v-btn
              outlined
              color="green"
              height="25"
              id="mSchool"
              :disabled="panels.mSchool"
              @click="toggleDisplayPanels('mSchool')"
          >
            School
          </v-btn>
        </v-col>

        <v-col class="">
          <v-btn
              outlined
              color="green"
              height="25"
              id="mLicenses"
              :disabled="panels.mLicenses"
              @click="toggleDisplayPanels('mLicenses')"
          >
            licences
          </v-btn>
        </v-col>
        <v-col class=" ">
          <v-btn
              outlined
              color="green"
              height="25"
              id="mAdmins"
              :disabled="panels.mAdmins"
              @click="toggleDisplayPanels('mAdmins')"
          >
            Admins
          </v-btn>
        </v-col>
        <v-col class="">
          <v-btn
              outlined
              color="green"
              height="25"
              id="mStudents"
              :disabled="panels.mStudents"
              @click="toggleDisplayPanels('mStudents')"
          >
            Students
          </v-btn>
        </v-col>
      </v-row>
      <v-card v-if="panels.mSchool">
        <SchoolPanel :listItems="listItems" :listItems_count="listItems_count" :schoolDetails="schoolDetails"></SchoolPanel>
      </v-card>
      <v-card v-if="panels.mLicenses">
        <ManageLicenses  :schoolDetails="schoolDetails"></ManageLicenses>
      </v-card>
      <v-card v-if="panels.mAdmins">
        <ManageAdmins  :schoolDetails="schoolDetails"></ManageAdmins>
      </v-card>
      <v-card v-if="panels.mStudents">
        <ManageStudents  :schoolDetails="schoolDetails"></ManageStudents>
      </v-card>
    </template>
  </div>
</template>

<script>

import CurriculumService from "@/services/CurriculumService";
import variousCountryListFormats from "@/variousCountryListFormats";
import BankDetailsForm from "@/components/schools/BankDetailsForm"
import BankDetailsList from "@/components/schools/BankDetailsList";
import BankDetailsService from "@/services/BankDetailsService";
import Vue from "vue";
import SchoolPanel from "@/components/schools/SchoolPanel";
import  ManageLicenses from "@/components/schools/ManageLicenses";
import ManageAdmins from "@/components/schools/ManageAdmins"
import ManageStudents from "@/components/schools/ManageStudents"

Vue.use(require('vue-moment'));

export default {
  name: "ShowSchoolDetails",
  props: [
    'school_id',
    'schoolDetails',
    'bank_listItems'
  ],
  components:{
    SchoolPanel,
    BankDetailsList,
    BankDetailsForm,
    ManageLicenses,
    ManageAdmins,
    ManageStudents
  },
  data() {
    return {
      logoUrl: "https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/Mutira-Girls-High-School-logo.png",
      tabs:null,
      listItems: this.bank_listItems||[],
      listItems_count:0,
      humanizedTime:null,
      panels:{
      mSchool:true,
      mLicenses:false,
      mAdmins:false,
      mStudents:false,
     }
    }
  },
  created() {
    this.$nuxt.$on('refreshBankDetailsList', ($event) => this.listBankDetails(this.schoolDetails.school_code))
  },
  mounted() {
    this.listBankDetails(this.schoolDetails.school_code)
  },
  async fetch(){
    await this.listBankDetails(this.schoolDetails.school_code)
  },
  methods:{
    toggleDisplayPanels(index){
      let pnl =Object.keys(this.panels)
      for (let i = 0; i < pnl.length; i++) {
        this.panels[pnl[i]]=false
      }
      this.panels[index]=true
      // document.getElementById(index).disabled=true;
    },
    humanizeTime(itemTime){
     let time =  new Date(itemTime)
      this.humanizedTime=time.toTimeString()
      return true
    },
    async listCurriculums() {
      let response = await CurriculumService.listCurriculum();
      if (response.data.status){
        this.curriculums=response.data.data.items
      }
    },
    setCountryList(){
      this.countries=variousCountryListFormats.setCountryWithCodeList()
    },
    loadSchoolLogo(){
    },
    async listBankDetails() {
      this.loading = true
      let response = await BankDetailsService.listBankDetails(this.schoolDetails.school_code)
      if (response.data.status) {
        this.listItems = response.data.data.items
        this.listItems_count=response.data.data.items.length
        this.successMessage = response.data.message
        this.submitSuccess = true
        this.loading = false
      } else {
        this.responseError = true
        this.responseErrorMessage = response.data.message
      }
    },
  }
}
</script>

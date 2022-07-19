<template>
    <div style="width: 800px;" class="pa-4">
          <template>

              <div class="fieldset">
                <div class="inline-field">
                </div>
              </div>
              <div>
                </br>
              </div>
              <v-row v-if="studentDetails">
                <v-col
                  cols="12"
                >
                  <strong> Student name: </strong> {{ studentDetails.student_name }}
                </v-col>
                <v-col
                  cols="12"
                >
                  <strong> School </strong>: {{studentDetails.school.school_name}}
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{studentDetails.curriculum.curriculum_code}}
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                 <strong> Nationality:</strong> {{studentDetails.nationality}}
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                  <strong>Student phone:</strong> {{studentDetails.student_phone}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Gender: </strong> {{studentDetails.gender}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Date of birth:</strong>  {{studentDetails.date_of_birth}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Student Assessment Code:</strong>  {{studentDetails.student_assessment_code}}
                </v-col>

                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{getValueString(studentDetails.is_invite_sent)}}
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{getValueString(studentDetails.is_account_activated)}}
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{getValueString(studentDetails.has_done_career_counselling)}}
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{getValueString(studentDetails.has_subscribed_for_assessmen)}}
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{getValueString(studentDetails.has_applied_for_course)}}
                </v-col>
                <v-col
                    cols="12"
                    md="6"
                >
                  <strong> Curriculum:</strong> {{getValueString(studentDetails.student_assessment_code)}}
                </v-col>

                <v-col cols="12" md="6">
                  <strong> guardian_student_relationship: </strong> {{studentDetails.guardian_student_relationship}}
                </v-col>

                <v-col cols="12" md="6">
                  <strong> Guardian name: </strong> {{studentDetails.guardian_name}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Guardian email: </strong> {{studentDetails.guardian_email}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> guardian_mobile_number: </strong> {{studentDetails.guardian_mobile_number}}
                </v-col>

                <v-col cols="12" md="6">
                  <strong> Grduation Year: </strong> {{studentDetails.year.year}} (<span>{{studentDetails.year.description}}</span>)
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Date Enrolled: </strong> {{studentDetails.date_enrolled}}
                </v-col>

                <v-col cols="12" md="6">
                  <strong> created_by: </strong> {{studentDetails.created_by}}
                </v-col>


                <v-col cols="12" md="6">
                  <strong> created_at: </strong> {{studentDetails.created_at}}
                </v-col>
              </v-row>
              <v-row v-else>
               <v-card>No Details to Display at the moment</v-card>
              </v-row>

          </template>
    </div>
</template>

<script>

import CurriculumService from "@/services/CurriculumService";
import variousCountryListFormats from "@/variousCountryListFormats";

export default {
  name: "ShowStudentDetails",
  props: [
    'school_id',
    'studentDetails'
  ],
  data() {
    return {
      logoUrl: "https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/Mutira-Girls-High-School-logo.png",
    }
  },
  methods:{
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
    getValueString(value){
      if(value===1){
       return "Yes"
      }else{
        return "NO"
      }
    }
  }
}
</script>

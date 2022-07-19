<template>
    <div style="width: 800px;" class="pa-4">
          <template>

              <div class="fieldset">
                <div class="inline-field">
                  <v-img
                    :src="schoolDetails.school_logo_url"
                    alt="School logo"
                    max-width="292"
                    min-height="85"
                    class="school-logo"
                  >
                    <template v-slot:placeholder>
                      <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center"
                      >
                        <v-progress-circular
                          indeterminate
                          color="grey"
                        ></v-progress-circular>
                      </v-row>
                    </template>
                  </v-img>
<!--                  <div class="file-upload">-->
<!--                    <label class="file-upload__btn">-->
<!--                      <input type="file" required @change="loadSchoolLogo" id="school_logo" accept="image/*">-->
<!--                      <svg id="camera_icon" width="20" height="17.3" xmlns="http://www.w3.org/2000/svg"-->
<!--                           viewBox="0 0 20 17.3">-->
<!--                        <path d="M10,7c1.7,0,3,1.3,3,3s-1.3,3-3,3s-3-1.3-3-3S8.3,7,10,7z M17.3,2.7c1.5,0,2.7,1.2,2.7,2.7v9.3c0,1.5-1.2,2.7-2.7,2.7H2.7-->
<!--	c-1.5,0-2.7-1.2-2.7-2.7V5.3c0-1.5,1.2-2.7,2.7-2.7H5l0.5-1.4C5.8,0.6,6.6,0,7.3,0h5.3c0.7,0,1.5,0.6,1.8,1.2L15,2.7H17.3z M10,14.7-->
<!--	c2.6,0,4.7-2.1,4.7-4.7S12.6,5.3,10,5.3S5.3,7.4,5.3,10S7.4,14.7,10,14.7z"/>-->
<!--                      </svg>-->
<!--                      Replace logo-->
<!--                    </label>-->
<!--                  </div>-->
                </div>
              </div>
              <div>
                </br>
              </div>
              <v-row>
                <v-col
                  cols="12"
                >
                  <strong> School Name: </strong> {{ schoolDetails.school_name }}
                </v-col>
                <v-col
                  cols="12"
                >
                  <strong> School Code </strong>: {{schoolDetails.school_code}}
                </v-col>

                <v-col
                  cols="12"
                  md="6"
                >
                 <strong> Country code:</strong> {{schoolDetails.country_code}}
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                  <strong>  School Phone:</strong> {{schoolDetails.school_phone}}
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                  <strong>School Address:</strong>  {{schoolDetails.school_address}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> School Physical Address:</strong>  {{schoolDetails.school_physical_address}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> School Website:</strong>  {{schoolDetails.school_website_url}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Country code: </strong> {{schoolDetails.curriculum_id}}
                </v-col>
                <v-col cols="12" md="6">
                  <strong> Created at: </strong> {{schoolDetails.created_at}}
                </v-col>

              </v-row>

          </template>
    </div>
</template>

<script>

import CurriculumService from "@/services/CurriculumService";
import variousCountryListFormats from "@/variousCountryListFormats";

export default {
  name: "ShowSchoolDetails",
  props: [
    'school_id',
    'schoolDetails'
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

    }
  }
}
</script>

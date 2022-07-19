<template>
    <div style="width: 500px;" class="pa-4">
          <template>
              <v-row>
                <v-col
                    cols="12"
                >
                  <strong> Admin Name: </strong> {{ adminDetails.admin_name }}
                </v-col>
                <v-col
                  cols="12"
                >
                  <strong> Admin Email: </strong> {{ adminDetails.admin_email }}
                </v-col>
                <v-col
                  cols="12"
                >
                  <strong> Admin Code </strong>: {{adminDetails.admin_phone}}
                </v-col>

                <v-col
                  cols="12"
                  md="12"
                >
                 <strong> Admin Role:</strong> {{adminDetails.admin_role}}
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                  <strong v-if="this.setSchool()">  School:</strong> {{school_name}}
                </v-col>

              </v-row>

          </template>
    </div>
</template>

<script>

import CurriculumService from "@/services/CurriculumService";
import variousCountryListFormats from "@/variousCountryListFormats";
import SchoolService from "@/services/SchoolService";

export default {
  name: "ShowSchoolDetails",
  props: [
    'school_id',
    'adminDetails',
    'schools'
  ],
  data() {
    return {
      logoUrl: "https://craydel.fra1.cdn.digitaloceanspaces.com/schools/logos/Mutira-Girls-High-School-logo.png",
      schools:[],
      school_name:""
    }
  },
  mounted(){
    this.setSchool()
  },
  methods:{
    setSchool(item){
      let indexItem=this.schools.findIndex(i=>i.id===this.adminDetails.school_id)
      if(indexItem!==-1){
        this.school_name = this.schools[indexItem].school_name||null
      }
      return true
    }
  }
}
</script>

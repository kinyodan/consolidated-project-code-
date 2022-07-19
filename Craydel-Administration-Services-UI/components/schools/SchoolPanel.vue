<template>
  <div>
  <v-toolbar flat>
    <v-toolbar-title class="float-right "></v-toolbar-title>
    <template>
      <v-tabs
          v-model="tabs"
          fixed-tabs
          height="40"
      >
        <v-tabs-slider></v-tabs-slider>
        <v-tab
            href="#mobile-tabs-5-1"
            class="primary--text"
        >
          School Details
        </v-tab>
        <v-tab
            href="#mobile-tabs-5-2"
            class="primary--text"
        >
          Add bank Details
        </v-tab>
        <v-tab
            href="#mobile-tabs-5-3"
            class="primary--text"
        >
          View Bank Details
        </v-tab>
      </v-tabs>
    </template>
  </v-toolbar>
  <v-tabs-items v-model="tabs">
    <v-tab-item
        :value="'mobile-tabs-5-1'"
    >
      <v-card flat>
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
              </div>
            </div>
            <div>
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
                <strong> School Code </strong>: {{ schoolDetails.school_code }}
              </v-col>

              <v-col
                  cols="12"
                  md="6"
              >
                <strong> Country code:</strong> {{ schoolDetails.country_code }}
              </v-col>
              <v-col
                  cols="12"
                  md="6"
              >
                <strong> School Phone:</strong> {{ schoolDetails.school_phone }}
              </v-col>
              <v-col
                  cols="12"
                  md="6"
              >
                <strong>School Address:</strong> {{ schoolDetails.school_address }}
              </v-col>
              <v-col cols="12" md="6">
                <strong> School Physical Address:</strong> {{ schoolDetails.school_physical_address }}
              </v-col>
              <v-col cols="12" md="6">
                <strong> School Website:</strong> {{ schoolDetails.school_website_url }}
              </v-col>
              <v-col cols="12" md="6">
                <strong> Country code: </strong> {{ schoolDetails.curriculum_id }}
              </v-col>
            </v-row>

          </template>
        </div>
      </v-card>
    </v-tab-item>
    <v-tab-item
        :value="'mobile-tabs-5-2'"
    >
      <v-container>
        <v-card
            class="pa-5"
        >
          <BankDetailsForm :item_school_id="schoolDetails.id"
                           :item_school_code="schoolDetails.school_code"></BankDetailsForm>
        </v-card>
      </v-container>
    </v-tab-item>
    <v-tab-item
        :value="'mobile-tabs-5-3'"
    >
      <BankDetailsList :listItems="listItems" :listItems_count="listItems_count"
                       :item_school_code="schoolDetails.school_code" :school_id="schoolDetails.id"></BankDetailsList>
    </v-tab-item>
  </v-tabs-items>
  </div>
</template>
<script>
import BankDetailsForm from "@/components/schools/BankDetailsForm"
import BankDetailsList from "@/components/schools/BankDetailsList"
export default {
  name:"SchoolPanel",
  props:[
   'schoolDetails',
   "listItems",
    "listItems_count"
  ],
  components:{
    BankDetailsForm,
    BankDetailsList
  },
  data(){
    return{
      tabs:null,
    }
  },
  methods:{
  }

}
</script>
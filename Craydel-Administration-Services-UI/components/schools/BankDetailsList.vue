<template>
  <v-card min-height="300" class="pa-4" >
    <v-expansion-panels v-model="panel">
      <v-expansion-panel
          v-for="(item,i) in listItems"
          :key="i"
          v-if="listItems.length>0"
          expand
      >
        <v-expansion-panel-header>
          Bank Name: {{ item.bank_name }}
        </v-expansion-panel-header>
        <v-expansion-panel-content >
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Account name: {{ item.account_name }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Account Number: {{ item.account_number }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Branch Name: {{ item.branch_name }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>Swift Code: {{ item.swift_code }}</v-list-item-title>
            </v-list-item-content>
            <v-list-item-action>
              <template>
<!--                <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Class">-->
<!--                  mdi-pencil-->
<!--                </v-icon>-->
                <v-icon small @click="deleteBankDetail(item)" title="Delete Class">
                  mdi-delete
                </v-icon>
              </template>
              <template >
                <v-dialog v-model="dialogDelete" v-if="deleting" max-width="500" content-class="dialog">
                  <v-card>
                    <v-card-title class="text-h6 text-center">Are you sure you want to delete this record?</v-card-title>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                      <v-btn color="error" text @click="deleteItemConfirm(details_item)">OK {{school_id}}  </v-btn>
                      <v-spacer></v-spacer>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </template>
            </v-list-item-action>
          </v-list-item>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <v-card  v-if="listItems.length===0" class="ma-7 pa-5">No data to show right now </v-card>
  </v-card>
</template>
<script>

import BankDetailsService from "@/services/BankDetailsService";

export default {
  name: 'BankDetailsList',
  layout: 'Default',
  props:[
    'school_id',
    'listItems_count',
    'listItems',
    'item_school_code'
  ],
  head() {
  },
  data() {
    return {
      details_item:null,
      dialogDelete:[],
      deleting:false,
      panel: 0
    }
  },
  methods: {
    async deleteItemConfirm(item){
      let response =await BankDetailsService.deleteBankDetails(this.item_school_code,item.id)
      if(response.data.status){
        this.deleteSuccessMessage = response.data.message
        this.deleteSuccess=true
        this.loading=false
        this.listBankDetails()
        setTimeout(() => {
          this.deleteSuccess=false
        }, "4000")
      }else{
        this.deleteResponseError=true
        this.deleteResponseErrorMessage=response.data.message
        setTimeout(() => {
          this.deleteResponseError=false
        }, "4000")
      }
      this.deleting=false
    },
    deleteBankDetail(item) {
      this.deleting = true
      this.details_item = item
    },
    closeDelete() {
      this.deleting = false
      this.details_item = []
    },
    setCurrentItem(item) {
      this.school_details_dialog = true
      this.getDetails(item)
    },
    async listBankDetails() {
      this.loading = true
      let response = await BankDetailsService.listBankDetails(this.item_school_code)
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

<style scoped>

</style>

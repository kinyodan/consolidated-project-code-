<template>
  <div>
    <div class="alert alert-dismissible fade show margin_all_round" :class="alertClass" role="alert"
         v-if="showAlert">
      <span v-html="alertMsg"></span>
      <button type="button" class="close_alert" @click="hideAlert" aria-label="Close">&times;</button>
    </div>
    <div class="row_table_action">
      <div class="row_action_left">
        <div class="styled-select">
          <select name="action" v-model="bulk_action">
            <option value="">Bulk actions</option>
            <option value="delete">Delete</option>
          </select>
        </div>
        <button class="filter_btn" @click="doBulkAction">Apply</button>

        <v-dialog v-model="dialogBulkDelete" content-class="dialog">
          <v-card>
            <v-card-title class="text-h6 text-center">Are you sure you want to delete { count } graduations?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue" text @click="closeBulkDelete">Cancel</v-btn>
              <v-btn color="error" text @click="deleteBulkItemsConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </div>
      <div class="row_action_right">
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Search"
          single-line
          hide-details
        ></v-text-field>
      </div>
    </div>
    <div data-app>
      <v-data-table
        v-model="selected"
        :headers="headers"
        :items="graduations"
        show-select
        :single-select="singleSelect"
        item-key="id"
        :search="search"
        :items-per-page="5"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-dialog v-model="dialogDelete" content-class="dialog">
            <v-card>
              <v-card-title class="text-h6 text-center">Are you sure you want to delete this graduation?</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </template>
        <template v-slot:item.graduation_year="{ item }">
          <a href="javascript:void(0)" data-toggle="modal" @click.prevent="showDetailsModal(item.id)">{{
              item.graduation_year
            }}</a>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Graduation">
            mdi-pencil
          </v-icon>
          <v-icon small @click="deleteItem(item)" title="Delete Graduation">
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
    </div>
    <!--graduation details modal-->
    <graduation-details></graduation-details>
    <!--graduation details modal-->
  </div>
</template>

<script>

import SchoolService from "@/services/SchoolService";
import GraduationDetails from "@/components/GraduationComponents/GraduationDetails";

export default {
  name: "GraduationsTable",
  components: {GraduationDetails},
  data: () => ({
    dialogDelete: false,
    dialogBulkDelete: false,
    bulk_action: "",
    search: '',
    singleSelect: false,
    selected: [],
    showAlert: false,
    alertClass: 'alert-success',
    alertMsg: '',
    headers: [
      {text: 'Year', value: 'graduation_year'},
      {text: 'Month', value: 'graduation_month'},
      {text: 'Actions', value: 'actions', align: 'right', sortable: false},
    ],
    graduations: [],
    editedIndex: -1,
    editedItem: {
      graduation_month: '',
      graduation_year: '',
    },
    defaultItem: {
      graduation_month: '',
      graduation_year: '',
    },
  }),

  watch: {
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },

  created() {
    this.initialize()
  },

  methods: {
    initialize() {
      this.graduations = [
        {
          graduation_year: '2021',
          graduation_month: 'December',
        },
        {
          graduation_year: '2022',
          graduation_month: 'March',
        },
      ]
    },

    editItem(item) {
      this.editedIndex = this.graduations.indexOf(item)
      this.editedItem = Object.assign({}, item)
      $('#add_new_graduation').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    deleteItem(item) {
      this.editedIndex = this.graduations.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.graduations.splice(this.editedIndex, 1)
      this.closeDelete()
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    deleteBulkItems() {
      this.dialogBulkDelete = true
    },

    deleteBulkItemsConfirm() {
      // this.$nuxt.$loading.start()
      this.graduations.splice(this.editedIndex, 1)
      this.showAlert = false
      this.closeBulkDelete()
    },

    closeBulkDelete() {
      this.dialogBulkDelete = false
    },

    async showDetailsModal(graduation_id) {
      $('#show_graduation_details').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    alertMessage(alert_class, alert_message, display_status, reload_page = false) {
      if (reload_page) {
        window.location.reload()
      } else {
        this.alertMsg = alert_message
        this.alertClass = alert_class
        this.showAlert = display_status
        //remove the loading screen
        this.$nuxt.$loading.finish()
      }
    },

    hideAlert() {
      this.showAlert = false
      this.alertMsg = ""
    },

    async doBulkAction() {
      if (this.bulk_action) {

        //prepare the data
        if (this.selected.length > 0) {

          //check what action we want to do
          if (this.bulk_action === 'delete') {
            this.deleteBulkItems()
          }
        } else {
          this.alertMessage("alert-danger", 'No records Selected', true)
        }
      } else {
        this.alertMessage("alert-danger", 'No Action Selected', true)
      }

      //reset the bulk action to avoid errors
      this.bulk_action = ""
      this.selected = []
    },
  },
}
</script>

<style scoped>

</style>

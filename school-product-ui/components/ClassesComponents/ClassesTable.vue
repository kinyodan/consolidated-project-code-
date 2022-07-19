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
            <v-card-title class="text-h6 text-center">Are you sure you want to delete { count } classes?</v-card-title>
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
        :items="classes"
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
              <v-card-title class="text-h6 text-center">Are you sure you want to delete this class?</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </template>
        <template v-slot:item.class_name="{ item }">
          <a href="javascript:void(0)" data-toggle="modal" @click.prevent="showDetailsModal(item.id)">{{
              item.class_name
            }}</a>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Class">
            mdi-pencil
          </v-icon>
          <v-icon small @click="deleteItem(item)" title="Delete Class">
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
    </div>
    <!--class details modal-->
    <class-details></class-details>
    <!--class details modal-->
  </div>
</template>

<script>

import ClassDetails from "@/components/ClassesComponents/ClassDetails";
export default {
  name: "ClassesTable",
  components: {ClassDetails},
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
      {text: 'Class Name', value: 'class_name'},
      {text: 'Actions', value: 'actions', align: 'right', sortable: false},
    ],
    classes: [],
    editedIndex: -1,
    editedItem: {
      class_name: '',
    },
    defaultItem: {
      class_name: '',
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
      this.classes = [
        {
          class_name: 'Form 1',
        },
        {
          class_name: 'Form 2',
        },
        {
          class_name: 'Form 3',
        },
        {
          class_name: 'Form 4',
        },
      ]
    },

    editItem(item) {
      this.editedIndex = this.classes.indexOf(item)
      this.editedItem = Object.assign({}, item)
      $('#add_new_class').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    deleteItem(item) {
      this.editedIndex = this.classes.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.classes.splice(this.editedIndex, 1)
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
      this.classes.splice(this.editedIndex, 1)
      this.showAlert = false
      this.closeBulkDelete()
    },

    closeBulkDelete() {
      this.dialogBulkDelete = false
    },

    async showDetailsModal(class_id) {
      $('#show_class_details').modal({
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

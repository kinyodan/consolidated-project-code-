<template>

  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Manage Streams</h2></div>
      <div class="app_header_right">
        <a href="" class="add_new_user" data-toggle="modal" @click.prevent="showAddNewStreamModal">Add Stream</a>
      </div>
    </div>
    <v-app class="dash_content_body">
      <div class="dash_inner_content">
        <div class="alert alert-dismissible fade show margin_all_round" :class="alertClass" role="alert"
             v-if="showAlert">
          <span v-html="alertMsg"></span>
          <button type="button" class="close_alert" @click="hideAlert" aria-label="Close">&times;</button>
        </div>
        <div class="row_table_action">
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
        <v-data-table
          :headers="headers"
          :items="streams"
          item-key="id"
          :search="search"
          :items-per-page="5"
          class="elevation-1"
        >
          <template v-slot:top>
            <v-dialog v-model="dialogDelete" content-class="dialog">
              <v-card>
                <v-card-title class="text-h6 text-center">Are you sure you want to delete this stream?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                  <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </template>
          <template v-slot:item.stream_name="{ item }">
            <a href="javascript:void(0)" data-toggle="modal" @click.prevent="showDetailsModal(item.id)">{{
                item.stream_name
              }}</a>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Stream">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item)" title="Delete Stream">
              mdi-delete
            </v-icon>
          </template>
        </v-data-table>
        <add-new-stream></add-new-stream>
        <stream-details></stream-details>
      </div>
    </v-app>
  </div>
</template>

<script>

import StreamDetails from "@/components/StreamsComponents/StreamDetails";
import AddNewStream from "@/components/StreamsComponents/AddNewStream";

export default {
  name: "StreamsTable",
  components: {AddNewStream, StreamDetails},
  data: () => ({
    dialogDelete: false,
    search: '',
    showAlert: false,
    alertClass: 'alert-success',
    alertMsg: '',
    headers: [
      {text: 'Stream Name', value: 'stream_name'},
      {text: 'Actions', value: 'actions', align: 'right', sortable: false},
    ],
    streams: [],
    editedIndex: -1,
    editedItem: {
      stream_name: '',
    },
    defaultItem: {
      stream_name: '',
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
      this.streams = [
        {
          stream_name: 'A',
        },
        {
          stream_name: 'B',
        },
        {
          stream_name: 'C',
        },
        {
          stream_name: 'D',
        },
      ]
    },

    showAddNewStreamModal() {
      $('#add_new_stream').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    editItem(item) {
      this.editedIndex = this.streams.indexOf(item)
      this.editedItem = Object.assign({}, item)
      $('#add_new_stream').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },

    deleteItem(item) {
      this.editedIndex = this.streams.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.streams.splice(this.editedIndex, 1)
      this.closeDelete()
    },

    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },

    async showDetailsModal(stream_id) {
      $('#show_stream_details').modal({
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
  },
}
</script>

<style scoped>

</style>

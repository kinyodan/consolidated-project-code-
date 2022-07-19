<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Manage Students</h2></div>
      <div class="app_header_right">
        <a href="javascript:void(0)" class="add_new_user" data-toggle="modal" @click.prevent="showAddNewStudentModal">Add Student</a>
        <a href="javascript:void(0)" class="bulk_upload" data-toggle="modal" @click.prevent="showStudentBulkUploadModal">Import an
          Excel</a>
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
          <div class="row_action_left">
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-badge
                  :content="selected.length"
                  :value="selected.length"
                  color="green"
                  overlap
                >
                  <v-btn
                    class="download_excel"
                    small
                    v-bind="attrs"
                    v-on="on"
                  >
                    Bulk Actions
                  </v-btn>
                </v-badge>
              </template>
              <v-list>
                <v-list-item-group>
                  <v-list-item
                    class="template-link"
                    @click="doBulkAction('resend')"
                  >
                    <v-list-item-title>
                      <a>Resend Invite</a>
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    class="template-link"
                    @click="doBulkAction('delete')"
                  >
                    <v-list-item-title>
                      <a>Delete Students</a>
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    class="template-link"
                    @click="doBulkAction('rollover')"
                  >
                    <v-list-item-title>
                      <a>Rollover Students</a>
                    </v-list-item-title>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </v-menu>

            <div class="filters">
              <span class="filters__title">Filters</span>
              <div class="filter-field">
                <v-select
                  v-model="filterSelectedClasses"
                  :items="filter_classes"
                  label="Student Class"
                  hide-details
                  multiple
                  single-line
                >
                  <template v-slot:prepend-item>
                    <v-list-item
                      ripple
                      @mousedown.prevent
                      @click="toggleFilterClasses"
                    >
                      <v-list-item-action>
                        <v-icon :color="filterSelectedClasses.length > 0 ? '#18244F darken-4' : ''">
                          {{ filterIconClass }}
                        </v-icon>
                      </v-list-item-action>
                      <v-list-item-content>
                        <v-list-item-title>
                          Select All
                        </v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider class="mt-2"></v-divider>
                  </template>

                  <template v-slot:selection="{ item, index }">
                    <v-chip v-if="index === 0">
                      <span>{{ item }}</span>
                    </v-chip>
                    <span v-if="index === 1" class="grey--text text-caption">
                      (+{{ filterSelectedClasses.length - 1 }} others)
                    </span>
                  </template>
                </v-select>
              </div>
              <button class="filter_btn" @click="doBulkAction">Apply</button>
              <a @click="showMoreFilters">+ More Filters</a>
            </div>
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
        <v-data-table
          v-model="selected"
          :headers="headers"
          :options.sync="options"
          :server-items-length="usersTotal"
          :items="users"
          :search="search"
          :single-select="singleSelect"
          item-key="id"
          :loading="loading"
          :expanded.sync="expanded"
          show-select
          show-expand
          class="elevation-1 school-data-table"
          :footer-props="{
        'items-per-page-options': [10,15,30,45,60,75,100]
      }"
          :items-per-page="15"
        >
          <template v-slot:top>
            <v-dialog v-model="dialogDelete" content-class="dialog">
              <v-card>
                <v-card-title class="text-h6 text-center">Are you sure you want to delete this student?</v-card-title>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                  <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </template>
          <template v-slot:item.data-table-expand="{item, isExpanded, isMobile}">
            <div v-if="isMobile" class="mobile-actions">
              <div class="school-grid-expand" @click="handleExpansion(item, isExpanded)">{{
                  item.student_name
                }}
              </div>
              <v-icon
                @click="handleExpansion(item, isExpanded)"
              >{{ isExpanded ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
              </v-icon>
              <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Student">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)" title="Delete Student">
                mdi-delete
              </v-icon>
            </div>
          </template>
          <template v-slot:expanded-item="{ headers, item, isMobile }">
            <td :colspan="headers.length" class="school-grid-mobile" v-if="isMobile">
              <div class="school-grid-mobile__row">
                <a href="javascript:void(0)" data-toggle="modal" @click.prevent="showDetailsModal(item.id)">View
                  Profile</a>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Student Class</div>
                <div class="v-data-table__mobile-row__cell">{{ item.class_name }}</div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Student Stream</div>
                <div class="v-data-table__mobile-row__cell">{{ item.stream_name }}</div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Invite Status</div>
                <div class="v-data-table__mobile-row__cell">
                  <v-chip :color="getColor(item.is_invite_sent)" dark>
                    {{ item.is_invite_sent === 1 ? 'Delivered' : 'Undelivered' }}
                  </v-chip>
                  <button class="resend_btn"
                          @click="resendInvite(item.id)"
                          v-if="!item.is_account_activated"
                  >
                    Resend Invite
                  </button>
                </div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Account Status</div>
                <div class="v-data-table__mobile-row__cell">
                  <v-chip :color="getColor(item.is_account_activated)" dark>
                    {{ item.is_account_activated === 1 ? 'Active' : 'Inactive' }}
                  </v-chip>
                </div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Payment Status</div>
                <div class="v-data-table__mobile-row__cell">
                  <v-chip :color="getColor(item.has_subscribed_for_assessment)" dark>
                    {{ item.has_subscribed_for_assessment === 1 ? 'Paid' : 'Unpaid' }}
                  </v-chip>
                </div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Assessment Status</div>
                <div class="v-data-table__mobile-row__cell">
                  <v-chip :color="getColor(item.has_done_career_counselling)" dark>
                    {{ item.has_done_career_counselling === 1 ? 'Complete' : 'Incomplete' }}
                  </v-chip>
                </div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Application Status</div>
                <div class="v-data-table__mobile-row__cell">
                  {{ item.student_opportunity_status ? item.student_opportunity_status : 'Course Undecided' }}
                </div>
              </div>
            </td>
          </template>
          <template v-slot:item.student_name="{ item }">
            <a href="javascript:void(0)" data-toggle="modal" @click.prevent="showDetailsModal(item.id)">{{
                item.student_name
              }}</a>
          </template>
          <template v-slot:item.is_invite_sent="{ item }">
            <v-chip :color="getColor(item.is_invite_sent)" dark>
              {{ item.is_invite_sent === 1 ? 'Delivered' : 'Undelivered' }}
            </v-chip>
            <button class="resend_btn"
                    @click="resendInvite(item.id)"
                    v-if="!item.is_account_activated"
            >
              Resend Invite
            </button>
          </template>
          <template v-slot:item.is_account_activated="{ item }">
            <v-chip :color="getColor(item.is_account_activated)" dark>
              {{ item.is_account_activated === 1 ? 'Active' : 'Inactive' }}
            </v-chip>
          </template>
          <template v-slot:item.has_subscribed_for_assessment="{ item }">
            <v-chip :color="getColor(item.has_subscribed_for_assessment)" dark>
              {{ item.has_subscribed_for_assessment === 1 ? 'Paid' : 'Unpaid' }}
            </v-chip>
          </template>
          <template v-slot:item.has_done_career_counselling="{ item }">
            <v-chip :color="getColor(item.has_done_career_counselling)" dark>
              {{ item.has_done_career_counselling === 1 ? 'Complete' : 'Incomplete' }}
            </v-chip>
          </template>
          <template v-slot:item.student_opportunity_status="{ item }">
            {{ item.student_opportunity_status ? item.student_opportunity_status : 'Course Undecided' }}
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit Student">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item)" title="Delete Student">
              mdi-delete
            </v-icon>
          </template>
        </v-data-table>
        <add-new-student></add-new-student>
        <add-new-class></add-new-class>
        <add-new-stream></add-new-stream>
        <bulk-excel-upload></bulk-excel-upload>
        <bulk-delete-students></bulk-delete-students>
        <bulk-resend-invite-students></bulk-resend-invite-students>
        <bulk-rollover-students></bulk-rollover-students>
        <students-filter></students-filter>
        <student-details
          @modal_dialog_closed="modalDialogClosed"
          :student="student_details"
          :show_student_details_loader="this.show_student_details_loader"
        >
        </student-details>
      </div>
    </v-app>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import StudentDetails from "@/components/StudentsComponents/StudentDetails";
import AddNewStudent from "@/components/StudentsComponents/AddNewStudent";
import BulkExcelUpload from "@/components/StudentsComponents/BulkUpload";
import BulkDeleteStudents from "@/components/StudentsComponents/BulkDeleteStudents";
import BulkResendInviteStudents from "@/components/StudentsComponents/BulkResendInviteStudents";
import BulkRolloverStudents from "@/components/StudentsComponents/BulkRolloverStudents";
import AddNewClass from "@/components/ClassesComponents/AddNewClass";
import AddNewStream from "@/components/StreamsComponents/AddNewStream";
import StudentsFilter from "@/components/StudentsComponents/StudentsFilter";

export default {
  name: "StudentsTable",
  components: {
    StudentsFilter,
    AddNewStream,
    AddNewClass,
    BulkRolloverStudents,
    BulkResendInviteStudents, BulkDeleteStudents, BulkExcelUpload, AddNewStudent, StudentDetails
  },
  data() {
    return {
      expanded: [],
      search: '',
      singleSelect: false,
      selected: [],
      usersTotal: 0,
      options: {},
      users: [],
      loading: true,
      bulk_action: "",
      student_details: "",
      headers: [
        {text: '', value: 'data-table-expand', align: 'd-none'},
        {
          text: 'Student Name',
          align: 'start',
          sortable: true,
          value: 'student_name',
        },
        {text: 'Student Class', value: 'class_name', sortable: false},
        {text: 'Student Stream', value: 'stream_name', sortable: false},
        {text: 'Invite Status', value: 'is_invite_sent'},
        {text: 'Account Status', value: 'is_account_activated'},
        {text: 'Payment Status', value: 'has_subscribed_for_assessment'},
        {text: 'Assessment Status', value: 'has_done_career_counselling'},
        {text: 'Application Status', value: 'student_opportunity_status'},
        {text: 'Actions', value: 'actions', align: 'center', sortable: false},
      ],
      dialogDelete: false,
      editedIndex: -1,
      editedItem: {
        student_name: '',
      },
      defaultItem: {
        student_name: '',
      },
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
      show_student_details_loader: true,
      filter_classes: ['Form 1', 'Form 2', 'Form 3'],
      filterSelectedClasses: [],
    }
  },
  computed: {
    filterSelectAllClasses() {
      return this.filterSelectedClasses.length === this.filter_classes.length
    },
    filterSelectSomeClasses() {
      return this.filterSelectedClasses.length > 0 && !this.filterSelectAllClasses
    },
    filterIconClass() {
      if (this.filterSelectAllClasses) return 'mdi-close-box'
      if (this.filterSelectSomeClasses) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },
  methods: {
    showAddNewStudentModal() {
      $('#add_new_user').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
    showStudentBulkUploadModal() {
      $('#bulk_excel_upload').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
    handleExpansion(item, state) {
      const itemIndex = this.expanded.indexOf(item);

      state ? this.expanded.splice(itemIndex, 1) : this.expanded.push(item);
    },
    getColor(value) {
      if (value === 1) {
        return 'green'
      } else {
        return 'red'
      }
    },
    processResponse(response, reload_page = false) {
      if (!response.status) {
        this.alertMessage("alert-danger", response.message, true, reload_page)
      } else {
        this.alertMessage("alert-success", response.message, true, reload_page)
      }
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
    async resendInvite(id) {
      //begin loading
      this.$nuxt.$loading.start()

      //create the request data
      let data = new FormData()
      data.append('ids', id)

      //post to resend api
      let response = await SchoolService.resendStudentInvite(this, data).then(response => response.data)
      if (!response.status) {
        this.alertMessage("alert-danger", 'Invite could not be sent! Please try again', true)
      } else {
        this.alertMessage("alert-success", response.message, true)
      }
    },
    async doBulkAction(action) {
      //begin loading
      // this.$nuxt.$loading.start()

      if (action) {

        //prepare the data
        if (this.selected.length > 0) {
          let ids_array = this.selected.map(student => student.id)
          let ids_string = ids_array.toString()

          //create the request data
          let data = new FormData()
          data.append('ids', ids_string)

          //check what action we want to do
          let response = null
          if (action === 'resend') {
            //post to resend api
            // response = await SchoolService.resendStudentInvite(this, data).then(response => response.data)
            // this.processResponse(response)
            this.hideAlert()
            $('#bulk_resend_invite_students').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
            });
          } else if (action === 'delete') {
            this.hideAlert()
            $('#bulk_delete_students').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
            });
            // response = await SchoolService.deleteStudent(this, data).then(response => response.data)
            // this.processResponse(response, true)
          } else if (action === 'rollover') {
            this.hideAlert()
            $('#bulk_rollover_students').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
            });
            // response = await SchoolService.deleteStudent(this, data).then(response => response.data)
            // this.processResponse(response, true)
          } else {
            this.alertMessage("alert-danger", 'Invalid Action', true)
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
    showDetailsModal(student_id) {
      $('#show_student_details').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
      this.student_details = ""

      SchoolService.getStudent(this, student_id).then(response => {
        if (response.status) {
          this.student_details = response.data.data
          this.show_student_details_loader = false
        } else {
          this.alertMessage('alert-danger', response.message, true)
        }
      })
    },
    editItem(item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      $('#add_new_user').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
    deleteItem(item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    deleteItemConfirm() {
      this.users.splice(this.editedIndex, 1)
      this.closeDelete()
    },
    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    async getDataFromApi() {
      this.loading = true

      let page = 0
      if ('page' in this.options) {
        page = this.options.page
      }
      let itemsPerPage = 15
      if ('itemsPerPage' in this.options) {
        itemsPerPage = this.options.itemsPerPage
      }
      let sortBy = ""
      if ('sortBy' in this.options && this.options.sortBy.length > 0) {
        sortBy = this.options.sortBy[0]
      }
      let sortDirection = ""
      if ('sortDesc' in this.options && this.options.sortDesc.length > 0) {
        if (this.options.sortDesc[0]) {
          sortDirection = 'DESC'
        } else {
          sortDirection = 'ASC'
        }
      }

      let response = await SchoolService.getStudents(this, page, itemsPerPage, this.search, sortBy, sortDirection).then(response => response.data)
      if (response.status) {
        this.loading = false
        this.users = response.data
        this.usersTotal = response.meta.pagination.total
      }

    },
    modalDialogClosed() {
      this.show_student_details_loader = true
    },
    toggleFilterClasses() {
      this.$nextTick(() => {
        if (this.filterSelectAllClasses) {
          this.filterSelectedClasses = []
        } else {
          this.filterSelectedClasses = this.filter_classes.slice()
        }
      })
    },
    showMoreFilters() {
      $('#filter_students').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
  },
  watch: {
    options: {
      handler() {
        this.getDataFromApi()
      },
      deep: true,
    },
    search: {
      handler() {
        this.getDataFromApi()
      },
      deep: true,
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
  },
  /*async fetch() {
    this.users = await SchoolService.getStudents(this).then(response => response.data.data)
  },*/
}
</script>

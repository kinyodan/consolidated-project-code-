<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Manage Users</h2></div>
      <div class="app_header_right">
        <a href="javascript:void(0)" class="add_new_user" data-toggle="modal" @click.prevent="showCreateNewUserModal">Add User</a>
        <a href="javascript:void(0)" class="bulk_upload" data-toggle="modal" @click.prevent="showUserBulkUploadModal">Import an
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
                    @click="doBulkAction('delete')"
                  >
                    <v-list-item-title>
                      <a>Delete Users</a>
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    class="template-link"
                    @click="doBulkAction('deactivate')"
                  >
                    <v-list-item-title>
                      <a>Deactivate Users</a>
                    </v-list-item-title>
                  </v-list-item>
                  <v-list-item
                    class="template-link"
                    @click="doBulkAction('password')"
                  >
                    <v-list-item-title>
                      <a>Reset Password</a>
                    </v-list-item-title>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </v-menu>

            <div class="filters">
              <span class="filters__title">Filters</span>
              <div class="filter-field">
                <v-select
                  v-model="filterSelectedStatuses"
                  :items="filter_statuses"
                  label="Account Status"
                  hide-details
                  multiple
                  single-line
                  clearable
                >
                  <template v-slot:prepend-item>
                    <v-list-item
                      ripple
                      @mousedown.prevent
                      @click="toggleFilterStatuses"
                    >
                      <v-list-item-action>
                        <v-icon :color="filterSelectedStatuses.length > 0 ? '#18244F darken-4' : ''">
                          {{ filterIconStatus }}
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
                      (+{{ filterSelectedStatuses.length - 1 }} others)
                    </span>
                  </template>
                </v-select>
              </div>
              <button class="filter_btn">Apply</button>
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
            <v-dialog v-model="dialogDelete" :retain-focus="false" content-class="dialog">
              <v-card>
                <v-card-title class="text-h6 text-center">Are you sure you want to delete this user?</v-card-title>
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
                  item.user_name
                }}
              </div>
              <v-icon
                @click="handleExpansion(item, isExpanded)"
              >{{ isExpanded ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
              </v-icon>
              <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit User">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)" title="Delete User">
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
                <div class="v-data-table__mobile-row__header">Email</div>
                <div class="v-data-table__mobile-row__cell">{{ item.user_email }}</div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Role</div>
                <div class="v-data-table__mobile-row__cell">{{ item.user_role }}</div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">School</div>
                <div class="v-data-table__mobile-row__cell">{{ item.user_school }}</div>
              </div>
              <div class="school-grid-mobile__row">
                <div class="v-data-table__mobile-row__header">Account Status</div>
                <div class="v-data-table__mobile-row__cell">
                  <v-chip :color="getColor(item.user_status)" dark>
                    {{ item.user_status === 1 ? 'Active' : 'Inactive' }}
                  </v-chip>
                </div>
              </div>
            </td>
          </template>
          <template v-slot:item.user_name="{ item }">
            <a href="javascript:void(0)" @click.prevent="showDetailsModal(item.id)">{{
                item.user_name
              }}</a>
          </template>
          <template v-slot:item.user_status="{ item }">
            <v-chip :color="getColor(item.user_status)" dark>
              {{ item.user_status === 1 ? 'Active' : 'Inactive' }}
            </v-chip>
          </template>
          <template v-slot:item.actions="{ item }">
            <v-icon small class="mr-2" data-toggle="modal" @click="editItem(item)" title="Edit User">
              mdi-pencil
            </v-icon>
            <v-icon small @click="deleteItem(item)" title="Delete User">
              mdi-delete
            </v-icon>
          </template>
        </v-data-table>
        <add-new-user></add-new-user>
        <bulk-user-upload></bulk-user-upload>
        <bulk-delete-users></bulk-delete-users>
        <bulk-deactivate-users></bulk-deactivate-users>
        <bulk-reset-password-users></bulk-reset-password-users>
        <users-filter></users-filter>
        <user-details
          @modal_dialog_closed="modalDialogClosed"
          :show_user_details_loader="this.show_user_details_loader"
        >
        </user-details>
      </div>
    </v-app>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import AddNewUser from "@/components/UsersComponents/AddNewUser";
import UserDetails from "@/components/UsersComponents/UserDetails";
import UsersFilter from "@/components/UsersComponents/UsersFilter";
import BulkUserUpload from "@/components/UsersComponents/BulkUserUpload";
import BulkDeleteUsers from "@/components/UsersComponents/BulkDeleteUsers";
import BulkDeactivateUsers from "@/components/UsersComponents/BulkDeactivateUsers";
import BulkResetPasswordUsers from "@/components/UsersComponents/BulkResetPasswordUsers";

export default {
  name: "UsersTable",
  components: {
    BulkResetPasswordUsers,
    BulkDeactivateUsers,
    BulkDeleteUsers,
    BulkUserUpload,
    UsersFilter,
    UserDetails,
    AddNewUser
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
      user_details: "",
      headers: [
        {text: '', value: 'data-table-expand', align: 'd-none'},
        {
          text: 'Name',
          align: 'start',
          value: 'user_name',
        },
        {text: 'Email', value: 'user_email', sortable: false},
        {text: 'Role', value: 'user_role'},
        {text: 'School', value: 'user_school'},
        {text: 'Account Status', value: 'user_status'},
        {text: 'Actions', value: 'actions', align: 'right', sortable: false},
      ],
      dialogDelete: false,
      editedIndex: -1,
      editedItem: {
        user_name: '',
      },
      defaultItem: {
        user_name: '',
      },
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
      show_user_details_loader: true,
      filter_statuses: ['Active', 'Inactive'],
      filterSelectedStatuses: [],
    }
  },
  computed: {
    filterSelectAllStatuses() {
      return this.filterSelectedStatuses.length === this.filter_statuses.length
    },
    filterSelectSomeStatuses() {
      return this.filterSelectedStatuses.length > 0 && !this.filterSelectAllStatuses
    },
    filterIconStatus() {
      if (this.filterSelectAllStatuses) return 'mdi-close-box'
      if (this.filterSelectSomeStatuses) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },
  created() {
    this.getDataFromApi()
  },
  methods: {
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

      // let response = await SchoolService.getStudents(this, page, itemsPerPage, this.search, sortBy, sortDirection).then(response => response.data)
      // if (response.status) {
      //   this.loading = false
      //   this.users = response.data
      //   this.usersTotal = response.meta.pagination.total
      // }

      this.loading = true
      // Dummy Data
      this.users = [
        {
          id: 1,
          user_name: 'Jason Oner',
          user_email: 'jason.oner@gmail.com',
          user_role: 'Administrator',
          user_school: 'School 1',
          user_status: 1,
        },
        {
          id: 2,
          user_name: 'Mike Carlson',
          user_email: 'mike.carlson@gmail.com',
          user_role: 'Administrator',
          user_school: 'School 2',
          user_status: 1,
        },
        {
          id: 3,
          user_name: 'Cindy Baker',
          user_email: 'cindy.baker@gmail.com',
          user_role: 'Counsellor',
          user_school: 'School 1',
          user_status: 0,
        },
        {
          id: 4,
          user_name: 'Ali Connors',
          user_email: 'ali.connors@gmail.com',
          user_role: 'Counsellor',
          user_school: 'School 2',
          user_status: 1,
        },
        {
          id: 5,
          user_name: 'Travis Howard',
          user_email: 'travis.howard@gmail.com',
          user_role: 'Counsellor',
          user_school: 'School 2',
          user_status: 0,
        },
      ]

    },
    showCreateNewUserModal() {
      $('#create_new_user').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
    showUserBulkUploadModal() {
      $('#bulk_user_upload').modal({
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
    async doBulkAction(action) {
      if (action) {
        //prepare the data
        if (this.selected.length > 0) {
          let ids_array = this.selected.map(user => user.id)
          let ids_string = ids_array.toString()

          //create the request data
          let data = new FormData()
          data.append('ids', ids_string)

          //check what action we want to do
          let response = null
          if (action === 'delete') {
            this.hideAlert()
            $('#bulk_delete_users').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
            });
          } else if (action === 'deactivate') {
            this.hideAlert()
            $('#bulk_deactivate_users').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
            });
          } else if (action === 'password') {
            this.hideAlert()
            $('#bulk_reset_password_users').modal({
              show: true,
              backdrop: 'static',
              keyboard: false
            });
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
      $('#show_user_details').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
      this.user_details = ""

      SchoolService.getStudent(this, student_id).then(response => {
        if (response.status) {
          this.user_details = response.data.data
          this.show_user_details_loader = false
        } else {
          this.alertMessage('alert-danger', response.message, true)
        }
      })
    },
    editItem(item) {
      this.editedIndex = this.users.indexOf(item)
      this.editedItem = Object.assign({}, item)
      $('#create_new_user').modal({
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
    modalDialogClosed() {
      this.show_user_details_loader = true
    },
    toggleFilterStatuses() {
      this.$nextTick(() => {
        if (this.filterSelectAllStatuses) {
          this.filterSelectedStatuses = []
        } else {
          this.filterSelectedStatuses = this.filter_statuses.slice()
        }
      })
    },
    showMoreFilters() {
      $('#filter_users').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
  },
  watch: {
    options: {
      handler() {
        // this.getDataFromApi()
      },
      deep: true,
    },
    search: {
      handler() {
        // this.getDataFromApi()
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

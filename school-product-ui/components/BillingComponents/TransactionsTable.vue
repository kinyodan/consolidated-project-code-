<template>
  <div class="dash_inner_content">
    <div class="alert alert-dismissible fade show margin_all_round" :class="alertClass" role="alert"
         v-if="showAlert">
      <span v-html="alertMsg"></span>
      <button type="button" class="close_alert" @click="hideAlert" aria-label="Close">&times;</button>
    </div>
    <div class="row_table_action">
      <div class="row_action_left">
        <div class="filters">
          <span class="filters__title">Filters</span>
          <div class="filter-field">
            <v-select
              v-model="filterSelectedStatuses"
              :items="filter_statuses"
              label="Payment Status"
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
          <div class="filter-dp">
            <client-only>
              <v-md-date-range-picker></v-md-date-range-picker>
            </client-only>
          </div>
          <button class="filter_btn">Apply</button>
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
      :headers="headers"
      :options.sync="options"
      :items="transactions"
      :search="search"
      item-key="id"
      :loading="loading"
      :expanded.sync="expanded"
      show-expand
      class="elevation-1 school-data-table no-actions"
      :footer-props="{
        'items-per-page-options': [10,15,30,45,60,75,100]
      }"
      :items-per-page="15"
    >
      <template v-slot:item.data-table-expand="{item, isExpanded, isMobile}">
        <div v-if="isMobile" class="mobile-actions">
          <div class="school-grid-expand" @click="handleExpansion(item, isExpanded)">
            {{ item.id + ' - ' + new Date(item.date).toLocaleDateString() }}
          </div>
          <v-icon
            @click="handleExpansion(item, isExpanded)"
          >{{ isExpanded ? 'mdi-chevron-up' : 'mdi-chevron-down' }}
          </v-icon>
        </div>
      </template>
      <template v-slot:expanded-item="{ headers, item, isMobile }">
        <td :colspan="headers.length" class="school-grid-mobile" v-if="isMobile">
          <div class="school-grid-mobile__row">
            <a href="javascript:void(0)" data-toggle="modal" @click.prevent="showDetailsModal(item)">View
              Details</a>
          </div>
          <div class="school-grid-mobile__row">
            <div class="v-data-table__mobile-row__header">Amount</div>
            <div class="v-data-table__mobile-row__cell">{{ item.amount }}</div>
          </div>
          <div class="school-grid-mobile__row">
            <div class="v-data-table__mobile-row__header">Payment Method</div>
            <div class="v-data-table__mobile-row__cell">{{ item.payment_method }}</div>
          </div>
          <div class="school-grid-mobile__row">
            <div class="v-data-table__mobile-row__header">Payment Status</div>
            <div class="v-data-table__mobile-row__cell">
              <v-chip :color="getColor(item.payment_status)" dark>
                {{ item.payment_status }}
              </v-chip>
            </div>
          </div>
        </td>
      </template>
      <template v-slot:item.id="{ item }">
        <a href="javascript:void(0)" @click.prevent="showDetailsModal(item)">{{
            item.id
          }}</a>
      </template>
      <template v-slot:item.date="{ item }">
        {{ new Date(item.date).toLocaleDateString() }}
      </template>
      <template v-slot:item.payment_status="{ item }">
        <v-chip :color="getColor(item.payment_status)">
          {{ item.payment_status }}
        </v-chip>
      </template>
    </v-data-table>
    <users-filter></users-filter>
    <transaction-details
      @modal_dialog_closed="modalDialogClosed"
      :transaction="transaction_details"
      :show_transaction_details_loader="this.show_transaction_details_loader"
    >
    </transaction-details>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";
import UsersFilter from "@/components/UsersComponents/UsersFilter";
import TransactionDetails from "@/components/BillingComponents/TransactionDetails";

export default {
  name: "TransactionsTable",
  components: {
    TransactionDetails,
    UsersFilter
  },
  data() {
    return {
      expanded: [],
      search: '',
      usersTotal: 0,
      options: {},
      transactions: [],
      loading: true,
      transaction_details: "",
      headers: [
        {text: '', value: 'data-table-expand', align: 'd-none'},
        {text: 'Transaction ID', align: 'start', value: 'id', sortable: false},
        {text: 'Date', value: 'date'},
        {text: 'Amount', value: 'amount'},
        {text: 'Payment Method', value: 'payment_method'},
        {text: 'Payment Status', value: 'payment_status', align: 'right'},
      ],
      show_transaction_details_loader: true,
      filter_statuses: ['Paid', 'Pending', 'Failed'],
      filterSelectedStatuses: [],
      showAlert: false,
      alertClass: 'alert-success',
      alertMsg: '',
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

      this.loading = false
      // Dummy Data
      this.transactions = [
        {
          id: 'TRN2LDEBDU',
          date: new Date('2022-06-14'),
          quantity: 2,
          amount: 'Ksh 3150',
          transacted_by: 'Jason Oner',
          payment_method: 'M-Pesa',
          payment_phone: '+254733434556',
          payment_status: 'Paid',
          billing_name: 'Mutira Girls High School',
          billing_phone: '+254733123456',
          billing_email: 'info@mutiraschool.ac.ke',
          billing_address: '36, Kerugoya',
          confirmation_code: 'QRS3TDYCX'
        },
        {
          id: 'TRN5IWTDNP',
          date: new Date('2022-05-23'),
          quantity: 1,
          amount: 'Ksh 1630',
          transacted_by: 'Mike Carlson',
          payment_method: 'Card',
          payment_phone: '',
          payment_status: 'Pending',
          billing_name: 'Mutira Girls High School',
          billing_phone: '+254733123456',
          billing_email: 'info@mutiraschool.ac.ke',
          billing_address: '36, Kerugoya',
          confirmation_code: 'CR34F6GTY2EYJK5X'
        },
        {
          id: 'TRN4173ACE',
          date: new Date('2022-05-02'),
          quantity: 2,
          amount: 'Ksh 2360',
          transacted_by: 'Cindy Baker',
          payment_method: 'Card',
          payment_phone: '',
          payment_status: 'Paid',
          billing_name: 'Mutira Girls High School',
          billing_phone: '+254733123456',
          billing_email: 'info@mutiraschool.ac.ke',
          billing_address: '36, Kerugoya',
          confirmation_code: 'CR5RT34HE7JLT64R'
        },
        {
          id: 'TRN1HKXE57',
          date: new Date('2022-04-16'),
          quantity: 2,
          amount: 'Ksh 3150',
          transacted_by: 'Ali Connors',
          payment_method: 'M-Pesa',
          payment_phone: '+254733434556',
          payment_status: 'Paid',
          billing_name: 'Mutira Girls High School',
          billing_phone: '+254733123456',
          billing_email: 'info@mutiraschool.ac.ke',
          billing_address: '36, Kerugoya',
          confirmation_code: 'QRY34ER5HG'
        },
        {
          id: 'TRN4HKUTSK',
          date: new Date('2022-02-11'),
          amount: 'Ksh 3150',
          transacted_by: 'Travis Howard',
          payment_method: 'M-Pesa',
          payment_phone: '+254733434556',
          payment_status: 'Failed',
          billing_name: 'Mutira Girls High School',
          billing_phone: '+254733123456',
          billing_email: 'info@mutiraschool.ac.ke',
          billing_address: '36, Kerugoya',
          confirmation_code: 'QRUY67DF3'
        },
      ]

    },
    handleExpansion(item, state) {
      const itemIndex = this.expanded.indexOf(item);

      state ? this.expanded.splice(itemIndex, 1) : this.expanded.push(item);
    },
    getColor(value) {
      if (value === 'Paid') {
        return 'green'
      } else if (value === 'Failed') {
        return 'failed'
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
    showDetailsModal(transaction) {
      $('#show_transaction_details').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
      this.transaction_details = transaction
      this.show_transaction_details_loader = false

      // SchoolService.getStudent(this, student_id).then(response => {
      //   if (response.status) {
      //     this.user_details = response.data.data
      //     this.show_user_details_loader = false
      //   } else {
      //     this.alertMessage('alert-danger', response.message, true)
      //   }
      // })
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
  },
  /*async fetch() {
    this.users = await SchoolService.getStudents(this).then(response => response.data.data)
  },*/
}
</script>

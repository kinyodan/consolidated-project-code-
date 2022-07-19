<template>
  <div>
    <PageHeader :title="title" :items="items" />
    <div class="row">
      <div class="col-12">
        <div class="row mt-4">
          <div class="col-sm-12 col-md-6">
            <div id="tickets-table_length" class="dataTables_length">
              <label class="d-inline-flex align-items-center">
                Show&nbsp;
                <b-form-select v-model="perPage" size="sm" :options="pageOptions"></b-form-select>&nbsp;entries
              </label>
            </div>
          </div>
          <!-- Search -->
          <div class="col-sm-12 col-md-6">
            <div id="tickets-table_filter" class="dataTables_filter text-md-right">
              <label class="d-inline-flex align-items-center">
                Search:
                <b-form-input v-model="filter" type="search" placeholder="Search..." class="form-control form-control-sm ml-2"></b-form-input>
              </label>
            </div>
          </div>
          <!-- End search -->
        </div>
        <!-- Table -->
        <div class="table-responsive mb-0">
          <b-table table-class="table table-centered datatable table-card-list" thead-tr-class="bg-transparent" :items="tableData" :fields="fields" responsive="sm" :per-page="perPage" :current-page="currentPage" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :filter="filter" :filter-included-fields="filterOn" @filtered="onFiltered">
            <template v-slot:cell(check)="data">
              <div class="custom-control custom-checkbox text-center">
                <input type="checkbox" class="custom-control-input" :id="`contacusercheck${data.item.id}`" />
                <label class="custom-control-label" :for="`contacusercheck${data.item.id}`"></label>
              </div>
            </template>
            <template v-slot:cell(id)="data">
              <a href="javascript: void(0);" class="text-dark font-weight-bold">{{ data.item.id }}</a>
            </template>

            <template v-slot:cell(group_code)="data">
              <a href="#" class="text-body">{{ data.item.group_code }}</a>
            </template>
            <template v-slot:cell(group_name)="data">
              <a href="#" class="text-body">{{ data.item.group_name }}</a>
            </template>
            <template v-slot:cell(description)="data">
              <a href="#" class="text-body">{{ data.item.description }}</a>
            </template>
            <template v-slot:cell(is_active)="data">
              <div class="badge badge-pill badge-soft-success font-size-12" :class="{'badge-soft-danger': !data.item.is_active}">
                {{ data.item.is_active ? "Active" : "In active" }}
              </div>
            </template>
            <template v-slot:cell(action)>
              <ul class="list-inline mb-0">
                <li class="list-inline-item">
                  <a href="javascript:void(0);" class="px-2 text-primary" v-b-tooltip.hover title="Edit">
                    <i class="uil uil-pen font-size-18"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="javascript:void(0);" class="px-2 text-danger" v-b-tooltip.hover title="Deactivate">
                    <i class="uil uil-trash-alt font-size-18"></i>
                  </a>
                </li>
              </ul>
            </template>
          </b-table>
        </div>
        <div class="row">
          <div class="col">
            <div class="dataTables_paginate paging_simple_numbers float-right">
              <ul class="pagination pagination-rounded">
                <!-- pagination -->
                <b-pagination v-model="currentPage" :total-rows="rows" :per-page="perPage"></b-pagination>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  head() {
    return {
      title: `${this.title} | User Groups`
    };
  },
  data() {
    return {
      apiUrl: "https://account-manager-api.craydel.online",
      tableData: [],
      title: "User Groups",
      items: [{
        text: "User Management"
      },
        {
          text: "User Groups",
          active: true
        }
      ],
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      pageOptions: [10, 25, 50, 100],
      filter: null,
      filterOn: [],
      sortBy: "id",
      sortDesc: false,
      fields: [
        {
          key: "id",
          label:'ID',
          sortable: true
        },
        {
          key: "group_code",
          label:'Group Code',
          sortable: true
        },
        {
          key: "group_name",
          label:'Group Name',
          sortable: true
        },
        {
          key: "description",
          label:'Description',
          sortable: true
        },
        {
          key: "is_active",
          label:'Status',
          sortable: true
        },
        "action",
      ]
    };
  },
  computed: {
    /**
     * Total no. of records
     */
    rows() {
      return this.tableData.length;
    }
  },
  mounted() {
    // Set the initial number of items
    this.totalRows = this.items.length;
    let self = this
    let token = localStorage.getItem('_token')
    let headers = {
      'token': token,
      'locale': 'en'
    }
    axios.get(`${this.apiUrl}/groups`, {
      headers: headers
    }).then((response) => {
      let data = response.data
      if(data.status){
        self.tableData = data.data.items
      }
    })
  },
  methods: {
    /**
     * Search the table data with search input
     */
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    },
    getData() {

    }
  },
  middleware: "authentication",
  name: "GroupsList",
}
</script>

<style scoped>

</style>

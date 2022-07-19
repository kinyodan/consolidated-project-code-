<template>
  <div class="modal fade in" id="show_enrollments_details" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content full-width">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Enrollments Done</h2>
            <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Close</a>
          </div>

          <div class="dash_content_body">
            <div class="dash_inner_content">
              <div class="row_table_action">
                <div class="row_action_left">
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
                        clearable
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
                    <button class="filter_btn">Apply</button>
                    <a href="javascript:void(0)" @click="showMoreFilters">+ More Filters</a>
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
                :items="students"
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
                    <div class="school-grid-expand" @click="handleExpansion(item, isExpanded)">{{ item.student_name }}
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
                      <div class="v-data-table__mobile-row__header">Student Class</div>
                      <div class="v-data-table__mobile-row__cell">{{ item.student_class }}</div>
                    </div>
                    <div class="school-grid-mobile__row">
                      <div class="v-data-table__mobile-row__header">Student Stream</div>
                      <div class="v-data-table__mobile-row__cell">{{ item.student_stream }}</div>
                    </div>
                    <div class="school-grid-mobile__row">
                      <div class="v-data-table__mobile-row__header">Course</div>
                      <div class="v-data-table__mobile-row__cell">{{ item.course }}</div>
                    </div>
                    <div class="school-grid-mobile__row">
                      <div class="v-data-table__mobile-row__header">Institution</div>
                      <div class="v-data-table__mobile-row__cell">{{ item.institution }}</div>
                    </div>
                  </td>
                </template>
              </v-data-table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name: "EnrollmentsDetails",
  data() {
    return {
      expanded: [],
      search: '',
      options: {},
      students: [],
      loading: true,
      headers: [
        {text: '', value: 'data-table-expand', align: 'd-none'},
        {
          text: 'Student Name',
          align: 'start',
          value: 'student_name',
        },
        {text: 'Student Class', value: 'student_class'},
        {text: 'Student Stream', value: 'student_stream'},
        {text: 'Course', value: 'course'},
        {text: 'Institution', value: 'institution'}
      ],
      filter_classes: ['Form 1', 'Form 2', 'Form 3', 'Form 4'],
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

      // When response is successful do following
      this.loading = false

      // Dummy Data
      this.students = [
        {
          id: 1,
          student_name: 'Jason Oner',
          student_class: 'Form 1',
          student_stream: 'A',
          course: 'Graduate Pathway in Computer Science with CS Background',
          institution: 'INTO Illinois State University, United States'
        },
        {
          id: 2,
          student_name: 'Mike Carlson',
          student_class: 'Form 1',
          student_stream: 'A',
          course: 'Foundation Program : Bachelor of Engineering Honours (Mechanical)-Standard',
          institution: 'ISC - Taylors College - The University of Sydney, Australia'
        },
        {
          id: 3,
          student_name: 'Cindy Baker',
          student_class: 'Form 2',
          student_stream: 'Y',
          course: 'Leadership',
          institution: 'Shorelight - Eureka College, United States'
        },
        {
          id: 4,
          student_name: 'Ali Connors',
          student_class: 'Form 4',
          student_stream: 'A',
          course: 'MSc Project Management',
          institution: 'Kaplan - University of Essex Online, United Kingdom'
        },
        {
          id: 5,
          student_name: 'Travis Howard',
          student_class: 'Form 4',
          student_stream: 'B',
          course: 'Pre-Masters Programme - Advanced Biochemistry',
          institution: 'ISC - University of Strathclyde Glasgow, United Kingdom'
        },
      ]
    },
    handleExpansion(item, state) {
      const itemIndex = this.expanded.indexOf(item);

      state ? this.expanded.splice(itemIndex, 1) : this.expanded.push(item);
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
      $('#filter_enrollments').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
    closeModal() {
      $('#show_enrollments_details').modal('hide');
    },
  },
}
</script>

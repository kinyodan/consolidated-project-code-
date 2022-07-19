<template>
  <div class="modal fade in" id="filter_students" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Filter Students</h2>
            <a href="javascript:void(0)" class="cancel_btn" data-dismiss="modal">Close</a>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="student_class">Student Class</label>
              <v-select
                v-model="filterSelectedClasses"
                :items="filter_classes"
                id="student_class"
                hide-details
                multiple
                solo
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
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="student_stream">Student Stream</label>
              <v-select
                v-model="filterSelectedStreams"
                :items="filter_streams"
                id="student_stream"
                hide-details
                multiple
                solo
              >
                <template v-slot:prepend-item>
                  <v-list-item
                    ripple
                    @mousedown.prevent
                    @click="toggleFilterStreams"
                  >
                    <v-list-item-action>
                      <v-icon :color="filterSelectedStreams.length > 0 ? '#18244F darken-4' : ''">
                        {{ filterIconStream }}
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
                      (+{{ filterSelectedStreams.length - 1 }} others)
                    </span>
                </template>
              </v-select>
            </div>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="student_stream">Student Milestones</label>
              <v-select
                v-model="filterSelectedMilestones"
                :items="filter_milestones"
                hide-details
                multiple
                solo
              >
                <template v-slot:prepend-item>
                  <v-list-item
                    ripple
                    @mousedown.prevent
                    @click="toggleFilterMilestones"
                  >
                    <v-list-item-action>
                      <v-icon :color="filterSelectedMilestones.length > 0 ? '#18244F darken-4' : ''">
                        {{ filterIconMilestone }}
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
                      (+{{ filterSelectedMilestones.length - 1 }} others)
                    </span>
                </template>
              </v-select>
            </div>
          </div>
          <div class="full_width">
            <div class="left_form">
              <label class="form_label" for="dob_from">Date of Birth From</label>
              <div class="form_group date-field">
                <v-menu
                  ref="dob_menu_from"
                  v-model="dob_menu_from"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="dob_from"
                      append-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      hide-details
                      solo
                      id="dob_from"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="dob_from"
                    :active-picker.sync="dobActivePickerFrom"
                    :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                    min="1950-01-01"
                    @change="dob_save_from"
                  ></v-date-picker>
                </v-menu>
              </div>
            </div>
            <div class="right_form">
              <label class="form_label" for="dob_to">Date of Birth To</label>
              <div class="form_group date-field">
                <v-menu
                  ref="dob_menu_to"
                  v-model="dob_menu_to"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="auto"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="dob_to"
                      append-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      hide-details
                      solo
                      id="dob_to"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="dob_to"
                    :active-picker.sync="dobActivePickerTo"
                    :max="(new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)"
                    min="1950-01-01"
                    @change="dob_save_to"
                  ></v-date-picker>
                </v-menu>
              </div>
            </div>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="student_gender">Gender</label>
              <v-select
                v-model="filterSelectedGenders"
                :items="filter_genders"
                id="student_gender"
                hide-details
                multiple
                solo
              >
                <template v-slot:prepend-item>
                  <v-list-item
                    ripple
                    @mousedown.prevent
                    @click="toggleFilterGenders"
                  >
                    <v-list-item-action>
                      <v-icon :color="filterSelectedGenders.length > 0 ? '#18244F darken-4' : ''">
                        {{ filterIconGender }}
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
                      (+{{ filterSelectedGenders.length - 1 }} others)
                    </span>
                </template>
              </v-select>
            </div>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete mb-0">
              <label class="form_label" for="student_graduation">Graduation Year</label>
              <v-select
                v-model="filterSelectedGraduations"
                :items="filter_graduations"
                id="student_graduation"
                hide-details
                multiple
                solo
              >
                <template v-slot:prepend-item>
                  <v-list-item
                    ripple
                    @mousedown.prevent
                    @click="toggleFilterGraduations"
                  >
                    <v-list-item-action>
                      <v-icon :color="filterSelectedGraduations.length > 0 ? '#18244F darken-4' : ''">
                        {{ filterIconGraduation }}
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
                      (+{{ filterSelectedGraduations.length - 1 }} others)
                    </span>
                </template>
              </v-select>
            </div>
          </div>
          <div class="action_btns modal_footer">
            <a class="edit_btn" @click="applyFilter">Apply Filter</a>
            <a href="javascript:void(0)" class="cancel_btn" data-dismiss="modal">Cancel</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "StudentsFilter",
  data() {
    return {
      filter_classes: ['Form 1', 'Form 2', 'Form 3'],
      filterSelectedClasses: [],
      filter_streams: ['Red', 'Blue', 'Yellow'],
      filterSelectedStreams: [],
      filter_milestones: ['Invite Status', 'Account Status', 'Payment Status', 'Assessment Status', 'Application Status'],
      filterSelectedMilestones: [],
      filter_genders: ['Male', 'Female', 'Anonymous'],
      filterSelectedGenders: [],
      filter_graduations: ['2022', '2021'],
      filterSelectedGraduations: [],
      dobActivePickerFrom: null,
      dobActivePickerTo: null,
      dob_from: null,
      dob_to: null,
      dob_menu_from: false,
      dob_menu_to: false,
    }
  },
  watch: {
    dob_menu_from(val) {
      val && setTimeout(() => (this.dobActivePickerFrom = 'YEAR'))
    },
    dob_menu_to(val) {
      val && setTimeout(() => (this.dobActivePickerTo = 'YEAR'))
    },
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

    filterSelectAllStreams() {
      return this.filterSelectedStreams.length === this.filter_streams.length
    },
    filterSelectSomeStreams() {
      return this.filterSelectedStreams.length > 0 && !this.filterSelectAllStreams
    },
    filterIconStream() {
      if (this.filterSelectAllStreams) return 'mdi-close-box'
      if (this.filterSelectSomeStreams) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },

    filterSelectAllMilestones() {
      return this.filterSelectedMilestones.length === this.filter_milestones.length
    },
    filterSelectSomeMilestones() {
      return this.filterSelectedMilestones.length > 0 && !this.filterSelectAllMilestones
    },
    filterIconMilestone() {
      if (this.filterSelectAllMilestones) return 'mdi-close-box'
      if (this.filterSelectSomeMilestones) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },

    filterSelectAllGenders() {
      return this.filterSelectedGenders.length === this.filter_genders.length
    },
    filterSelectSomeGenders() {
      return this.filterSelectedGenders.length > 0 && !this.filterSelectAllGenders
    },
    filterIconGender() {
      if (this.filterSelectAllGenders) return 'mdi-close-box'
      if (this.filterSelectSomeGenders) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },

    filterSelectAllGraduations() {
      return this.filterSelectedGraduations.length === this.filter_graduations.length
    },
    filterSelectSomeGraduations() {
      return this.filterSelectedGraduations.length > 0 && !this.filterSelectAllGraduations
    },
    filterIconGraduation() {
      if (this.filterSelectAllGraduations) return 'mdi-close-box'
      if (this.filterSelectSomeGraduations) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },
  methods: {
    toggleFilterClasses() {
      this.$nextTick(() => {
        if (this.filterSelectAllClasses) {
          this.filterSelectedClasses = []
        } else {
          this.filterSelectedClasses = this.filter_classes.slice()
        }
      })
    },
    toggleFilterStreams() {
      this.$nextTick(() => {
        if (this.filterSelectAllStreams) {
          this.filterSelectedStreams = []
        } else {
          this.filterSelectedStreams = this.filter_streams.slice()
        }
      })
    },
    toggleFilterMilestones() {
      this.$nextTick(() => {
        if (this.filterSelectAllMilestones) {
          this.filterSelectedMilestones = []
        } else {
          this.filterSelectedMilestones = this.filter_milestones.slice()
        }
      })
    },
    toggleFilterGenders() {
      this.$nextTick(() => {
        if (this.filterSelectAllGenders) {
          this.filterSelectedGenders = []
        } else {
          this.filterSelectedGenders = this.filter_genders.slice()
        }
      })
    },
    toggleFilterGraduations() {
      this.$nextTick(() => {
        if (this.filterSelectAllGraduations) {
          this.filterSelectedGraduations = []
        } else {
          this.filterSelectedGraduations = this.filter_graduations.slice()
        }
      })
    },
    dob_save_from(date) {
      this.$refs.dob_menu_from.save(date)
    },
    dob_save_to(date) {
      this.$refs.dob_menu_to.save(date)
    },
    applyFilter() {
      $('#filter_students').modal('hide');
    },
  }
}
</script>

<style scoped>

</style>

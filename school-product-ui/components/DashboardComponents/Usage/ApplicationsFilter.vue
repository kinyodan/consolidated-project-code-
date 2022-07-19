<template>
  <div class="modal fade in" id="filter_applications" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Filter Applications Done</h2>
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
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="filter_stream">Student Stream</label>
              <v-select
                v-model="filterSelectedStreams"
                :items="filter_streams"
                id="filter_stream"
                hide-details
                multiple
                solo
                clearable
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
              <label class="form_label" for="filter_course">Course</label>
              <v-autocomplete
                :items="filter_courses"
                id="filter_course"
                hide-details="auto"
                solo
                clearable
              ></v-autocomplete>
            </div>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="filter_institution">Institution</label>
              <v-autocomplete
                :items="filter_institutions"
                id="filter_institution"
                hide-details="auto"
                solo
                clearable
              ></v-autocomplete>
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
  name: "ApplicationsFilter",
  data() {
    return {
      filter_classes: ['Form 1', 'Form 2', 'Form 3', 'Form 4'],
      filterSelectedClasses: [],
      filter_streams: ['Red', 'Blue', 'Yellow'],
      filterSelectedStreams: [],
      filter_courses: ['Course 1', 'Course 2', 'Course 3'],
      filter_institutions: ['Institution 1', 'Institution 2', 'Institution 3'],
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
    applyFilter() {
      $('#filter_applications').modal('hide');
    },
  }
}
</script>

<style scoped>

</style>

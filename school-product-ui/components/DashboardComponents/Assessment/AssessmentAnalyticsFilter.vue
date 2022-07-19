<template>
  <div class="row_table_action pa-0">
    <div class="row_action_left">
      <div class="filters">
        <div class="filter-field">
          <v-select
            v-model="filterSelectedClasses"
            :items="filter_classes"
            label="Filter by Class"
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
        <div class="filter-field">
          <v-select
            v-model="filterSelectedStreams"
            :items="filter_streams"
            label="Filter by Stream"
            hide-details
            multiple
            single-line
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
        <button id="apply_filter" class="filter_btn">Apply</button>
      </div>
    </div>
    <div class="row_action_right">
      <button class="filter_btn" @click="showDetailsModal">View Students</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "AssessmentAnalyticsFilter",
  data() {
    return {
      filter_classes: ['Form 1', 'Form 2', 'Form 3'],
      filterSelectedClasses: [],
      filter_streams: ['Red', 'Blue', 'Yellow'],
      filterSelectedStreams: [],
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

    showDetailsModal() {
      $('#show_assessments_details').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
      });
    },
  },
}
</script>

<style scoped>

</style>

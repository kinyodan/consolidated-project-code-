<template>
  <div class="modal fade in" id="filter_users" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Filter Users</h2>
            <a href="javascript:void(0)" class="cancel_btn" data-dismiss="modal">Close</a>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="user_status">Status</label>
              <v-select
                v-model="filterSelectedStatuses"
                :items="filter_statuses"
                id="user_status"
                hide-details
                clearable
                multiple
                solo
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
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="user_role">Role</label>
              <v-select
                v-model="filterSelectedRoles"
                :items="filter_roles"
                id="user_role"
                hide-details
                clearable
                multiple
                solo
              >
                <template v-slot:prepend-item>
                  <v-list-item
                    ripple
                    @mousedown.prevent
                    @click="toggleFilterRoles"
                  >
                    <v-list-item-action>
                      <v-icon :color="filterSelectedRoles.length > 0 ? '#18244F darken-4' : ''">
                        {{ filterIconRole }}
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
                      (+{{ filterSelectedRoles.length - 1 }} others)
                    </span>
                </template>
              </v-select>
            </div>
          </div>
          <div class="full_width">
            <div class="form_group autocomplete">
              <label class="form_label" for="user_school">School</label>
              <v-select
                v-model="filterSelectedSchools"
                :items="filter_schools"
                id="user_school"
                hide-details
                clearable
                multiple
                solo
              >
                <template v-slot:prepend-item>
                  <v-list-item
                    ripple
                    @mousedown.prevent
                    @click="toggleFilterSchools"
                  >
                    <v-list-item-action>
                      <v-icon :color="filterSelectedSchools.length > 0 ? '#18244F darken-4' : ''">
                        {{ filterIconSchool }}
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
                      (+{{ filterSelectedSchools.length - 1 }} others)
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
  name: "UsersFilter",
  data() {
    return {
      filter_statuses: ['Active', 'Inactive'],
      filterSelectedStatuses: [],
      filter_roles: ['Administrator', 'Counsellor'],
      filterSelectedRoles: [],
      filter_schools: ['School 1', 'School 2', 'School 3'],
      filterSelectedSchools: [],
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

    filterSelectAllRoles() {
      return this.filterSelectedRoles.length === this.filter_roles.length
    },
    filterSelectSomeRoles() {
      return this.filterSelectedRoles.length > 0 && !this.filterSelectAllRoles
    },
    filterIconRole() {
      if (this.filterSelectAllRoles) return 'mdi-close-box'
      if (this.filterSelectSomeRoles) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },

    filterSelectAllSchools() {
      return this.filterSelectedSchools.length === this.filter_schools.length
    },
    filterSelectSomeSchools() {
      return this.filterSelectedSchools.length > 0 && !this.filterSelectAllSchools
    },
    filterIconSchool() {
      if (this.filterSelectAllSchools) return 'mdi-close-box'
      if (this.filterSelectSomeSchools) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    },
  },
  methods: {
    toggleFilterStatuses() {
      this.$nextTick(() => {
        if (this.filterSelectAllStatuses) {
          this.filterSelectedStatuses = []
        } else {
          this.filterSelectedStatuses = this.filter_statuses.slice()
        }
      })
    },
    toggleFilterRoles() {
      this.$nextTick(() => {
        if (this.filterSelectAllRoles) {
          this.filterSelectedRoles = []
        } else {
          this.filterSelectedRoles = this.filter_roles.slice()
        }
      })
    },
    toggleFilterSchools() {
      this.$nextTick(() => {
        if (this.filterSelectAllSchools) {
          this.filterSelectedSchools = []
        } else {
          this.filterSelectedSchools = this.filter_schools.slice()
        }
      })
    },
    applyFilter() {
      $('#filter_users').modal('hide');
    },
  }
}
</script>

<style scoped>

</style>

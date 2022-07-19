<template>
    <div>
        <v-system-bar></v-system-bar>
        <v-card-title class="">
          <template>
            <v-row justify="center" width="">
              <v-col
              >
                <v-card>
                  <v-list>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><strong> Curriculum: </strong> {{ curriculumDetails.curriculum_name }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider ></v-divider>
                    <v-list-item>
                      <v-list-item-action>
                        <v-icon></v-icon>
                      </v-list-item-action>

                      <v-list-item-content>
                        <v-list-item-title>Code: {{curriculumDetails.curriculum_code}}</v-list-item-title>
                      </v-list-item-content>

                      <v-list-item-action>
                      </v-list-item-action>
                    </v-list-item>

                    <v-divider inset></v-divider>

                    <v-list-item>
                      <v-list-item-action>
                      </v-list-item-action>
                      <v-list-item-content>
                        <v-list-item-title>Country code: {{curriculumDetails.country_code}}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>

                    <v-divider inset></v-divider>

                    <v-list-item>
                      <v-list-item-action>
                      </v-list-item-action>

                      <v-list-item-content>
                        <v-list-item-title>Created at: {{curriculumDetails.created_at}}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
                </v-card>
              </v-col>
            </v-row>
          </template>
        </v-card-title>
    </div>
</template>

<script>

export default {
  name: "ShowCurriculumDetails",
  props: [
    'class_id',
    'curriculumDetails'
  ],
  data() {
    return {
      itemDetails:[],
    }
  },
}
</script>

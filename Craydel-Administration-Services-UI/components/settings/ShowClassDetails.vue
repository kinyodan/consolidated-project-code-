<template>
    <div>
      <v-divider></v-divider>

      <v-card-title class="">
          <template>
            <v-row justify="center" width="">
              <v-col
              >
                <v-card>
                  <v-list>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><strong> Class: </strong> {{ classDetails.class_name }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider ></v-divider>
                    <v-list-item>
                      <v-list-item-action>
                        <v-icon></v-icon>
                      </v-list-item-action>

                      <v-list-item-content>
                        <v-list-item-title>Curriculum: {{classDetails.curriculum_code}}</v-list-item-title>
                      </v-list-item-content>

                      <v-list-item-action>
                      </v-list-item-action>
                    </v-list-item>

                    <v-divider inset></v-divider>

                    <v-list-item>
                      <v-list-item-action>
                      </v-list-item-action>
                      <v-list-item-content>
                        <v-list-item-title>Country code: {{classDetails.country_code}}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>

                    <v-divider inset></v-divider>

                    <v-list-item>
                      <v-list-item-action>
                      </v-list-item-action>
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
  name: "ShowClassDetails",
  props: [
    'class_id',
    'classDetails'
  ],
  data() {
    return {
    }
  },
}
</script>

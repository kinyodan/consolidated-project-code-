<template>
    <div>
        <v-card-title class="">
          <template>
            <v-row justify="center" width="">
              <v-col
              >
                <v-card>
                  <v-list>
                    <v-list-item>
                      <v-list-item-content>
                        <v-list-item-title><strong> Stream: </strong> {{ streamDetails.stream_name }}</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-divider ></v-divider>
                    <v-list-item>
                      <v-list-item-action>
                        <v-icon></v-icon>
                      </v-list-item-action>

                      <v-list-item-action>
                      </v-list-item-action>
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
    'stream_id',
    'streamDetails'
  ],
  data() {
    return {
    }
  },
}
</script>

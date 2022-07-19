<template>
  <v-list class="py-0">
    <v-list-item
      v-for="user in users"
      :key="user.id"
      class="px-0"
    >
      <v-list-item-avatar>
        <v-img
          :alt="`${user.user_name} photo`"
          :src="user.user_photo"
        ></v-img>
      </v-list-item-avatar>
      <v-list-item-content>
        <v-list-item-title>{{ user.user_name }}</v-list-item-title>
        <v-list-item-subtitle><span class="text--primary">Role:</span> {{ user.user_role }}</v-list-item-subtitle>
        <v-list-item-subtitle><span class="text--primary">School:</span> {{ user.user_school }}</v-list-item-subtitle>
      </v-list-item-content>
      <v-list-item-action>
        <v-btn
          icon
          color="red"
          @click="removeItemFromList(user.id)"
          title="Remove user from list"
        >
          <v-icon>
            mdi-close
          </v-icon>
        </v-btn>
      </v-list-item-action>
    </v-list-item>
  </v-list>
</template>

<script>
export default {
  name: "BulkUsers",
  data() {
    return {
      users: [
        {
          id: 1,
          user_photo: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
          user_name: 'Jason Oner',
          user_role: 'Administrator',
          user_school: 'School 1'
        },
        {
          id: 2,
          user_photo: 'https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/blank-profile-pic.svg',
          user_name: 'Mike Carlson',
          user_role: 'Administrator',
          user_school: 'School 2'
        },
        {
          id: 3,
          user_photo: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
          user_name: 'Cindy Baker',
          user_role: 'Counsellor',
          user_school: 'School 1'
        },
        {
          id: 4,
          user_photo: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
          user_name: 'Ali Connors',
          user_role: 'Counsellor',
          user_school: 'School 2'
        },
        {
          id: 5,
          user_photo: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
          user_name: 'Travis Howard',
          user_role: 'Counsellor',
          user_school: 'School 2'
        },
      ],
    }
  },
  methods: {
    removeItemFromList(id) {
      this.users = this.users.filter(user => user.id !== id)
    }
  },
}
</script>

<style scoped>

</style>

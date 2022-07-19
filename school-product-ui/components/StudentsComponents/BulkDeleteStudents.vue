<template>
  <div class="modal fade in" id="bulk_delete_students" tabindex="-1">
    <div class="modal-dialog create_new_event">
      <div class="modal-content">
        <div class="modal-content-inner">
          <div class="modal_header">
            <h2 class="modal_title">Delete Students in Bulk</h2>
            <a href="javascript:void(0)" class="cancel_btn" @click="closeModal">Close</a>
          </div>

          <v-skeleton-loader
            v-if="firstLoad"
            class="details-loader"
            :loading="loading"
            type="list-item-avatar@5, divider, actions"
          ></v-skeleton-loader>

          <div v-show="!firstLoad">
            <!-- selected students -->
            <v-list class="py-0">
              <v-list-item
                v-for="student in students"
                :key="student.id"
                class="px-0"
              >
                <v-list-item-avatar>
                  <v-img
                    :alt="`${student.name} photo`"
                    :src="student.photo"
                  ></v-img>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ student.name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ student.class + student.stream }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <!-- selected students end -->
            <div class="action_btns modal_footer">
              <a class="edit_btn" @click="deleteItem">Delete Students</a>
              <a href="" class="cancel_btn" data-dismiss="modal">Cancel</a>
            </div>
          </div>

          <v-dialog v-model="dialogDelete" content-class="dialog">
            <v-card>
              <v-card-title class="text-h6 text-center">Are you sure you want to delete { count } students?
              </v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue" text @click="closeDelete">Cancel</v-btn>
                <v-btn color="error" text @click="deleteItemConfirm">OK</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SchoolService from "@/services/SchoolService";

export default {
  name: "BulkDeleteStudents",
  data() {
    return {
      loading: true,
      firstLoad: false,
      students: [
        {
          id: 1,
          photo: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
          name: 'Jason Oner',
          class: 'Form 1',
          stream: 'A'
        },
        {
          id: 2,
          photo: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
          name: 'Mike Carlson',
          class: 'Form 1',
          stream: 'B'
        },
        {
          id: 3,
          photo: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
          name: 'Cindy Baker',
          class: 'Form 2',
          stream: 'C'
        },
        {
          id: 4,
          photo: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
          name: 'Ali Connors',
          class: 'Form 2',
          stream: 'P'
        },
        {
          id: 5,
          photo: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
          name: 'Travis Howard',
          class: 'Form 3',
          stream: 'Y'
        },
      ],
      dialogDelete: false,
    }
  },
  methods: {
    deleteItem() {
      this.dialogDelete = true
    },

    deleteItemConfirm() {
      this.closeDelete()
      $('#bulk_delete_students').modal('hide');
    },

    closeDelete() {
      this.dialogDelete = false
    },

    closeModal() {
      $('#bulk_delete_students').modal('hide');
    },
  },
}
</script>

<style scoped>

</style>

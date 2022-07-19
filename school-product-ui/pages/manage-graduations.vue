<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Manage Graduations</h2></div>
      <div class="app_header_right">
        <a href="" class="add_new_user" data-toggle="modal" @click.prevent="showAddNewGraduationModal">Add Graduation</a>
      </div>
    </div>
    <div class="dash_content_body">
      <div class="dash_inner_content">
        <v-app>
          <graduations-table></graduations-table>
        </v-app>
        <add-new-graduation></add-new-graduation>
      </div>
    </div>
  </div>
</template>

<script>
import GraduationsTable from "@/components/GraduationComponents/GraduationsTable";
import AddNewGraduation from "@/components/GraduationComponents/AddNewGraduation";

export default {
  name: "manage-graduations",
  components: {AddNewGraduation, GraduationsTable},
  middleware:['auth'],

  methods: {
    showAddNewGraduationModal(){
      $('#add_new_graduation').modal({
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

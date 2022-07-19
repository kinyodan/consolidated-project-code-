<template>
  <div class="dash_main">
    <div class="dash_page_header">
      <div class="app_header_left"><h2 class="page_header">Manage Classes</h2></div>
      <div class="app_header_right">
        <a href="" class="add_new_user" data-toggle="modal" @click.prevent="showAddNewClassModal">Add Class</a>
      </div>
    </div>
    <div class="dash_content_body">
      <div class="dash_inner_content">
        <v-app>
          <classes-table></classes-table>
        </v-app>
        <add-new-class></add-new-class>
      </div>
    </div>
  </div>
</template>

<script>
import ClassesTable from "@/components/ClassesComponents/ClassesTable";
import AddNewClass from "@/components/ClassesComponents/AddNewClass";

export default {
  name: "manage-classes",
  components: {AddNewClass, ClassesTable},
  middleware:['auth'],

  methods: {
    showAddNewClassModal(){
      $('#add_new_class').modal({
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

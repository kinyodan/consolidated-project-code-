<template>
  <students-table></students-table>
</template>

<script>
import StudentsTable from "@/components/StudentsComponents/StudentsTable";

export default {
  name: "manage-students",
  components: {StudentsTable},
  middleware: ['auth'],
}
</script>

<style scoped>

</style>

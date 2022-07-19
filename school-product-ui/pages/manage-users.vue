<template>
  <users-table></users-table>
</template>

<script>

import UsersTable from "@/components/UsersComponents/UsersTable";

export default {
  name: "manage-users",
  components: {UsersTable},
  middleware: ['auth'],
}
</script>

<style scoped>

</style>

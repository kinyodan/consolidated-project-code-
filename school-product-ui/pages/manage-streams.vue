<template>
  <streams-table></streams-table>
</template>

<script>
import StreamsTable from "@/components/StreamsComponents/StreamsTable";

export default {
  name: "manage-streams",
  components: {StreamsTable},
  middleware:['auth'],
}
</script>

<style scoped>

</style>

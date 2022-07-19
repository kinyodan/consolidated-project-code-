<template>
  <AdminForm :editing="editing" :button_text="button_text" :schools="schools" :itemData="itemData" ></AdminForm>
</template>
<script>
import AdminForm from "~/components/admins/AdminForm";
export default {
  name:"AdminEditing",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text",
    "schools"
  ],
  components: {
    AdminForm
  },
  created() {
    // this.$nuxt.$on('inComponentRefreshAdminList', ($event) => this.callInComponentRefreshAdminList())
  },
  data() {
    return {}
  },
  methods:{
    resetEditing(msg){
      this.$emit("resetEditing",false)
    },
    callInComponentRefreshAdminList(){
    },
  }
}
</script>

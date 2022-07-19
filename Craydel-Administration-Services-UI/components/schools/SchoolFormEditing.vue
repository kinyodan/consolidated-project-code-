<template>
  <SchoolForm :editing="editing" :button_text="button_text"  :schools="schools" :itemData="itemData" ></SchoolForm>
</template>
<script>
import SchoolForm from "@/components/schools/SchoolForm";
export default {
  name:"SchoolFormEditing",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text",
    "schools"
  ],
  components: {
    SchoolForm
  },
  data() {
    return {}
  },
  methods:{
    resetEditing(msg){
      this.$emit("resetEditing",false)
    }
  }
}
</script>

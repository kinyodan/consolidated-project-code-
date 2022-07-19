<template>
  <StudentForm :editing="editing" :button_text="button_text" :school_code="school_code" :itemData="itemData" ></StudentForm>
</template>
<script>
import StudentForm from "~/components/students/StudentForm";
export default {
  name:"StudentEditing",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text",
    "propSchoolCode"
  ],
  components: {
    StudentForm
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

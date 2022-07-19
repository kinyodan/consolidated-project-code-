<template>
  <classesForm :editing="editing" :itemData="itemData" ></classesForm>
</template>
<script>
import ClassesForm from "@/components/settings/ClassesForm";

export default {
  name: "ClassesEditing",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
  ],
  components: {
    ClassesForm
  },
  mounted(){
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

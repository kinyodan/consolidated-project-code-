<template>
  <SchoolForm :editing="editing" :button_text="button_text" :itemData="itemData" ></SchoolForm>
</template>
<script>
import SchoolForm from "@/components/schools/SchoolForm";
export default {
  name:"SchoolFormEditing",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
    "button_text"
  ],
  components: {
    SchoolForm
  },
  mounted(){
    console.log(this.itemData)
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

<template>
  <CurriculumForm :editing="editing" :itemData="itemData" ></CurriculumForm>
</template>
<script>
import CurriculumForm from "@/components/settings/CurriculumForm";

export default {
  name:"ClassesFormEditing",
  props: [
    "dialogue_value",
    "editing",
    "itemData",
  ],
  components: {
    CurriculumForm
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

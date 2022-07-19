<template>

</template>

<script>
import variousCountryListFormats from '@/variousCountryListFormats'
import ClassesService from "@/services/ClassesService";

export default {
  name: 'Classes',
  created() {
    this.setCountryList()
    },
  data() {
    return {
     class_name: "",
      curriculum_id: null,
      status: null,
      submitSuccess:false,
      successMessage:"",
      responseError:false,
      responseErrorMessage:"",
      is_global: false,
      countries: [],
    }
  },
  methods: {
    setCountryList() {
      this.countries = variousCountryListFormats.setCountries()
    },
    async submitForm(){
     console.log("submitted ")
      let formData =  new FormData()
      formData.append("class_name", this.class_name)
      formData.append("curriculum_id", this.curriculum_id)
      let response = await ClassesService.addClass(formData)
      if(response.status){
        this.successMessage = response.message
        this.submitSuccess=true
     }
    }
  }
}
</script>

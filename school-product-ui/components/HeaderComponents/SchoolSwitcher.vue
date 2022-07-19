<template>
  <!--  <v-app :id="`${switcher}`" class="school-switcher">-->
  <!--    <v-select-->
  <!--      v-model="selectedSchool"-->
  <!--      :items="schools"-->
  <!--      label="Select"-->
  <!--      hide-details-->
  <!--      prepend-icon="mdi-school"-->
  <!--      background-color="transparent"-->
  <!--      :attach="`#${switcher}`"-->
  <!--      :menu-props="{ auto: true, offsetY: true }"-->
  <!--      solo-->
  <!--      flat-->
  <!--      dense-->
  <!--    ></v-select>-->
  <!--  </v-app>-->

  <div class="school-switcher">
    <div class="styled-select mdi mdi-chevron-down">
      <select id="school_switcher" name="school_switcher">
        <option>School 1</option>
        <option>School 2</option>
        <option>Really long school name lorem ipsum</option>
      </select>
    </div>
  </div>

</template>

<script>
export default {
  name: "SchoolSwitcher",
  props: ['switcher'],
  data() {
    return {
      selectedSchool: 'School 1',
      schools: ['School 1', 'Really long school name lorem ipsum dolor sit amet lorem', 'School 3'],
    }
  },
}
</script>

<style scoped>

</style>

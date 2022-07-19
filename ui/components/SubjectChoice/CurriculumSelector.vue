<template>
  <div class="curriculum-selector">
    <span class="curriculum-selector__label">Currently showing subject requirements for:</span>
    <div class="custom-select">
      <select id="all_pathways" name="all_pathways">
        <option disabled selected>-- Select Curriculum --</option>
        <option value="11">KCSE</option>
        <option value="">IGCSE / A-Levels</option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  name: "CurriculumSelector"
}
</script>

<style scoped>

</style>

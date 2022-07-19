<template>
  <div>
    <PageHeader :title="title" :items="items"/>
    <div class="row">
      <div class="col-12">
        <div>
          <router-link class="btn btn-primary waves-effect waves-light mb-3" to="/course-management/alumni/bulk-import">
            <i
              class="mdi mdi-plus mr-1"></i> Bulk Upload Alumni
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "alumni",
  head() {
    return {
      title: `${this.title} | Institution Alumni`
    };
  },
  data() {
    return {
      title: "Institution Alumni",
      items: [
        {
          text: "Course Management",
          href: "/course-management/institution-list"
        },
        {
          text: "Institutions",
          href: "/course-management/institution-list"
        },
        {
          text: "Alumni",
          active: true
        }
      ],
    }
  }
}
</script>

<style scoped>

</style>

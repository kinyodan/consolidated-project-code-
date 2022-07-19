<template>
  <div>
    <PageHeader :title="title" :items="items"/>

    <div class="row">
      <div class="col-12">
        <!--   Link back to  list   -->
        <div>
          <router-link class="btn btn-primary waves-effect waves-light mb-3" to="/course-management/alumni"><i
            class="mdi mdi-chevron-left mr-1"></i> Back to Alumni
          </router-link>
        </div>
        <!--   End Link back to  list   -->

        <!--   Form   -->
        <div class="card">
          <div class="card-body">
            <p class="card-title-desc">
              Upload the Alumni details.
            </p>
            <div class="alert alert-danger" v-if="alumniFormSubmittedError">
              <p>{{ alumniFormSubmittedErrorText }}</p>
            </div>

            <form @submit.prevent="uploadAlumniList">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Institution Name</label>
                    <b-form-select class="mr-3" id="institution_code" v-model="institution_code" value="institution_code" required>
                      <b-form-select-option :value="null" selected="selected">Select an institution</b-form-select-option>
                      <b-form-select-option v-for="institution in institutions" :key="institution.institution_code" :value="institution.institution_code">{{ institution.institution_name }}</b-form-select-option>
                    </b-form-select>
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Alumni List(xls,xlsx)</label>
                    <input ref="alumni_list" @change="handleLogoUpload" type="file" class="form-control"
                           placeholder="Alumni List" name="alumni_list"
                    />
                    <div class="invalid-feedback">
                      <span>Please upload the alumni list.</span>
                    </div>
                  </div>

                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <button class="btn btn-success" type="submit">Upload Alumni List</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <!--   End Form   -->
      </div>
    </div>
  </div>
</template>

<script>
import CoursesService from "~/helpers/course-services/CoursesService";
export default {
  name: "alumni-bulk-import",
  head() {
    return {
      title: `${this.title} | Institution Alumni Upload`
    };
  },
  data() {
    return {
      title: "Institution Alumni Upload",
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
          href: "/course-management/alumni"
        },
        {
          text: "Bulk Upload",
          active: true
        }
      ],
      alumni_list:null,
      institutions:[],
      alumniFormSubmitted: false,
      alumniFormSubmittedError: false,
      alumniFormSubmittedErrorText: "",
      institution_code:null,
    }
  },
  created() {
    this.getAlumniBuilder()
  },
  methods: {
    getAlumniBuilder() {
      let self = this;
      CoursesService.getCourseBuild().then(response => {
        let data = response.data
        if (data.status) {
          self.institutions = data.data.institutions
        }
      });
    },
    handleLogoUpload() {
      //check if we are updating or just creating
      this.alumni_list = this.$refs.alumni_list.files[0];
    },
    uploadAlumniList(){
      var alumniData = new FormData();
      alumniData.append('alumni_list', this.alumni_list);
      alumniData.append('institution_code', this.institution_code);
      let self = this;
      console.log(alumniData);
      //upload the file

    }
  }
}
</script>

<style scoped>

</style>

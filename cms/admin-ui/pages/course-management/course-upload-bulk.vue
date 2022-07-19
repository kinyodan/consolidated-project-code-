<template>
  <div>
    <PageHeader :title="title" :items="items"/>

    <div class="row">
      <div class="col-12">
        <!--   Link back to  list   -->
        <div>
          <router-link class="btn btn-success waves-effect waves-light mb-3" to="/course-management/courses-list"><i
            class="mdi mdi-chevron-left mr-1"></i> Back to Courses
          </router-link>
        </div>
        <!--   End Link back to  list   -->

        <!--   Form   -->
        <div class="card">
          <div class="card-body">
            <p class="card-title-desc">
              Upload the Course details.
            </p>
            <div class="alert alert-danger" v-if="coursesFormSubmittedError">
              <p>{{ coursesFormSubmittedErrorText }}</p>
            </div>

            <form @submit.prevent="uploadCourseList">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Country</label>
                    <v-select
                      :options="countries"
                      label="name"
                      :reduce="name => name.iso_code"
                      :value="country_code"
                      v-model="country_code">
                    </v-select>
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Institution Name</label>
                    <v-select
                      :options="institutions"
                      label="institution_name" :reduce="institution_name => institution_name.institution_code"
                      :value="institution_code"
                      v-model="institution_code">
                    </v-select>
                  </div>

                </div>

              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Courses List(xls,xlsx)</label>
                    <input ref="courses_list" @change="handleLogoUpload" type="file" class="form-control"
                           placeholder="Courses List" name="courses_list"
                    />
                    <div class="invalid-feedback">
                      <span>Please enter the institution logo.</span>
                    </div>
                  </div>

                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <button class="btn btn-success" type="submit">Upload Courses List</button>
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
import CoursesService from "../../helpers/course-services/CoursesService";
import InstitutionsService from "../../helpers/institution-services/InstitutionsService";

import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";


export default {
  head() {
    return {
      title: `${this.title} | Courses`
    };
  },
  components: {
    vSelect,
    Multiselect
  },
  data() {
    return {
      title: "Upload List",
      items: [
        {
          text: "Course Management",
          href: "/course-management/institution-list"
        },
        {
          text: "Courses",
          href: "/course-management/courses-list"
        },
        {
          text: "Upload",
          active: true
        }
      ],

      countries: [],
      institution_types: [],
      formData: {},
      coursesFormSubmitted: false,
      coursesFormSubmittedError: false,
      coursesFormSubmittedErrorText: "",
      institutions:[],
      courses_list:"",
      institution_code:"",
      country_code:"",
    };
  },
  computed: {},
  created() {
    this.getCourseBuilder()
    this.rollbar_init();
  },
  methods: {
    rollbar_init(){
      // include and initialize the rollbar library with your access token
      var Rollbar = require('rollbar')
      var rollbar = new Rollbar({
        accessToken: process.env.ROLLBAR_TOKEN,
        captureUncaught: true,
        captureUnhandledRejections: true,
      })
      // record a generic message and send it to Rollbar
      rollbar.log(console.error("hello from cms"));

    },
    getCourseBuilder(){
      let self = this;
      CoursesService.getCourseBuild().then(response=>{
        let data = response.data
        if (data.status){
          self.institutions = data.data.institutions
        }
      });

      //get course build
      InstitutionsService.getInstitutionBuild().then(response => {
        let data = response.data
        if (data.status) {
          self.countries = data.data.countries
        }
      })
    },
    handleLogoUpload() {
      //check if we are updating or just creating
      this.courses_list = this.$refs.courses_list.files[0];
    },
    uploadCourseList(){
      var courseData = new FormData();
      courseData.append('courses_list', this.courses_list);
      courseData.append('institution_code', this.institution_code);
      courseData.append('country_code', this.country_code);
      let self = this;
      //upload the file
      CoursesService.uploadCourseList(courseData).then(response=>{
        let data = response.data;
        if (data.status) {
          //redirect to the listing page
          self.$router.push('/course-management/courses-list');
        } else {
          self.coursesFormSubmittedError = true
          self.coursesFormSubmittedErrorText = data.message
        }
      })

    }
  },
  validationRules: {},
  middleware: "authentication",
  name: "CourseUploadBulk",
}
</script>

<style scoped>

</style>

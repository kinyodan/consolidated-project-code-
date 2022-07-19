<template>
  <div>
    <PageHeader :title="title" :items="items"/>
    <div class="row">
      <div class="col-12">
        <!--   Link back to  list   -->
        <div>
          <router-link class="btn btn-success waves-effect waves-light mb-3" to="/course-management/institution-list"><i
            class="mdi mdi-chevron-left mr-1"></i> Back to Institutions
          </router-link>
        </div>
        <!--   End Link back to  list   -->

        <!--   Form   -->
        <div class="card">
          <div class="card-body">
            <p class="card-title-desc">
              Upload the Alumni details.
            </p>
            <span  v-if="alumniFormSubmitted" style="color:green;"><strong>{{alumniFormSubmittedSuccessText}}</strong></span></br>
            <span v-if="alumniFormSubmittedError" style="color:red;"><strong>{{alumniFormSubmittedErrorText}}</strong></span></br>

            <div class="alert alert-danger" v-if="alumniFormSubmittedError">
              <p>{{ alumniFormSubmittedErrorText }}</p>
            </div>
            <form @submit.prevent="uploadAlumniList">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Alumni List(xls,xlsx)</label>
                    <input @change="handleLogoUpload" ref="alumni_list" type="file" class="form-control"
                           placeholder="alumni_list List" name="alumni_list"
                    />
                    <div class="invalid-feedback">
                      <span>Please select the upload file.</span>
                    </div>
                  </div>

                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <button class="btn btn-success" type="submit">Upload List</button>
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
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";

export default {
  head() {
    return {
      title: `${this.title} | Institutions`
    };
  },
  components: {

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
          text: "Institutions",
          href: "/course-management/institution-list"
        },
        {
          text: "Upload",
          active: true
        }
      ],
      countries: [],
      institution_types: [],
      formData: {},
      alumniFormSubmitted: false,
      alumniFormSubmittedSuccessText:"",
      alumniFormSubmittedError:false,
      alumniFormSubmittedErrorText:"",
      alumni_list:null,
    };

  },
  computed: {},
  created() {
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
    handleLogoUpload() {
      //check if we are updating or just creating
        this.alumni_list = this.$refs.alumni_list.files[0];
    },
    uploadAlumniList(){
      var alumniData = new FormData();
      alumniData.append('alumni_list', this.alumni_list);
      let self = this;
      //upload the file
      InstitutionsService.uploadAlumniList(alumniData).then(response=>{
        let data = response.data;
        console.log(data);
        if (data.status) {
          //redirect to the listing page
          self.$router.push('/course-management/alumni-upload-bulk');
          this.alumniFormSubmitted=true;
          this.alumniFormSubmittedSuccessText=data.message;
        } else {
          self.alumniFormSubmittedError = true
          self.alumniFormSubmittedErrorText = data.message
        }
      })

    }

  },

  validationRules: {},
  middleware: "authentication",
  name: "InstitutionUploadBulk",
}
</script>

<style scoped>

</style>

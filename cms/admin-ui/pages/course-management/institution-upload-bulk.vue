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
              Upload the Institution details.
            </p>
            <div class="alert alert-danger" v-if="institutionsFormSubmittedError">
              <p>{{ institutionsFormSubmittedErrorText }}</p>
            </div>

            <form @submit.prevent="uploadInstitutionList">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Institutions List(xls,xlsx)</label>
                    <input @change="handleLogoUpload" ref="institutions_list" type="file" class="form-control"
                           placeholder="Institutions List" name="institutions_list"
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
      institutionsFormSubmitted: false,
      institutionsFormSubmittedError: false,
      institutionsFormSubmittedErrorText: "",
      institutions_list:null,
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
        this.institutions_list = this.$refs.institutions_list.files[0];
    },
    uploadInstitutionList(){
      var institutionData = new FormData();
      institutionData.append('institution_list', this.institutions_list);
      let self = this;
      //upload the file
      InstitutionsService.uploadInstitutionList(institutionData).then(response=>{
        let data = response.data;
        if (data.status) {
          //redirect to the listing page
          self.$router.push('/course-management/institution-list');
        } else {
          self.institutionsFormSubmittedError = true
          self.institutionsFormSubmittedErrorText = data.message
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

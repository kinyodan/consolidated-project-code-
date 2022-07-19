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
          <div class="card-header">
            <h5 class="mb-0" v-if="institution.institution_name">Upload the {{ institution.institution_name }}
              Assets</h5>
          </div>
          <div class="card-body">
            <div class="alert alert-danger" v-if="galleryItemSubmitError">
              <p>{{ galleryItemSubmitErrorText }}</p>
            </div>
            <div class="alert alert-success" v-if="galleryItemSubmitSuccess">
              <p>{{ galleryItemSubmitSuccessText }}</p>
            </div>
            <form @submit.prevent="submitGalleryItem">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Asset Type</label>
                    <b-form-select name="type" v-model="type" required class="form-control" @change="changeType">
                      <b-form-select-option value="">Select Type</b-form-select-option>
                      <b-form-select-option value="Image">Image</b-form-select-option>
                      <b-form-select-option value="VideoLink">Video</b-form-select-option>
                    </b-form-select>
                  </div>
                </div>
                <div class="col-md-6" v-if="showVideoField">
                  <div class="form-group position-relative">
                    <label>Video Link</label>
                    <input type="url" class="form-control" required v-model="asset_url" name="asset_url"
                           placeholder="https://www.youtube.com/placeholder"/>
                  </div>
                </div>
                <div class="col-md-6" v-else>
                  <div class="form-group position-relative">
                    <label>Image File</label>
                    <input type="file" class="form-control" required ref="uploaded_asset_image"
                           v-on:change="handleImageFileUpload()" name="uploaded_asset_image"
                           placeholder="This is a cool image!"/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Image/Video Name</label>
                    <input type="text" v-model="asset_name" required name="asset_name" class="form-control"
                           placeholder="Image/Video Name"/>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label>Image/Video Caption</label>
                    <input type="text" required v-model="asset_caption" name="asset_caption" class="form-control"
                           placeholder="This is a cool image!"/>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <input type="submit" class="btn btn-success" value="Upload">
                </div>
                <div class="col-md-6">
                  <input type="reset" class="btn btn-danger" value="Reset">
                </div>
              </div>
            </form>
          </div>
        </div>
        <!--   Form   -->
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <!--   Gallery  Items  -->
        <h5 class="mb-5" v-if="institution.institution_name">{{ institution.institution_name }} Assets</h5>
        <!--Show the images-->
        <div class="row" v-if="gallery">
          <div class="col-md-4" v-for="item in gallery">
            <div class="card">
              <template v-if="item.type ==='Image'">
                <img :src="item.small_image_url" v-if="item.small_image_url" class="rounded-top"
                     :alt="item.asset_name_slug" />
              </template>
              <template v-else>
                <div class="embed-responsive embed-responsive-4by3" v-if="item.video_url != null">
                  <iframe class="embed-responsive-item" v-if="item.video_url" :src="getVideoIframe(item.video_url)"></iframe>
                </div>
              </template>

              <div class="card-body">
                <p class="card-text">
                  {{ item.asset_description }}
                </p>
                <ul class="list-inline mb-0">
                  <li class="list-inline-item">
                    <a class="px-2 text-danger" href="javascript:void(0);" @click="deleteGalleryItem(item)" v-b-tooltip.hover title="Delete">
                      <i class="uil uil-trash font-size-18"></i>
                    </a>
                  </li>
                  <li class="list-inline-item" v-if="item.type ==='Image'">
                    <a href="javascript:void(0);" @click="featureGalleryItem(item)"  class="px-2" :class="{'text-success':item.is_featured}" v-b-tooltip.hover title="Set Featured">
                      <i class="uil uil-star font-size-18"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!--  Gallery  Items   -->
      </div>
    </div>
  </div>
</template>

<script>
import InstitutionsService from "~/helpers/institution-services/InstitutionsService";

export default {
  name: "institution-gallery",
  head() {
    return {
      title: `${this.title} | Institutions`
    };
  },
  data() {
    return {
      title: "Institution Gallery",
      items: [
        {
          text: "Institutions",
          href: "/course-management/institution-list"
        },
        {
          text: "Manage Gallery",
          active: true
        }
      ],
      type: "Image",
      asset_caption: "",
      asset_name: "",
      asset_url: "",
      showVideoField: false,
      institution: [],
      gallery: [],
      institution_code: "",
      uploaded_asset_image: "",
      galleryItemSubmitError: false,
      galleryItemSubmitErrorText: "",
      galleryItemSubmitSuccess: false,
      galleryItemSubmitSuccessText: "",
    }
  },
  created() {
    let code = this.$route.query.code
    if (code) {
      this.institution_code = code

      //get the institution for editing
      this.getInstitutionToUpdate(code);

      //get the gallery data
      this.getInstitutionGallery(code);
    }
  },
  methods: {
    changeType() {
      if (this.type === 'VideoLink') {
        this.showVideoField = true;
      } else {
        this.showVideoField = false;
      }
    },
    getInstitutionToUpdate(code) {
      let self = this;
      InstitutionsService.getInstitution(code).then(response => {
        let data = response.data
        if (data.status) {
          self.institution = data.data
        }
      });
    },
    getInstitutionGallery(code) {
      let self = this;
      InstitutionsService.getInstitutionGallery(code).then(response => {
        let data = response.data
        if (data.status) {
          self.gallery = data.data
        }
      });
    },
    getVideoIframe(video_url){
      if(video_url) {
        let res = video_url.split("=");
        return "https://www.youtube.com/embed/" + res[1];
      }else{
        return null;
      }
    },
    handleImageFileUpload() {
      //check if we are updating or just creating
      this.uploaded_asset_image = this.$refs.uploaded_asset_image.files[0];
    },
    submitGalleryItem(e) {
      var itemData = new FormData();
      let self = this;

      //append data to the formdata
      itemData.append('asset_name', this.asset_name);
      itemData.append('asset_caption', this.asset_caption);
      itemData.append('type', this.type);

      //check if it
      if (this.type === "Image") {
        itemData.append('uploaded_asset_image', this.uploaded_asset_image);
      } else {
        itemData.append('asset_url', this.asset_url);
      }

      //submit the data
      InstitutionsService.updateInstitutionGallery(itemData, this.institution_code).then(response => {
        let data = response.data;
        if (data.status) {
          //remove any error messages
          self.galleryItemSubmitError = false;
          self.galleryItemSubmitErrorText = "";

          //clear the models data
          self.asset_name = "";
          self.asset_caption = "";
          self.asset_url = "";
          self.type = "";
          self.uploaded_asset_image = "";

          //show success and refresh page after 5 seconds
          self.galleryItemSubmitSuccess = true;
          self.galleryItemSubmitSuccessText = data.message;
          setTimeout(() => {
            location.reload()
          }, 3000)

        } else {
          //remove any success messages
          self.galleryItemSubmitSuccess = false;
          self.galleryItemSubmitSuccessText = "";

          self.galleryItemSubmitError = true;
          self.galleryItemSubmitErrorText = data.message;
        }
      });
    },
    deleteGalleryItem(item){
      let self = this;
      InstitutionsService.deleteInstitutionGalleryItem(item.institution_code,item.asset_code).then(response =>{
        let data = response.data;
        if (data.status) {
          //remove any error messages
          self.galleryItemSubmitError = false;
          self.galleryItemSubmitErrorText = "";

          //show success and refresh page after 5 seconds
          self.galleryItemSubmitSuccess = true;
          self.galleryItemSubmitSuccessText = data.message;
          setTimeout(() => {
            location.reload()
          }, 3000)

        }else {
          //remove any success messages
          self.galleryItemSubmitSuccess = false;
          self.galleryItemSubmitSuccessText = "";

          self.galleryItemSubmitError = true;
          self.galleryItemSubmitErrorText = data.message;
        }
      })
    },

    featureGalleryItem(item){
      let self = this;
      InstitutionsService.featureInstitutionGalleryItem(item.institution_code,item.asset_code).then(response =>{
        let data = response.data;
        if (data.status) {
          //remove any error messages
          self.galleryItemSubmitError = false;
          self.galleryItemSubmitErrorText = "";

          //show success and refresh page after 5 seconds
          self.galleryItemSubmitSuccess = true;
          self.galleryItemSubmitSuccessText = data.message;
          setTimeout(() => {
            location.reload()
          }, 3000)

        }else {
          //remove any success messages
          self.galleryItemSubmitSuccess = false;
          self.galleryItemSubmitSuccessText = "";

          self.galleryItemSubmitError = true;
          self.galleryItemSubmitErrorText = data.message;
        }
      })
    }
  }
}
</script>

<style scoped>

</style>

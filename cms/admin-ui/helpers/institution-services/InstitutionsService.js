import {apiGetClient, apiPostClient} from '../axios-config'

//create the required functions
export default {
  getInstitutions(page,params) {
    if(page) {
      return apiPostClient.post(`/institutions/admin?page=${page}`,params)
    }else{
      return apiPostClient.post(`/institutions/admin`,params)
    }
  },
  getInstitutionsForFilter() {
    return apiGetClient.get(`/institutions/admin?no_paging=1`)
  },
  getInstitution(code) {
    return apiGetClient.get( `/institutions/admin/${code}/edit`)
  },
  getInstitutionBuild() {
    return apiGetClient.get('/institutions/admin/build')
  },
  postInstitution(institution){
    return apiPostClient.post('/institutions/admin/create', institution)
  },
  uploadInstitutionList(institution){
    return apiPostClient.post('/institutions/admin/upload', institution)
  },
  updateInstitution(institution,code) {
    return apiPostClient.post( `/institutions/admin/${code}/update`, institution)
  },
  updateInstitutionGallery(galleryItem,code) {
    return apiPostClient.post( `/institutions/admin/${code}/gallery/add`, galleryItem)
  },
  featureInstitutionGalleryItem(institution_code, asset_code) {
    return apiPostClient.post( `/institutions/admin/${institution_code}/gallery/asset/${asset_code}/feature`)
  },
  deleteInstitutionGalleryItem(institution_code, asset_code) {
    return apiPostClient.post( `/institutions/admin/${institution_code}/gallery/asset/${asset_code}/delete`)
  },
  getInstitutionGallery(code) {
    return apiGetClient.get( `/institutions/admin/${code}/gallery`)
  },
  setFeatured(code) {
    return apiPostClient.post( `/institutions/admin/${code}/feature`)
  },
  deleteInstitution(institution_code,){
    return apiPostClient.post( `/institutions/admin/${institution_code}/delete`)
  },
  publishInstitution(institution_code){
    return apiPostClient.post( `/institutions/admin/${institution_code}/publish`)
  },
  unpublishInstitution(institution_code){
    return apiPostClient.post( `/institutions/admin/${institution_code}/unpublish`)
  },
  uploadAlumniList(alumni_data){
    return apiPostClient.post('/institutions/admin/alumni-upload', alumni_data)
  },
  getAlumniInstitutions(){
    return apiPostClient.get(`/institutions/rpc/institution-names`)
  },
  getAcademicDisciplines() {
    return apiGetClient.get(`/courses/academic-disciplines`)
  },
}

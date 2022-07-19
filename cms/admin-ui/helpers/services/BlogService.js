import {apiGetClient, apiPostClient,blogapiGetClient,blogapiPostClient ,sslblogapiGetClient} from '../axios-config'

//create the required functions
export default {

  getBlogCategories(){
    return blogapiGetClient.get(`/craydel/blog/categories`)
  },

  getBlogPosts(blog_category){
    return blogapiGetClient.get(`/craydel/blog/category/${blog_category}`)
  },

  getBlogPostsPageChange(blog_category,page){
    return blogapiGetClient.get(`/craydel/blog/category/${blog_category}?page=${page}`)
  },

  getIndexPosts(){
    return blogapiGetClient.get(`/craydel/blog/index`)
  },


 blogSearch(course_category_name,search_keywords,number_of_posts_per_page){

   return sslblogapiGetClient.get(`/wp/v2/posts?filter[category_name]=${course_category_name}&search=${search_keywords}&per_page=${number_of_posts_per_page}&order=asc`)


 }




 
 

}

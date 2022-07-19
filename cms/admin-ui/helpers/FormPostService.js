
//create the required functions
export default {

  post_form(import_service , service_source , service_method , redirect_url , [options], edit_action , form_data){
 			
  		  	let errand_importer = import(({import_service, service_source}, '../axios-config'))

          //let errand_importer = await import import_service from service_source;

            return errand_importer
            var specFormSubmittedError = false
            var specFormSubmittedErrorText = false
 
			const reference_method_in_service = {
			  our_service_method: service_method
			};

             var setData = new FormData();
             console.log(setData);

            //get the form data
            for (let key in this.form_data) {

              console.log(key)
              if(this.formData[key] && this.formData[key] !== 'null'){
                setData.append(key, this.formData[key]);
              }

            }  
              //append the for edit
            if (edit_action) {
                import_service.reference_method_in_service.our_service_method(edit_param1,edit_param2).then(response => {
                  let data = response.data;
                  console.log(response.data);
                  if (data.status) {
                    //redirect to the listing page
                      if (redirect_url.length>0){
                        self.$router.push(redirect_url);
                      }
                         
                  } else {
                      FormSubmittedError = true
                      FormSubmittedErrorText = data.message
                  }
                })
              } else {
                import_service.reference_method_in_service.our_service_method(setData).then(response => {
                  let data = response.data;
                  console.log(response.data);
                  if (data.status) {
                    //redirect to the listing page
                      if (redirect_url.length>0){
                      self.$router.push(redirect_url);
                      }

                  } else {
                    FormSubmittedError = true
                    FormSubmittedErrorText = data.message
                  }
                })
              }
    },


  

}


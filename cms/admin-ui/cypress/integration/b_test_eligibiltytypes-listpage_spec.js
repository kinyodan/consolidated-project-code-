
describe('Testing Eligibility Types page ', () => {
  // @see https://on.cypress.io/catalog-of-events
  // let's use "window:before:load" to automatically
  // attach mock Analytics method to the application's "window" object
  // we will use "cy.on" which requires a test or a hook to work
  beforeEach(() => {


      cy.setLocalStorage("_token", "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJDcmF5ZGVsIEF1dGhlbnRpY2F0aW9uIFNlcnZpY2UiLCJzdWIiOiI5ODYxNTA4NzkyNjQiLCJfdG9rZW5faWQiOiJlOGYyZTRhMi1hZTNhLTRjZmQtYjFkYi1mMDVjZjFmYjhlZDUiLCJpYXQiOjE2MzQ4MDc1NjQsImV4cCI6MTYzNzM5OTU2NCwiY291bnRyeV9pZCI6MTEzLCJjb3VudHJ5X2NvZGUiOiJLRSIsImFjY2Vzc19ncm91cF9jb2RlIjoiQ0FHVUwySEZHUkQ0VyIsImFjY2Vzc19yb2xlX2NvZGUiOiJDQVJUNVRLSDlDWVFBIiwidXNlcl9jb2RlIjoiOTg2MTUwODc5MjY0IiwidXNlcl9wcm92aWRlciI6ImN1c3RvbSIsInVzZXJuYW1lIjoiY3JheWRlbGx0ZEBnbWFpbC5jb20iLCJlbWFpbCI6ImNyYXlkZWxsdGRAZ21haWwuY29tIiwiZGVmYXVsdF9sYW5ndWFnZSI6ImVuIiwiZGVmYXVsdF9jdXJyZW5jeV9uYW1lIjoiU2hpbGxpbmciLCJkZWZhdWx0X2N1cnJlbmN5X2NvZGUiOiJLRVMiLCJ0aW1lem9uZSI6IkFmcmljYVwvTmFpcm9iaSIsImZ1bGxfbmFtZSI6IkpvaG4gTmd1cnUiLCJmaXJzdF9uYW1lIjoiSm9obiIsImxhc3RfbmFtZSI6Ik5ndXJ1IiwiZGlzcGxheV9uYW1lIjoiSm9obiBOZ3VydSIsImFjcm9ueW0iOiJKTiIsImdlbmRlciI6bnVsbCwiYWNjZXB0ZWRfdGVybXNfY29uZGl0aW9ucyI6IjEiLCJkYXRlX2FjY2VwdGVkX3Rlcm1zX2NvbmRpdGlvbnMiOiIyMDIxLTA2LTA0IDExOjA3OjMxIiwicHJvZmlsZV9waWN0dXJlX3VybCI6bnVsbCwibW9iaWxlX251bWJlciI6IjI1NDcxMTY4OTkzOCIsInVzZXJfaGFzX3R3b19mYWN0b3JfYXV0aGVudGljYXRpb24iOjAsImFjY2Vzc190eXBlIjoiUGFnZUxvZ2luIiwiYWxsb3dfbXVsdGlwbGVfbG9naW5fc2Vzc2lvbnMiOjEsImZvcmNlX3Bhc3N3b3JkX3Jlc2V0IjowLCJ1c2VyX2lzX2FjdGl2YXRlZCI6dHJ1ZSwidXNlcl9hY3RpdmF0aW9uX2NvZGUiOm51bGwsInVzZXJfYWN0aXZhdGlvbl9jb2RlX2V4cGlyZXNfb24iOm51bGwsInVzZXJfYWNjb3VudF92ZXJpZmllZF9vbiI6bnVsbCwidXNlcl9pc19ibG9ja2VkIjpmYWxzZSwidXNlcl9pc19kZWxldGVkIjpmYWxzZSwidXNlcl9pc19zdXNwZW5kZWQiOmZhbHNlLCJ1c2VyX2FjY291bnRfc3VzcGVuZGVkX29uIjpudWxsLCJsYXN0X3N1Y2Nlc3NmdWxfbG9naW5fZGF0ZSI6bnVsbCwibGFzdF9zdWNjZXNzZnVsX3Bhc3N3b3JkX3Jlc2V0X2RhdGUiOm51bGwsInBhc3N3b3JkX3Jlc2V0X3JlcXVlc3RlZF9vbiI6bnVsbCwicGFzc3dvcmRfcmVzZXRfcmVxdWVzdF9leHBpcmVzX29uIjpudWxsLCJhcHByb3ZlZF9hdCI6bnVsbCwiZGVhY3RpdmF0ZWRfYXQiOm51bGwsImNyZWF0ZWRfYXQiOiIyMDIxLTA2LTA0IDExOjA3OjMxIiwidXBkYXRlZF9hdCI6bnVsbCwiZGVsZXRlZF9hdCI6bnVsbCwiYWNjZXNzX3Blcm1pc3Npb25zIjpbXX0.fz7-11P1CBwSdrne_oohJ5wCzarrcfbzlQJ1pWJi6Yj1M_TdK381UbAovIMPhgKNS5j8wowdZWw8XsVQ6VW-fM26K1iJlTkLXdjsWwO5-Fjfn9gMEaVvmCq82uxp5ZT6K_30SnnHRz4NRibqUSmhLFH6eZmLEyR2rjbj19wc-a1gLucPTLOXe3wNkZeU6CZ3bb_X3eY5Vz85xF1VunQscRmsAQbJS-19spYcu3Dopwo0JdrhnRned81r7gnbSnQ4JcSyCH1i6YdgRPyzZaTSTx7trQ4wo3TiEkEeCEIFFBlow78INhjjp9dn7bIWrucm_DyKD_bIMYvaMKCHVoD3_nKZf_CYscfrekO7c_GMNF9koIAcYNccmxRG0C7LpOhBVFVP3-4dNBbVP9a1zSu4dbiUD4Z0tWiARtfNQ5InV29G4wgW4TG7bWVWQmKakzdu5jRqd3i8rS9aBjcIWFu8eR5gxoitSSlR47WVUW9RczIfcEapXEOKSdsJYSUohRvAkw2xm9vK8WOjC1X8fKgJHecCOu7RVwsHVJ8JMk-AGsIqYY1lGYjLwGlBP7WRtW-5WHt6RS9fMudI5Fd-5WI_NrJ6BGZveqoihsNas7KiqMLQtZv-SnugNm4bQUJIgF4S5JigJeIgvtlFaFuyRCfJCWdFUAmxtLYzAwpc0aopUNI")
      cy.setLocalStorage("user", '{"id":"986150879264","username":"craydelltd@gmail.com","name":"John Nguru","email":"craydelltd@gmail.com","profile_image":"https://icotar.com/initials/John Nguru"}')
      cy.saveLocalStorage()
      
      Cypress.Cookies.defaults({
        preserve: "_token"
      })

    cy.server();
    cy.viewport(1500, 900)


  })


  it("LOGIN: to preserve token and navigate to Dashboard elibility-types page", ()=>{

    cy.visit('https://admin.craydel.online')

 
    //
    //
    // click access Pathways ..
    cy.contains('Pathways').click();
    cy.wait(6000);
    // end click Pathways
    //
    //

    //
    //
    // click access Settings ..
    cy.contains('Settings').click();
    cy.wait(3000);
    // end click Settings
    //
    //

    //
    //
    // click access Eligibility Types ..
    cy.contains('Eligibility Types').click();
    cy.wait(6000);
    // end click Employer Types
    //
    //


    //
    //
    // check dashboard card elements
    cy.get('.b-table').each( (item , index) =>{
        cy
        .wrap(item)

          //
          //
          //
          // start get the first list items 

          if(index === 0){
               
               cy.get('.b-table tr').first()
                  .should('exist')


                ///
                //
                // 
                //start checking each list item inner td items 

                cy.get('.b-table tr > td').first().each( (item , index) =>{
                    cy
                    .wrap(item)

                      cy.get('.etd-name').first()
                          .should('have.attr', 'href', "/pathways/eligibility_types/eligibility-types#")

                      cy.get('.etd-active').first()
                        .should('have.attr', 'href', "/pathways/eligibility_types/eligibility-types#")

                      cy.contains('Academic Eligibility').then(($el) => {
                        console.log($el);
                        cy.wait(6000);

                      })

 
                 }) 

                // end checking each list item inner td items 
                //
                //
                //  


          }else{


          }

          // end get the first list items 
          //
          //
          //


    })        
    cy.wait(6000)

    // end dashboard card elements
    //
    //




    
  })



   
})


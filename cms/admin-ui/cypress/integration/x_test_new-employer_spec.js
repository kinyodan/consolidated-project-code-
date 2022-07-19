/*


describe('Testing assessment-test page ', () => {
  // @see https://on.cypress.io/catalog-of-events
  // let's use "window:before:load" to automatically
  // attach mock Analytics method to the application's "window" object
  // we will use "cy.on" which requires a test or a hook to work
  beforeEach(() => {

      cy.saveLocalStorage()

      cy.setLocalStorage("_token", "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJDcmF5ZGVsIEF1dGhlbnRpY2F0aW9uIFNlcnZpY2UiLCJzdWIiOiI5ODYxNTA4NzkyNjQiLCJfdG9rZW5faWQiOiJjY2U5YzFhNS04YTAwLTQ3ZTEtOTdmYS1iYjRiMjM3OGVmOTkiLCJpYXQiOjE2Mjk4ODYzNDEsImV4cCI6MTYzMjQ3ODM0MSwiY291bnRyeV9pZCI6MTEzLCJjb3VudHJ5X2NvZGUiOiJLRSIsImFjY2Vzc19ncm91cF9jb2RlIjoiQ0FHVUwySEZHUkQ0VyIsImFjY2Vzc19yb2xlX2NvZGUiOiJDQVJUNVRLSDlDWVFBIiwidXNlcl9jb2RlIjoiOTg2MTUwODc5MjY0IiwidXNlcl9wcm92aWRlciI6ImN1c3RvbSIsInVzZXJuYW1lIjoiY3JheWRlbGx0ZEBnbWFpbC5jb20iLCJlbWFpbCI6ImNyYXlkZWxsdGRAZ21haWwuY29tIiwiZGVmYXVsdF9sYW5ndWFnZSI6ImVuIiwiZGVmYXVsdF9jdXJyZW5jeV9uYW1lIjoiU2hpbGxpbmciLCJkZWZhdWx0X2N1cnJlbmN5X2NvZGUiOiJLRVMiLCJ0aW1lem9uZSI6IkFmcmljYVwvTmFpcm9iaSIsImZ1bGxfbmFtZSI6IkpvaG4gTmd1cnUiLCJmaXJzdF9uYW1lIjoiSm9obiIsImxhc3RfbmFtZSI6Ik5ndXJ1IiwiZGlzcGxheV9uYW1lIjoiSm9obiBOZ3VydSIsImFjcm9ueW0iOiJKTiIsImdlbmRlciI6bnVsbCwiYWNjZXB0ZWRfdGVybXNfY29uZGl0aW9ucyI6IjEiLCJkYXRlX2FjY2VwdGVkX3Rlcm1zX2NvbmRpdGlvbnMiOiIyMDIxLTA2LTA0IDExOjA3OjMxIiwicHJvZmlsZV9waWN0dXJlX3VybCI6bnVsbCwibW9iaWxlX251bWJlciI6IjI1NDcxMTY4OTkzOCIsInVzZXJfaGFzX3R3b19mYWN0b3JfYXV0aGVudGljYXRpb24iOjAsImFjY2Vzc190eXBlIjoiUGFnZUxvZ2luIiwiYWxsb3dfbXVsdGlwbGVfbG9naW5fc2Vzc2lvbnMiOjEsImZvcmNlX3Bhc3N3b3JkX3Jlc2V0IjowLCJ1c2VyX2lzX2FjdGl2YXRlZCI6dHJ1ZSwidXNlcl9hY3RpdmF0aW9uX2NvZGUiOm51bGwsInVzZXJfYWN0aXZhdGlvbl9jb2RlX2V4cGlyZXNfb24iOm51bGwsInVzZXJfYWNjb3VudF92ZXJpZmllZF9vbiI6bnVsbCwidXNlcl9pc19ibG9ja2VkIjpmYWxzZSwidXNlcl9pc19kZWxldGVkIjpmYWxzZSwidXNlcl9pc19zdXNwZW5kZWQiOmZhbHNlLCJ1c2VyX2FjY291bnRfc3VzcGVuZGVkX29uIjpudWxsLCJsYXN0X3N1Y2Nlc3NmdWxfbG9naW5fZGF0ZSI6bnVsbCwibGFzdF9zdWNjZXNzZnVsX3Bhc3N3b3JkX3Jlc2V0X2RhdGUiOm51bGwsInBhc3N3b3JkX3Jlc2V0X3JlcXVlc3RlZF9vbiI6bnVsbCwicGFzc3dvcmRfcmVzZXRfcmVxdWVzdF9leHBpcmVzX29uIjpudWxsLCJhcHByb3ZlZF9hdCI6bnVsbCwiZGVhY3RpdmF0ZWRfYXQiOm51bGwsImNyZWF0ZWRfYXQiOiIyMDIxLTA2LTA0IDExOjA3OjMxIiwidXBkYXRlZF9hdCI6bnVsbCwiZGVsZXRlZF9hdCI6bnVsbCwiYWNjZXNzX3Blcm1pc3Npb25zIjpbXX0.EZOFNiyGNUyAU9qR9MQpMjvZYmaTOsMQ7tsywnjIQFkqpzefN7f8znHwjOmWLhqseyke9js2-meli7lROd6-IrRcsXzSj0jYG-huXWmP504jZBKKNMxEUsiurQLqt4lYpfXfB7sqiLZk1rsuW0JTyPQ4j73m0vECbJuIzobKTR7pgWIoNWTRON6Fq3dgyD4wBX9iKrlJXDZwM2XB3yerc2pR8Qe9pTn6l-KzEM92DLj9tRloMnW4OgXEFXxLcxUyEAgu0oo8lTzzmegPFD7z3Gb4uHlfhMkkykmktr_AEFneAnWbhPRlRRlLxvMHsGg9n9kPupYSTsq_Ziv26IJyZoQTdCq2mkmelrbV5XQwjauhCYNNK-PWSQKeJ9VDGGqK7bESLK_wtY4hXQ2aAnRLSoS4c9XP6k8UpDtj8xY0oyatpr_tD9Az-gckeBff9SPdnu3ixA6zpXHnA6ErbscJcGlzXo1u6ScEqfvShuI-NNX5GDOnCV1Pu498CFtZwKmo8TSf0qgFREiHmsnfhIDthzyUjE__Vg-P-37aBRqmUS2pDyV6V3avCAepzXZgC9PQbSipE3PXpS8DfIuiqxfqv2HbLnyH8Dcnk2qDg8cB5IXj0kzueZf44uA66-eIYzPNezuzr4xA0YcQDX13EJ4c0EYBGJTe9tgleTwZ1IVoCB0")
      cy.setLocalStorage("user", '{"id":"986150879264","username":"craydelltd@gmail.com","name":"John Nguru","email":"craydelltd@gmail.com","profile_image":"https://icotar.com/initials/John Nguru"}')

      Cypress.Cookies.defaults({
        preserve: "_token"
      })

    cy.server();
    cy.viewport(1500, 900)


  })


  it("LOGIN: to preserve token and navigate to Dashboard employers  page", ()=>{

    cy.visit('http://localhost:3000')

 
    //
    //
    // click access assesment ..
    cy.contains('Pathways').click();
    cy.wait(6000);
    // end click Pathways
    //
    //

    //
    //
    // click access assesment ..
    cy.contains('Settings').click();
    cy.wait(3000);
    // end click Settings
    //
    //

    //
    //
    // click access assesment ..
    cy.contains('Employer Types').click();
    cy.wait(6000);
    // end click Employer Types
    //
    //


    //
    //
    // click access assesment ..
    cy.contains('Add new Employer types').click();
    cy.wait(6000);
    // end click Add new Employer types
    //
    //


    // ####################  START opening Add new employer type view in sequence and autofill form     
    //
    //
    // Get form inputs and autifill values 

     cy.get('input[name="name"]') // 4.
            .type('Cypress test item employer type') // 5
    
     cy.get('.ck-editor__editable') // 4.
            .type('DESCRIPTION: Cypress test item employer type') // 5

     cy.get(".multiselect__input") // 4.
            .type('keyword test{enter}',{force: true}) // 5

     cy.get(".multiselect__input") // 4.
            .type('keyword test2{enter}', {force: true}) // 5

     cy.get(".btn-primary").click();
     cy.wait(6000);

    // End: Get form inputs and autifill values 
    //
    //
    // ###################  END opening Add new employer type view in sequence and autofill form     


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
                          .should('have.attr', 'href', "/pathways/employer_types/new-employertype/?id=1")

                      cy.get('.etd-active').first()
                        .should('have.attr', 'href', "/pathways/employer_types/employer-type?id=1")

    
                      cy.get('.etd-edit').first()
                        .should('have.attr', 'href', "/pathways/employer_types/new-employertype/?id=1")

                      cy.get('.etd-delete').first()
                        .click();
                        cy.wait(2000);

                      cy.get('.etd-cancel').first()
                        .click();
                        cy.wait(2000);

                      cy.contains('Cypress test item employer type').then(($el) => {
                        console.log($el);
                        cy.wait(6000);

                      }

 
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



*/
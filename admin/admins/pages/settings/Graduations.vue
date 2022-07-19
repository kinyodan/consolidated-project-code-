<template>
  <CCard style="width: 90%">
      <CCardBody>
        <CContainer>
          <CForm>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">text</CFormLabel>
              <CFormInput type="text" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">text</CFormLabel>
              <CFormInput type="text" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">text</CFormLabel>
              <CFormInput type="text" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">text</CFormLabel>
              <CFormInput type="text" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>

            <div class="mb-3">
              <CFormLabel for="exampleFormControlTextarea1">text</CFormLabel>
              <CFormTextarea id="exampleFormControlTextarea1" rows="3"></CFormTextarea>
            </div>
          </CForm>
        </CContainer>
      </CCardBody>
    </CCard>

</template>

<script>
import { CForm } from '@coreui/vue';
import { CFormLabel } from '@coreui/vue';
import { CFormInput } from '@coreui/vue';
import { CFormTextarea } from '@coreui/vue';
import { CContainer } from '@coreui/vue';

export default {
  name: 'graduations',
  components: { CForm,CFormLabel,CFormInput,CFormTextarea,CContainer },
}
</script>

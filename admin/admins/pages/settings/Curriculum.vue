<template>
  <CCard style="width: 90%">
      <CCardBody>
        <CContainer>
          <CForm>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">Curriculum Name</CFormLabel>
              <CFormInput type="email" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">Email address</CFormLabel>
              <CFormInput type="email" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">Email address</CFormLabel>
              <CFormInput type="email" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>
            <div class="mb-3">
              <CFormLabel for="exampleFormControlInput1">Email address</CFormLabel>
              <CFormInput type="email" id="exampleFormControlInput1" placeholder="name@example.com"/>
            </div>

            <div class="mb-3">
              <CFormLabel for="exampleFormControlTextarea1">Example textarea</CFormLabel>
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
import {useStore} from "vuex";
import {logoNegative} from "@/assets/brand/logo-negative";
import {sygnet} from "@/assets/brand/sygnet";
import {computed} from "vue/dist/vue";

export default {
  name: 'curriculum',
  components: { CForm,CFormLabel,CFormInput,CFormTextarea,CContainer },
  setup() {
    return {

      // sidebarUnfoldable: computed(() => store.state.sidebarUnfoldable),
      // sidebarVisible: computed(() => store.state.sidebarVisible),
    }
  },
}
</script>

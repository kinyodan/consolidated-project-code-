<template>
  <section class="applications-card" v-if="applications && progress === 100">
    <h2 class="applications-card__title">My Applications</h2>

    <div class="custom-select">
      <select id="course_applied_for" name="course_applied_for" v-model="selectedCourse" @change="getSingleApplication">
        <option disabled selected value="">Select course applied for</option>
        <option v-for="application in applications" :value="application.opportunity_id">{{
            application.course_name
          }}
        </option>
      </select>
    </div>

    <template v-if="application">
      <!-- application-steps -->
      <ul class="application-steps">
        <li class="done">
          <span>Career Match Assessment Done</span>
        </li>
        <template v-if="application.stages" v-for="stage in application.stages">
          <li class="pending" v-if="stage.is_next_step && !stage.status">
            <span>{{ stage.title }}</span>
          </li>
          <li :class="{'done':stage.status}" v-else>
            <span>{{ stage.title }}</span>
          </li>
        </template>
      </ul>
      <!-- application-steps end -->

      <div class="text_center" v-if="application.current_status_message">
        <div class="application-message">
          <div class="application-message__pic bounceIn">
            <img src="/images/memojis/lady.svg" width="147" height="147" alt="Memoji thumbs up"/>
          </div>
          <div class="application-message__info">{{ application.current_status_message }}</div>
        </div>
      </div>
      <!-- admissions-counsellor -->
      <div class="admissions-counsellor">
        <div class="admissions-counsellor__card">
          <span class="counsellor-label" v-if="application.opportunity_owner_name">My Admissions Counsellor:</span>
          <span>{{ application.opportunity_owner_name }}</span><br/>
          <span class="counsellor-label" v-if="application.opportunity_owner_email">Email:</span> <a
          :href="`mailto:${application.opportunity_owner_email}`">{{ application.opportunity_owner_email }}</a>
        </div>
      </div>
      <!-- admissions-counsellor end -->
    </template>
  </section>

  <section class="applications-card" v-else>
    <h2 class="applications-card__title">My Applications</h2>
    <div class="custom-select">
      <select name="course_applied_for" disabled>
        <option disabled selected>Select course applied for</option>
      </select>
    </div>
    <!-- application-steps -->
    <ul class="application-steps">
      <li>
        <span>Career Match Assessment Done</span>
      </li>
      <li>
        <span>Course decided</span>
      </li>
      <li>
        <span>Application done</span>
      </li>
      <li>
        <span>Offer received</span>
      </li>
      <li>
        <span>Offer accepted</span>
      </li>
      <li>
        <span>Deposit paid</span>
      </li>
      <li>
        <span>Visa application submitted</span>
      </li>
      <li>
        <span>Visa issued</span>
      </li>
      <li>
        <span>Enrolled!</span>
      </li>
    </ul>
    <!-- application-steps end -->

  </section>
</template>

<script>
import {mapState, mapActions} from "vuex";
import ApplicationsService from "@/helpers/ApplicationsService";

export default {
  name: "ApplicationsCard",
  props: ['activeSlot', 'attempt', 'profile', 'progress', 'assessment'],
  data() {
    return {
      selectedCourse: "",
      falseCounter:0,
    }
  },
  computed: {
    ...mapState('profile', {
      applications: state => state.applications,
      application: state => state.application
    }),
  },
  mounted() {
    console.log(this.applications)
  },
  methods: {
    ...mapActions('profile', ['getApplication']),
    getSingleApplication() {
      this.getApplication(this.selectedCourse)
    },
    incrementFalseCounter(){
      this.falseCounter += 1
    },
    async getApplications(){
      /*let _assessment = Object.keys(this.assessment).map((key) => [(key), this.assessment[key]]);
      let _student_email = _assessment[1][1]*/

      console.log(this.assessment)

      /*let formdata = {
        linked_lead_owner_email: _student_email
      }
       let applicationsResponse = await ApplicationsService.getStudentApplications(this, formdata).then(response=>response.data)

      if(applicationsResponse){
        this.applications = applicationsResponse.data
      }*/
    }
  }
}
</script>

<style scoped>

</style>

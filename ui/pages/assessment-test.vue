<template>
  <div>
    <template v-if="!isComplete && answered_questions < total_questions">
      <div class="assessment_section_header" v-if="section">
        <h3 class="section-title">{{ section.name }}</h3>
        <div class="assessment_test_progress">
          <div class="assessment_test_progress_bar" :style="`width:${progress}%`"></div>
        </div>
      </div>

      <div class="question_sheet">
        <div class="question_sheet_excerpt" v-if="section">
          <default-question-format
            v-if="section.question_type === 'SENTENCE'"
            @questionAnswered="questionAnswered"
            :questions="questions"
            :isLastSection="isLastSection"
          ></default-question-format>

          <tabular-questions-format
            v-else-if="section.question_type === 'TABULAR'"
            @questionAnswered="questionAnswered"
            :section="section"
            :questions="questions"
            :isLastSection="isLastSection"
          ></tabular-questions-format>

          <image-question-format
            v-else-if="section.question_type === 'IMAGE'"
            @questionAnswered="questionAnswered"
            :section="section"
            :questions="questions"
            :isLastSection="isLastSection"
          ></image-question-format>

          <case-study-question-format
            v-if="section.question_type === 'CASE_STUDY'"
            @questionAnswered="questionAnswered"
            :section="section"
            :questions="questions"
            :isLastSection="isLastSection"
          ></case-study-question-format>
        </div>

        <section-complete
          :completion_congratulation_message_title=completion_congratulation_message_title
          :completion_congratulation_message=completion_congratulation_message
          :show_section_completion_popup=show_section_completion_popup>
        </section-complete>

      </div>
    </template>
    <template v-if="isComplete">
      <div class="assessment-complete">
        <div class="section-complete__popup fadeInUp">
          <div class="col left">
            <h2 class="popup-title">Congratulations!</h2>
            <p>You have successfully completed your assessment.</p>
            <div class="quiz_action_btns">
              <nuxt-link class="quiz_next_btn" :to="`/ideal-report?profile=${activeSlot}`">View Assessment Report</nuxt-link>
            </div>
          </div>
          <div class="col right">
            <div id="confettiSVG" class="confetti-svg"></div>
            <div class="congrats__memoji bounceIn">
              <img src="/images/memojis/lady.svg" width="163" height="163" alt="Memoji">
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
<script>
import {mapState} from "vuex";
import AssessmentService from "@/helpers/assessments/AssessmentService";
import TabularQuestionsFormat from "@/components/AssessmentComponents/TabularQuestionsFormat";
import ImageQuestionFormat from "@/components/AssessmentComponents/ImageQuestionFormat";
import IdenticalCodes from "@/components/AssessmentComponents/ImageQuestionFormat";
import SectionComplete from "@/components/AssessmentComponents/SectionComplete";
import DefaultQuestionFormat from "@/components/AssessmentComponents/DefaultQuestionFormat";
import CaseStudyQuestionFormat from "@/components/AssessmentComponents/CaseStudyQuestionFormat";

export default {
  components: {DefaultQuestionFormat, SectionComplete, IdenticalCodes, TabularQuestionsFormat, ImageQuestionFormat, CaseStudyQuestionFormat},
  middleware: 'auth',
  name: "assessment-test",
  data() {
    return {
      questions: [],
      section: [],
      answered_questions: 0,
      total_questions: 0,
      isComplete: false,
      show_motivational_message: false,
      number_of_questions_in_section: 0,
      number_of_attempted_questions_in_section: 0,
      show_section_popup: false,
      completion_congratulation_message_title: '',
      completion_congratulation_message: '',
      show_section_completion_popup: false,
      is_start_of_section: false
    }
  },
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    }),
    progress() {
      return Math.round((this.answered_questions / this.total_questions) * 100)
    },
    isLastSection() {
      return (this.total_questions - this.answered_questions) <= 1
    },
  },
  mounted() {
    if (!this.activeSlot) {
      this.$router.push('/my-packages')
    }
    //get the test questions
    this.getQuestions()
  },
  methods: {
    async getQuestions() {
      //start loading
      this.$nextTick(() => {
        this.$nuxt.$loading.start()
      });

      let response = await AssessmentService.getQuestions(this, this.activeSlot);
      if (response.data.status) {
        this.questions = response.data.data.questions;
        this.section = response.data.data.section;
        console.log(this.section)
        this.answered_questions = response.data.data.answered_questions;
        this.total_questions = response.data.data.total_questions;

        if ((this.total_questions - this.answered_questions) <= 0) {
          this.isComplete = true
        }

        //start loading
        this.$nextTick(() => {
          this.$nuxt.$loading.finish()
        });

        this.number_of_questions_in_section = response.data.data.number_of_questions_in_section
        this.number_of_attempted_questions_in_section = response.data.data.number_of_attempted_questions_in_section
        this.is_start_of_section = response.data.data.is_start_of_section

        if(this.answered_questions > 0 && this.is_start_of_section === true){
          this.completion_congratulation_message = response.data.data.section.completion_congratulation_message
          this.completion_congratulation_message_title = response.data.data.section.completion_congratulation_message_title

          if(this.completion_congratulation_message){
            this.show_section_completion_popup = true
          }
        }
      }
    },
    reloadPage() {
      location.reload()
    },
    questionAnswered() {
      ++this.answered_questions
    }
  }
}
</script>

<style>

</style>

<template>
  <form @submit.prevent="reloadPage">
    <div class="question_sheet_excerpt">
      <h4 v-if="section.description">{{ section.description }}</h4>
      <template v-if="getSectionParagraphs.length > 0">
        <p v-for="paragraph in getSectionParagraphs" v-html="paragraph.paragraph"></p>
      </template>
    </div>
    <div class="list_of_questions" v-if="questions">
    <div class="single_quiz_grid" v-for="question in questions">
      <template>
        <h4><span>{{ question.provider_question_code }}. </span>
          <div v-html="question.title"></div>
        </h4>
        <label class="answer_container" v-for="answer in question.question_answer_options">
          <input required type="radio"
                 @change="submitAnswer(question.provider_question_code, answer, question.question_motivation_message)"
                 :name="`question${question.provider_question_code}`" :value="answer"/>
          <span v-html="answer.value"></span>
          <span class="checkmark"></span>
        </label>
      </template>
    </div>

    <motivational-message
      v-if="show_motivational_message"
      emoji_class="party-bubbles"
      :motivation_message="motivational_message">
    </motivational-message>
  </div>
    <div class="quiz_action_btns">
    <button class="quiz_next_btn" type="submit" v-if="!isLastSection">Next</button>
    <a class="quiz_next_btn" :href="`/assessment-test-success?profile=${activeSlot}`" v-else>Finish</a>
  </div>
  </form>
</template>
<script>
import MotivationalMessage from "@/components/AssessmentComponents/MotivationalMessage";
import AssessmentService from "@/helpers/assessments/AssessmentService";
import {mapState} from "vuex";
export default {
  name: "CaseStudyQuestionFormat",
  components: {MotivationalMessage},
  data() {
    return {
      show_motivational_message: false,
      motivational_message: ''
    }
  },
  props:{
    isLastSection: false,
    questions: [],
    section: [],
  },
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    }),
    getSectionParagraphs(){
      let data = JSON.parse(this.section.section_additional_details)
      console.log(data)
      return data
    }
  },
  methods: {
    async submitAnswer(questionCode, answer, question_motivation_message) {
      if(question_motivation_message){
        this.show_motivational_message = true
        this.motivational_message = question_motivation_message

        setTimeout(() => {
          this.show_motivational_message = false;
        }, 3000);
      }

      let formData = new FormData();
      formData.append('provider_question_code', questionCode);
      formData.append('answer_number', answer.sno);
      formData.append('answer_value', answer.key);

      let response = await AssessmentService.submitAnswer(this, this.activeSlot, formData);

      if(response.data.status){
        this.$emit('questionAnswered')
      }
    },
    reloadPage() {
      location.reload()
    }
  }
}
</script>

<style scoped>

</style>

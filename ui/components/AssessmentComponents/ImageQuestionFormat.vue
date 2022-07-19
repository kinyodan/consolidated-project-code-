<template>
  <form @submit.prevent="reloadPage">
    <div class="list_of_questions" v-if="questions">
      <div class="single_quiz_grid identical-codes" v-for="question in questions">
        <h4>
          <span>{{ question.provider_question_code }}.</span>
          <div v-html="question.title"></div>
        </h4>

        <div class="code_wrapper">
          <div class="code">
            <img :src="question.base_image" width="258" height="241" alt="Question pic"/>
          </div>
          <div class="code_answers">
            <label class="answer_container" v-for="answer in question.question_answer_options">
              <input required type="radio"
                     @change="submitAnswer(question.provider_question_code, answer, question.question_motivation_message)"
                     :name="`question${question.provider_question_code}`" :value="answer"/>
              <span v-if="isValidURL(answer.value)" :class="{'image-option' : question.question_type === 'IMAGE'}">
                <img :src="answer.value" width="102" height="102" alt="Option pic"/>
              </span>

              <span v-else v-html="answer.value"></span>
              <span class="checkmark"></span>
            </label>
          </div>
        </div>
      </div>
    </div>

    <motivational-message
      v-if="show_motivational_message"
      emoji_class="party-bubbles"
      :motivation_message="motivational_message">
    </motivational-message>

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
  name: "ImageQuestionFormat",
  components: {MotivationalMessage},
  data() {
    return {
      section: [],
      show_motivational_message: false,
      motivational_message: ''
    }
  },
  props:{
    isLastSection: false,
    questions: []
  },
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    })
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
    },
    isValidURL(string) {
      let res = string.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
      return (res !== null)
    }
  }
}
</script>

<style scoped>

</style>

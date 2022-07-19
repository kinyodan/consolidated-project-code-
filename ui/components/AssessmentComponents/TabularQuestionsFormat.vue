<template>
  <form @submit.prevent="reloadPage">
    <div>
      <div class="question_sheet_excerpt">
        <h4 v-if="section.description">{{ section.description }}</h4>
        <p v-if="section.section_additional_details">{{ section.section_additional_details }}</p>
      </div>

      <table class="tabular-questions">
        <thead>
        <tr>
          <th scope="col" class="number-col"></th>
          <th scope="col" class="align-left">{{ section.tabular_section_title }}</th>
          <template v-for="answer_title in getAnswerOptions">
            <th scope="col">{{ answer_title }}</th>
          </template>
        </tr>
        </thead>
        <tbody>
        <tr v-for="question in questions">
          <td class="q-number">{{ question.provider_question_code }}.</td>
          <td class="question">
            <div class="text">{{ question.title }}</div>
          </td>
          <template v-for="answer in question.question_answer_options">
            <td :data-label="answer.value">
              <label class="answer_container">
                <input required type="radio"
                       @change="submitAnswer(question.provider_question_code, answer, question.question_motivation_message)"
                       :name="`question${question.provider_question_code}`" :value="answer.value"/>
                <span class="checkmark"></span>
              </label>
            </td>
          </template>
        </tr>
        </tbody>
      </table>

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
  name: "TabularQuestionsFormat",
  components: {MotivationalMessage},
  data() {
    return {
      show_motivational_message: false,
      questions_list: this.questions,
      motivational_message: ""
    }
  },
  props:{
    isLastSection: false,
    questions: [],
    section: []
  },
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
    }),
    getAnswerOptions(){
      let _final_question_list = Object.keys(this.questions).map((key) => [Number(key), this.questions[key]]);

      return _final_question_list = (function doSomething() {
        let _data = [];

        _final_question_list[0][1].question_answer_options.forEach(element => {
          _data.push(element.value)
        })

        return _data
      })(_final_question_list)
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

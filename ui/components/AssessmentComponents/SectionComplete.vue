<template v-if="completion_congratulation_message">
  <div class="section-complete" v-if="displaySectionCompletionPopup">
    <div class="section-complete__popup fadeInUp">
      <div class="col left">
        <h2 class="popup-title" v-if="completion_congratulation_message_title">{{ completion_congratulation_message_title }}</h2>
        <p v-if="completion_congratulation_message" v-html="completion_congratulation_message"></p>
        <div class="quiz_action_btns">
          <button type="button" class="quiz_next_btn" @click="hideSectionCompletionPopup()">Next Section</button>
        </div>
      </div>
      <div class="col right">
        <div id="confettiSVG" class="confetti-svg"></div>
        <div class="congrats__memoji bounceIn">
          <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/lady.svg" width="163" height="163" alt="Memoji">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SectionComplete",
  props: {
    completion_congratulation_message_title: '',
    completion_congratulation_message: '',
    show_section_completion_popup: false,
  },
  data () {
    return {
      display_section_completion_popup: this.displaySectionCompletionPopup,
      display_section_completion_popup_changed: undefined
    }
  },
  computed: {
    displaySectionCompletionPopup () {
      if(this.display_section_completion_popup_changed !== undefined) {
        console.log(this.display_section_completion_popup_changed)
        return this.display_section_completion_popup_changed
      }

      return this.show_section_completion_popup
    }
  },
  methods: {
    hideSectionCompletionPopup(){
      this.display_section_completion_popup_changed = false
    }
  }
}
</script>

<style scoped>

</style>

<template>
  <div class="motivational-message" v-if="motivation_message">
    <div class="motivational-message__popup fadeInUp" :class="this.message_color">
      <div class="memoji bounceIn">
        <img src="https://ddasf3j8zb8ok.cloudfront.net/craydel.com/images/assessment/lady.svg" width="163" height="163" alt="Thumbs up" />
      </div>
      <div class="message-label">
        <div :class="emoji_class"></div>
        <span>{{ motivation_message }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MotivationalMessage",
  props: {
    emoji_class: '',
    motivation_message: ''
  },
  data(){
    return{
      message_color: this.getBackGroundColor()
    }
  },
  methods:{
    getBackGroundColor(){
      Array.prototype.sample = function(){
        return this[Math.floor(Math.random()*this.length)];
      }

      return [
        'blue',
        'yellow',
        'orange',
        'purple'
      ].sample()
    }
  }
}
</script>

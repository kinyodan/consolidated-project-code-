const partyEmojiContainer = document.getElementById('partySVG');
const partyEmoji = bodymovin.loadAnimation({
  wrapper: partyEmojiContainer,
  animType: 'svg',
  loop: true,
  autoplay: true,
  path: 'https://ddasf3j8zb8ok.cloudfront.net/craydel.com/plugins/js/party-emoji.json'
});

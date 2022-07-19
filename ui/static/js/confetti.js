const svgContainer = document.getElementById('confettiSVG');
const animItem = bodymovin.loadAnimation({
  wrapper: svgContainer,
  animType: 'svg',
  loop: true,
  autoplay: true,
  path: 'https://ddasf3j8zb8ok.cloudfront.net/craydel.com/plugins/js/confetti.json'
});

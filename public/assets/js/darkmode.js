const options = {
  bottom: '16px', // default: '32px'
  right: '16px', // default: '32px'
  left: 'unset', // default: 'unset'
  time: '0.7s', // default: '0.3s'
  mixColor: '#FFF', // default: '#fff'
  backgroundColor: 'white', // default: '#fff'
  buttonColorDark: '#100f2c', // default: '#100f2c'
  buttonColorLight: '#fff', // default: '#fff'
  saveInCookies: true, // default: true,
  label: '🌓', // default: ''
  autoMatchOsTheme: true // default: true
}

function addDarkmodeWidget() {
  new Darkmode(options).showWidget();
}
window.addEventListener('load', addDarkmodeWidget);

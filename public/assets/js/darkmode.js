const options = {
  bottom: '16px', // default: '32px'
  right: '16px', // default: '32px'
  left: 'unset', // default: 'unset'
  time: '0.7s', // default: '0.3s'
  mixColor: '#FFF', // default: '#fff'
  backgroundColor: '#FFF', // default: '#fff'
  buttonColorDark: '#100f2c', // default: '#100f2c'
  buttonColorLight: '#fff', // default: '#fff'
  saveInCookies: true, // default: true,
  label: '🌓', // default: ''
  autoMatchOsTheme: false // default: true
}

const darkMode = new Darkmode(options)
function addDarkmodeWidget() {
  darkMode.showWidget();
  if(window.location.pathname == "/calendar") darkMode.button.addEventListener("click", () => 
  {
    location.reload();
  })
}
window.addEventListener('load', addDarkmodeWidget);

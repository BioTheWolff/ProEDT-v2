<?php
$site_title = isset($site_title) && !empty($site_title) ? $this->e($site_title) : 'Pro EDT';
$displayed_title = $site_title;

// setting up the page title if there is any
$page_title = isset($page_title) ? $this->e($page_title) : '';
if (!empty($page_title)) $displayed_title .= " | $page_title";

$neon = isset($container) ? $container->get(App\Services\Neon::class) : null;
$this->flashes = $neon->get();
?>

<html lang="fr">
    <head>
        <title><?= $displayed_title ?></title>
        <link rel="shortcut icon" href="/assets/favicon.ico" />
        <link rel="stylesheet" href="/assets/css/spectre.min.css" />
        <link rel="stylesheet" href="/assets/css/index.css" />

        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

        <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png" />
        <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png" />
        <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon-16x16.png" />
        <link rel="manifest" href="/site.webmanifest" />
    </head>
<body>
  <header class="navbar">
    <section class="navbar-section">
        <!-- Main page -->
        <a href="/" class="btn btn-link">Pro EDT</a>
    </section>
    <section class="navbar-center">
        <img src="/assets/img/logo.png" alt="LOGO">
    </section>
    <section class="navbar-section"></section>
  </header>

  <!-- Flash message -->
  <?php if($this->flashes): ?>
      <?php foreach ($this->flashes as $flash): ?>
          <div class="bg-<?= $flash['type'] ?>">
              <?= $flash['message'] ?>
          </div>
      <?php endforeach; ?>
  <?php endif; ?>

  <!-- Container -->
  <div class="container">
    <div id="app">
      <v-app>
        <v-app-bar app color="primary" dark>
          <div class="d-flex align-center">
            <h2>ProEDT</h2>
          </div>

          <v-spacer></v-spacer>

          <a href="/">
            <v-icon class="nav-icon">mdi-calendar</v-icon>
          </a>
          <a href="/settings">
            <v-icon class="nav-icon">mdi-settings</v-icon>
          </a>
        </v-app-bar>

          <div>
              <?= $this->section('content') ?>
          </div>

          <img v-if="this.loading" src="/assets/img/loading.gif" alt="Loading animation" id="loadingImg" />
          
          <v-alert dense text type="success" :value="alert.show" transition="slide-y-transition" id="validNotification">
            {{ alert.text }}
          </v-alert>
        </div>
      </v-app>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
  <script src="https://unpkg.com/vue-cookies@1.7.4/vue-cookies.js"></script>

  <script>
  new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data: function() {
      return {
        type: 'week',
        mode: 'stack',
        weekday: [1, 2, 3, 4, 5, 6, 0],
        value: '',
        events: [],
        loading: false,
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        groupe: undefined,
        alert: {
          text: "",
          show: false,
        }
      };
    },
    created() {
      if (this.isMobile()) this.type = 'day';
      const cookie_groupe = this.$cookies.get("groupe")
      if(cookie_groupe === null && window.location.pathname === "/")
      {
        alert("Vous n'avez pas de groupe, merci d'en selectionner après avoir cliqué sur 'OK'")
        window.location.href = '/settings';
      } 
      else this.groupe = cookie_groupe;
    },
    methods: {
      getEvents({
        start,
        end
      }) {
        const events = [];
        let firstDate = this.$refs.calendar.value;
        if (firstDate === '') {
          let ds = new Date();
          //firstDate = `${ds.getUTCFullYear()}-${ds.getMonth() + 1}-${ds.getDate()}`;
          firstDate = '2021-06-10'
        }
        this.loading = true;
        axios
          .get(`/api/ical/json/iut/${this.groupe}/${firstDate}`)
          .then((response) => {
            response.data.events.forEach((e) => {
              events.push({
                name: e.summary,
                location: `${this.joinV(e.location)}`,
                start: this.calenDate(e.start),
                end: this.calenDate(e.end),
                color: 'blue',
                timed: true,
                teachers: this.joinV(e.description.teachers.join(", "))
              });
            });
            this.loading = false;
          })
          .catch((e) => {
            this.loading = false;
            console.log(e);
          });
        this.events = events;
      },
      isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
            navigator.userAgent
        );
      },
      calenDate(icalStr) {
        // icalStr = '20110914T184000Z'
        let strYear = icalStr.substr(0, 4);
        let strMonth = parseInt(icalStr.substr(4, 2), 10) - 1;
        let strDay = icalStr.substr(6, 2);
        let strHour = parseInt(icalStr.substr(9, 2));
        let strMin = icalStr.substr(11, 2);
        let strSec = icalStr.substr(13, 2);

        return new Date(Date.UTC(strYear, strMonth, strDay, strHour, strMin, strSec));
      },
      showEvent ({ nativeEvent, event }) {
        const open = () => {
          this.selectedEvent = event
          this.selectedElement = nativeEvent.target
          requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
        }

        if (this.selectedOpen) {
          this.selectedOpen = false
          requestAnimationFrame(() => requestAnimationFrame(() => open()))
        } else {
          open()
        }

        nativeEvent.stopPropagation()
      },
      joinV(str)
      {
        if(Array.isArray(str)) return str.join(", ");
        else return str;
      },
      saveGroupe(v)
      {
        this.$cookies.set("groupe", v, "365d")
        this.show_alert("Votre groupe est " + v)
      },
      show_alert(text) {
        this.alert.text = text;
        this.alert.show = true;
        window.setInterval(() => {
          this.alert.show = false;
        }, 3000)
      }
    },
  });
  </script>

  <script>
    window.axeptioSettings = {
      clientId: "612a7e6e00e5cf4cbbe9c7fb",
      cookiesVersion: "proedt-base",
    };
    
    (function(d, s) {
      var t = d.getElementsByTagName(s)[0], e = d.createElement(s);
      e.async = true; e.src = "//static.axept.io/sdk.js";
      t.parentNode.insertBefore(e, t);
    })(document, "script");
  </script>
</body>

</html>
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

          <!--<router-link to="/">-->
          <v-icon class="nav-icon">mdi-calendar</v-icon>
          <!--</router-link>-->
          <!--<router-link to="/about">-->
          <v-icon class="nav-icon">mdi-information-outline</v-icon>
          <!--</router-link>-->
        </v-app-bar>

          <div>
              <?= $this->section('content') ?>
          </div>

          <img v-if="this.loading" src="/assets/img/loading.gif" alt="Loading animation" id="loadingImg" />
        </div>
      </v-app>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>

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
        loading: true,
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
      };
    },
    created() {
      if (this.isMobile()) this.type = 'day';
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
          .get(`/api/ical/json/iut/s2/${firstDate}`)
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
      }
    },
  });
  </script>
</body>

</html>
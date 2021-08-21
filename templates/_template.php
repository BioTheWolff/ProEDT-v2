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
        <link rel="shortcut icon" href="/assets/favicon.ico">
        <link rel="stylesheet" href="/assets/css/spectre.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
  </head>
<body>

<head>
  <title><?= $displayed_title ?></title>
  <link rel="shortcut icon" href="/assets/favicon.ico" />
  <link rel="stylesheet" href="/assets/css/spectre.min.css" />
  <link rel="stylesheet" href="/assets/css/main.css" />

  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet" />
  <link href="css/index.css" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

  <link rel="apple-touch-icon" sizes="180x180" href="/imgs/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/imgs/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/imgs/favicon-16x16.png" />
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

          <img v-if="this.loading" src="/imgs/loading.gif" alt="Loading animation" id="loadingImg" />
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
          var ds = new Date();
          firstDate = `${ds.getUTCFullYear()}-${ds.getMonth() + 1}-${ds.getDate()}`;
        }
        this.loading = true;
        axios
          .get(`/api/ical/json/iut/s2/${firstDate}`)
          .then((response) => {
            response.data.events.forEach((e) => {
              events.push({
                name: e.summary,
                start: this.calenDate(e.start),
                end: this.calenDate(e.end),
                color: 'blue',
                timed: true,
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
        if (
          /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
            navigator.userAgent
          )
        ) {
          return true;
        } else {
          return false;
        }
      },
      calenDate(icalStr) {
        // icalStr = '20110914T184000Z'
        var strYear = icalStr.substr(0, 4);
        var strMonth = parseInt(icalStr.substr(4, 2), 10) - 1;
        var strDay = icalStr.substr(6, 2);
        var strHour = parseInt(icalStr.substr(9, 2));
        var strMin = icalStr.substr(11, 2);
        var strSec = icalStr.substr(13, 2);

        const date = new Date(Date.UTC(strYear, strMonth, strDay, strHour, strMin, strSec));
        return date;
      },
    },
  });
  </script>
</body>

</html>
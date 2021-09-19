<?php
$this->layout('_template');


use App\Database\Interactions\UserInteraction;
use App\Services\Session\SessionInterface;

$user_is_connected = isset($container) && UserInteraction::is_user_connected($container->get(SessionInterface::class));

$ecole = "";
if (isset($_COOKIE["ecole"])) $ecole = $_COOKIE["ecole"];
?>

<?php if ($ecole == 'iut') { ?>
  <!--<div class="card">
    <div class="accordion">
      <input type="checkbox" id="accordion-1" name="accordion-checkbox" hidden>
      <label class="accordion-header" for="accordion-1">
        <i class="icon icon-arrow-right mr-1"></i>
        <strong>IMPORTANT</strong> Etape 2 du PROJET, date limite LUNDI 13/09 18h
      </label>
      <div class="accordion-body">
        <div class="card-body">
          Bonjour, <br>
          <br>
          Les groupes étant constitués, nous allons passer à la saisie des voeux. <br>
          <br>
          Le premier login de chaque groupe (ou le second si le premier est un doublant) <br>
          peut se connecter sur le site<br>
          <a href="https://webinfo.iutmontp.univ-montp2.fr/projets/">https://webinfo.iutmontp.univ-montp2.fr/projets/</a><br>
          <br>
          Et saisir exactement 5 voeux ordonnées. <br>
          <br>
          PS: attention, un sujet a été supprimé et un autre ajouté.<br>
          Rémi Coletta <br>
        </div>
      </div>
    </div>
  </div>-->
<?php } ?>


<div id="menu">
  <span id="menu-navi">
    <button type="button" class="btn btn-default btn-sm move-today" onclick="cal.today()">Aujourd'hui</button>
    <button type="button" class="btn btn-default btn-sm move-day" onclick="cal.prev()"><</button>
    <button type="button" class="btn btn-default btn-sm move-day" onclick="cal.next()">></button>
  </span>
  <span id="renderRange" class="render-range"></span>
</div>

<div id="calendar" style="height: fit-content;"></div>

<div id="loadingImg">
  <div class="water"></div>
</div>

    
<script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>

<script>

function joinV(str) {
  if (Array.isArray(str)) return str.join(", ");
  else return str;
};

function calenDate(icalStr) {
  // icalStr = '20110914T184000Z'
  let strYear = icalStr.substr(0, 4);
  let strMonth = parseInt(icalStr.substr(4, 2), 10) - 1;
  let strDay = icalStr.substr(6, 2);
  let strHour = parseInt(icalStr.substr(9, 2));
  let strMin = icalStr.substr(11, 2);
  let strSec = icalStr.substr(13, 2);

  return new Date(Date.UTC(strYear, strMonth, strDay, strHour, strMin, strSec));
}

function showLoading()
{
  document.getElementById("loadingImg").style.display = "block";
}

function unShowLoading()
{
  document.getElementById("loadingImg").style.display = "none";
}

const themeConfig = {
  'week.timegridOneHour.height': '40px',
  'week.timegridHalfHour.height': '20px',
};

  var cal = new tui.Calendar('#calendar', {
    theme: themeConfig,
    isReadOnly: true,
    useDetailPopup: true,
    defaultView: 'week',
    taskView: false,
    week: {
      startDayOfWeek: 1,
      hourStart: 7,
      hourEnd: 20
    },
    month: {
      startDayOfWeek: 1,
    }
  });

showLoading();
fetch('/api/json/iut/q1')
.then(function(response) {
  return response.json();
})
.then(function(res) {
  const events = [];
  res.events.forEach(event => 
  {
    events.push({
      id: event.uid,
      calendarId: event.uid,
      title: (event.homework ? "** " : "") + event.summary,
      location: `${this.joinV(event.location)}`,
      body: '<strong>Prof</strong>: ' + this.joinV(event.description.teachers.join(", ")) + 
        (event.homework ? "<br><strong>Devoir</strong>: " + event.homework : '') 
        <?php if ($user_is_connected) { ?>
          + "<br><a href=/homework/"+event.uid+">Modifier les devoirs</a>"
        <?php } ?>,
      category: 'time',
      dueDateClass: '',
      start: calenDate(event.start),
      end: calenDate(event.end),
      isReadOnly: true,
      bgColor: (event.homework ? "#7700ff" : "#0089c9"),
      borderColor: (event.homework ? "#0089c9" : "#6b00ff"),
      color: 'white'
    })
  });
  console.log(events);
  cal.createSchedules(events);
  unShowLoading();
});
</script>

<!--<div id="app">
  <v-app>
    <div>
      <v-main>
        <v-container>
          <v-sheet tile height="54" class="d-flex">
            <v-btn icon class="ma-2" @click="$refs.calendar.prev()">
              <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-title v-if="$refs.calendar">
              <v-btn :disabled="dialog" :loading="dialog" class="white--text" color="purple darken-2" @click="dialog = true">
                {{ $refs.calendar.title }}
              </v-btn>
              <v-dialog v-model="dialog" width="300">
                <v-date-picker v-model="picker" @click:date="onDateClick" first-day-of-week="1" locale="fr">
                </v-date-picker>
              </v-dialog>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="ma-2" @click="$refs.calendar.next()">
              <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
          </v-sheet>
          <v-sheet height="600">
            <v-calendar ref="calendar" v-model="picker" :weekdays="weekday" :type="type" first-time="7" locale="fr" interval-count="12" interval-height="40" :events="events" :event-overlap-mode="mode" :event-overlap-threshold="30" @change="getEvents" @click:event="showEvent" :now="picker" :event-color="getEventColor">
              <template v-slot:event="{ event }">
                <div class="pl-1">
                  <v-icon v-if="event.homework" color="yellow" dense>mdi-notebook</v-icon> <strong>{{ event.name }}</strong>
                  <br>
                  {{ event.location }}
                </div>
              </template>
            </v-calendar>

            <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement" offset-x>
              <v-card color="grey lighten-4" min-width="150px" flat>
                <v-toolbar :color="selectedEvent.color" dark>
                  <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                </v-toolbar>
                <v-card-text v-if="selectedElement" style="color: black; font-size: 14px;">
                  De {{ ("0" + selectedEvent.start.getHours()).slice(-2) }}h{{ ("0" +
                  selectedEvent.start.getMinutes()).slice(-2) }} à {{ ("0" + selectedEvent.end.getHours()).slice(-2)
                  }}h{{ ("0" + selectedEvent.end.getMinutes()).slice(-2) }}
                  <br>
                  Avec {{selectedEvent.teachers}}
                  <br>
                  En {{selectedEvent.location}}

                  <br>
                  <span v-if="selectedEvent.homework">
                    <br>
                    <strong>Devoir</strong>: {{selectedEvent.homework}}
                  </span>
                  <?php if ($user_is_connected) { ?>

                    <br>
                    <a :href="'/homework/' + selectedEvent.uid">Modifier les devoirs</a>
                  <?php } ?>
                </v-card-text>
                <v-card-actions>
                  <v-btn text color="secondary" @click="selectedOpen = false">
                    Fermer
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-menu>
          </v-sheet>

          <v-alert dense text :color="alert.color" :value="alert.show" transition="slide-y-transition" id="validNotification" v-html="alert.text">
          </v-alert>
        </v-container>
      </v-main>
    </div>

    <div v-if="this.loading" id="loadingImg">
      <div class="water"></div>
    </div>
</div>
</v-app>
</div>

<script src="/cdn/js/vue.js"></script>
<script src="/cdn/js/vuetify.js"></script>
<script src="/cdn/js/axios.min.js"></script>
<script src="/cdn/js/vue-cookies.js"></script>

<script>
  new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    data: function() {
      return {
        type: 'week',
        mode: 'stack',
        weekday: [1, 2, 3, 4, 5, 6, 0],
        events: [],
        loading: false,
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        groupe: undefined,
        alert: {
          text: "",
          show: false,
          color: 'green'
        },
        dialog: false,
        picker: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        ecole: '',
        alreadyFetch: false,
      };
    },
    created() {
      if (this.isMobile()) this.type = 'day';

      const cookie_ecole = this.$cookies.get("ecole");
      const cookie_groupe = this.$cookies.get("groupe");

      if (cookie_ecole === null || cookie_groupe === null) {
        alert("Vous n'avez pas de groupe/école, merci d'en selectionner après avoir cliqué sur 'OK'")
        window.location.href = '/settings';
      } else {
        this.ecole = cookie_ecole;
        this.groupe = cookie_groupe;
      }
    },
    methods: {
      getEvents({
        start,
        end
      }) {
        const events = [];
        let firstDate = this.picker;
        if (firstDate === '') {
          let ds = new Date();
          firstDate = `${ds.getFullYear()}-${("0" + (ds.getMonth() + 1)).slice(-2)}-${("0" + ds.getDate()).slice(-2)}`;
        }
        this.loading = true;
        axios
          .get(`/api/json/${this.ecole}/${this.groupe}/${firstDate}`)
          .then((response) => {
            response.data.events.forEach((e) => {
              events.push({
                name: e.summary,
                location: `${this.joinV(e.location)}`,
                start: this.calenDate(e.start),
                end: this.calenDate(e.end),
                color: 'blue',
                timed: true,
                teachers: this.joinV(e.description.teachers.join(", ")),
                homework: e.homework,
                uid: e.uid
              });
            });
            this.loading = false;

            if (!this.alreadyFetch) {
              this.alreadyFetch = true;
              setTimeout(() => {
                axios
                  .get(`/api/json/${this.ecole}/${this.groupe}/${firstDate}`)
                  .then((response2) => {
                    this.checkLastCalendarFetch(response2.data);
                  });
              }, 3000);
            }
            else
            {
              this.checkLastCalendarFetch(response.data);
            }
          })
          .catch((e) => {
            this.loading = false;
            if (e.response.status === 521) {
              this.show_alert("Le serveur est injoignable, merci de contacter un admin !", 'red');
            } else if (e.response.status === 500) {
              this.show_alert("Erreur 500 (Potentioellement aucun cours à afficher)", 'orange');
            } else if (e.response.status === 400) {
              this.show_alert("Erreur 400: Votre école/groupe est surement mal configuré ! <a href='/settings'>Paramètres</a>", 'orange');
            } else if (e.response.status === 404) {
              this.show_alert("Erreur 404: Un bug est survenu coté client, contactez les admins si cela persiste. <a href='/about'>Contacter</a>", 'orange');
            } else if (e.response.status !== 200) {
              this.show_alert(`Erreur serveur: ${e.response.status}`, 'red');
            }
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
      showEvent({
        nativeEvent,
        event
      }) {
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
      joinV(str) {
        if (Array.isArray(str)) return str.join(", ");
        else return str;
      },
      saveGroupe(v) {
        this.$cookies.set("groupe", v, "365d")
        this.show_alert("Votre groupe est " + v, 'green')
      },
      show_alert(text, color) {
        this.alert.text = text;
        this.alert.color = color;
        this.alert.show = true;
        window.setInterval(() => {
          this.alert.show = false;
        }, 5000)
      },
      onDateClick(date) {
        this.dialog = false;
        this.value = date;
      },
      getEventColor(event) {
        if (event.homework) return 'purple';
        else return 'blue';
      },
      checkLastCalendarFetch(eventData) {
        const gathered_at = eventData.gathered_at;
        const generated_at = eventData.generated_at;
        const diff = generated_at - gathered_at;
        
        if (diff >= 320 && diff < 600) this.show_alert(`Le serveur va actualiser l'EDT dans quelques instants.`, 'orange');
        else if (diff >= 600) this.show_alert(`Le serveur n'a pas pu récupérer l'EDT, il se pourrait que le serveur de votre école soit hors-ligne.`, 'red');
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
    var t = d.getElementsByTagName(s)[0],
      e = d.createElement(s);
    e.async = true;
    e.src = "//static.axept.io/sdk.js";
    t.parentNode.insertBefore(e, t);
  })(document, "script");
</script>-->

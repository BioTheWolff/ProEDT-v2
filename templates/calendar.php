<?php
$this->layout('_template');


use App\Database\Interactions\UserInteraction;
use App\Services\Session\SessionInterface;

$user_is_connected = isset($container) && UserInteraction::is_user_connected($container->get(SessionInterface::class));
if ($user_is_connected == false) $user_is_connected = false;
if (!isset($_COOKIE["ecole"]) || !isset($_COOKIE["groupe"])) {
  header("Location: /settings");
  exit();
}

$ecole = $_COOKIE["ecole"];
$groupe = $_COOKIE["groupe"];

?>

<!-- 
<?php if ($ecole == 'iut' && $groupe == 'q1') { ?>
  <div class="card">
    <div class="accordion">
      <input type="checkbox" id="accordion-1" name="accordion-checkbox" hidden>
      <label class="accordion-header" for="accordion-1">
        <i class="icon icon-arrow-right mr-1"></i>
        Cours de gestion d'entreprise du lundi avancé.
      </label>
      <div class="accordion-body">
        <div class="card-body">
          Tous les lundis, le cours de gestion d'entreprise commencera à <strong>15h15</strong>.
        </div>
      </div>
    </div>
  </div>
<?php } ?>
-->


<div id="menu" style="text-align: right;">
  <span id="menu-navi" style="width: 100%;">
    <button class="btn btn-primary btn-action" onclick="calPrev()" style="display: inline;"><i class="icon icon-arrow-left"></i></button>
    <input type="date" class="form-input input-sm" value="2002-02-20" style="-webkit-appearance: auto; width: auto; display: inline;" id="date-picker" onchange="changeDate(event);">
    <button class="btn btn-primary btn-action" onclick="calNext()"><i class="icon icon-arrow-right"></i></button>
  </span>
  <span id="renderRange" class="render-range"></span>
</div>

<div id="calendar" style="height: fit-content;"></div>

<a href="#url-infos" id="links-info"><button class="btn btn-primary btn-action btn-lg"><i class="icon icon-link"></i></button></a>
<div class="modal" id="url-infos">
  <a href="#" class="modal-overlay" aria-label="Close"></a>
  <div class="modal-container">
    <div class="modal-header">
      <a href="#" class="btn btn-clear float-right" aria-label="Close"></a>
      <div class="modal-title h5">Liens</div>
    </div>
    <div class="modal-body">
      <div class="content">
        Lien permanent : <a href="<?= $permalink ?? '' ?>"><?= $permalink ?? '' ?></a><br>
        Lien pour ajouter l'ICAL à google calendar: <a href="<?= $icslink ?? '' ?>"><?= $icslink ?? '' ?></a>
      </div>
    </div>
  </div>
</div>

<div id="loadingImg">
  <img src="/assets/img/loading.gif" width="500" style="max-width: 500px;">
</div>

<div class="toast toast-warning" id="validNotification" style="display:none; margin-left: 20px;">
  Lorem ipsum dolor sit amet, consectetur adipiscing elit.
</div>


<script src="/cdn/jquery/jquery-3.6.0.min.js"></script>
<script src="/cdn/tui/tui-code-snippet.min.js"></script>
<script src="/cdn/tui/tui-time-picker.min.js"></script>
<script src="/cdn/tui/tui-date-picker.min.js"></script>
<script src="/cdn/tui/tui-calendar.min.js"></script>
<script src="/cdn/moment/moment.js"></script>

<script>
  let alreadyFetch = false;
  let notificationElement = document.getElementById("validNotification");

  function checkLastCalendarFetch(eventData) {
    const gathered_at = eventData.gathered_at;
    const generated_at = eventData.generated_at;
    const diff = generated_at - gathered_at;

    if (diff >= 320 && diff < 600) this.show_alert(`Le serveur va actualiser lEDT dans quelques instants.`, 'orange');
    else if (diff >= 600) this.show_alert(`Le serveur na pas pu récupérer l'EDT, il se pourrait que le serveur de votre école soit hors-ligne.`, 'red');
  }

  function show_alert(text) {

    notificationElement.innerHTML = text;
    this.alert.show = true;
    notificationElement.style.display = "block";
    window.setInterval(() => {
      notificationElement.style.display = "none";
      notificationElement.innerHTML = "";
    }, 5000)
  }

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

  Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
  }

  function showLoading() {
    $("#loadingImg").fadeIn();
  }

  function unShowLoading() {
    $("#loadingImg").fadeOut();
  }

  const themeConfig = {
    'week.timegridOneHour.height': '40px',
    'week.timegridHalfHour.height': '20px',
    'common.backgroundColor': 'black',
  };

  var templates = {
    popupDetailLocation: function(schedule) {
      return schedule.location;
    },
    popupDetailUser: function(schedule) {
      return (schedule.attendees || []).join(', ').replace("|", ", ");
    },
    popupDetailBody: function(schedule) {
      return schedule.body;
    },
    milestone: function(schedule) {
      return '<span style="color:red;"><i class="fa fa-flag"></i> ' + schedule.title + '</span>';
    },
    time: function(schedule) {
      let t = schedule.title + "<br>" + schedule.location;
      if (schedule.raw) t = "<i class='mdi mdi-notebook' style='color: yellow;'></i> " + t;
      return t;
    },
    popupDetailDate: function(isAllDay, start, end) {
      start = moment(start.toDate());
      end = moment(end.toDate());
      const isSameDate = start.isSame(end, 'day');
      const endFormat = (isSameDate ? '' : 'DD.MM.YYYY ') + 'HH:mm';
      return (start.format('DD.MM.YYYY HH:mm') + ' - ' + end.format(endFormat));
    },
  };

  var cal = new tui.Calendar('#calendar', {
    theme: themeConfig,
    template: templates,
    useCreationPopup: true,
    useDetailPopup: true,

    isReadOnly: true,
    defaultView: 'week',
    taskView: false,
    week: {
      startDayOfWeek: 1,
      hourStart: 7,
      hourEnd: 20,
      daynames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    },
    month: {
      startDayOfWeek: 1,
    }
  });

  cal.on('afterRenderSchedule', function(event) {
    //reloadDisplayedDate();
  });

  function changeDate(event) {
    cal.setDate(new Date(event.target.value));
    reloadDisplayedDate();
  }

  function calNext()
  {
    cal.next();
    reloadDisplayedDate();
  }

  function calPrev()
  {
    cal.prev();
    reloadDisplayedDate();
  }

  function reloadDisplayedDate() 
  {
    let date = cal.getDate().toDate();
    date = date.addDays(1);
    $("#date-picker").val(date.toISOString().substring(0, 10));
  }

  const screenRatio = window.screen.height / window.screen.width;
  if (screenRatio >= 1) cal.changeView('day');
  
  showLoading();
  fetch('/api/json/<?php echo $ecole ?>/<?php echo $groupe ?>')
    .then(function(response) {
      reloadDisplayedDate();
      if (response.ok) {
        return response.json();
      } else {
        unShowLoading();
        if (response.status === 521) {
          this.show_alert("Le serveur est injoignable, merci de contacter un admin !");
        } else if (response.status === 500) {
          this.show_alert("Erreur 500 (Potentioellement aucun cours à afficher)");
        } else if (response.status === 400) {
          this.show_alert("Erreur 400: Votre école/groupe est surement mal configuré ! <a href='/settings'>Paramètres</a>", );
        } else if (response.status === 404) {
          this.show_alert("Erreur 404: Un bug est survenu coté client, contactez les admins si cela persiste. <a href='/about'>Contacter</a>");
        } else if (response.status !== 200) {
          this.show_alert(`Erreur serveur: ${response.status}`);
        }

        throw new Error('Something went wrong');
      }
    })
    .then(function(res) {
      const events = [];
      res.events.forEach(event => {
        events.push({
          id: event.uid,
          calendarId: event.uid,
          title: event.summary,
          location: `${this.joinV(event.location)}`,
          body: (event.homework ? "<strong>Devoir</strong>: " + event.homework + (<?php echo $user_is_connected ? 'true' : 'false'; ?> == true ? "<br>" : "") : "")
          <?php if ($user_is_connected) { ?> +
            "<a href=/homework/" + event.uid + ">Modifier les devoirs</a>"
          <?php } ?>,
          category: 'time',
          start: calenDate(event.start),
          end: calenDate(event.end),
          isReadOnly: true,
          bgColor: (event.homework ? "#7700ff" : "#0089c9"),
          borderColor: (event.homework ? "#0089c9" : "#6b00ff"),
          color: 'white',
          attendees: event.description.teachers,
          raw: event.homework,
        })
      });
      cal.createSchedules(events);
      unShowLoading();

      if (!this.alreadyFetch) {
        this.alreadyFetch = true;
        setTimeout(() => {
          fetch('/api/json/<?php echo $ecole ?>/<?php echo $groupe ?>')
            .then(function(response2) {
              return response2.json();
            })
            .then(function(res2) {
              this.checkLastCalendarFetch(res2);
            });
        }, 3000);
      } else {
        this.checkLastCalendarFetch(response.data);
      }

    });
</script>
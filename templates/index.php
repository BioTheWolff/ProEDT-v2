<?php
$this->layout('_template');

$ecole = "";
if (isset($_COOKIE["ecole"])) $ecole = $_COOKIE["ecole"];
?>

<div class="container">
  <div class="columns">

    <div class="column col-4 col-sm-12 col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title h5">Changelog: Dimanche 10/10/2021</strong></div>
          <div class="card-subtitle">Auteur: <span class="chip bg-primary">Nathan R.</span></div>
        </div>
        <div class="card-body">
          Bonjour,<br>
          Pour vous informer des derniers changements de ProEDT, je vous propose de lire cette news 😄<br>
          <br>
          [+] Calendrier des GEA<br>
          [+] Calendrier des TC<br>
          [+] Lien ICAL pour votre gestionnaire d'agenda (avec devoirs affichés)<br>
          [*] Page 'EDT' entièrement recodée en vanilla<br>
          [*] Nouvelle animation de chargement<br>
          [*] Fix des boutons de changements de semaine/jour<br>
          [*] Modification du fournisseur des statistiques de ProEDT<br>
          <br>
          Si vous souhaitez gérer les devoirs de votre groupe/classe, n'hésitez pas à me contacter via Discord (_Rtinox#4442) ou IRL.<br>
          Je m'excuse aussi pour la panne de vendredi 08/10/2021 au soir ayant rendu le site durement accessible pendant quelques minutes suite à une perte réseau de notre hébergeur.<br>
          Nous vous préparons quelques fonctionnalités pour les prochaines semaines qui ne devraient pas vous décevoir<br>
          <br>
          Vous souhaitant de passer une bonne semaine tout en restant ouvert à vos propositions et idées !
        </div>
      </div>
    </div>

    <div class="column col-4 col-sm-12 col-md-12">
      <div class="card">
        <div class="card-image">
          <img src="/assets/img/logo.png" class="img-responsive" width="100">
        </div>
        <div class="card-header">
          <div class="card-title h5">ProEDT</div>
          <div class="card-subtitle">Auteur: <span class="chip bg-primary">Admin</span></div>
        </div>
        <div class="card-body">
          ProEDT est enfin disponible dans sa nouvelle version !<br><br>
          <strong>Pour commencer</strong>, choisissez votre école ainsi que votre classe/groupe <a href="/settings">en cliquant ici</a><br>
          Votre <a href="/calendar">emploi du temps</a> est ensuite visible dans la barre de navigation sur l'onglet 'EDT'.<br>
          Si vous avez des questions, n'hésitez pas à regarder la page <a href="/about">'Informations'</a><br>
          <br><br>
          N'hésitez pas à nous <a href="/about">contacter</a> si vous avez des idées, des questions ou un soucis.<br>
          Vous verrez ici prochainement les informations à propos de votre école ou encore des informations diffusé par votre BDE par exemple.
        </div>
      </div>
    </div>

  </div>
</div>
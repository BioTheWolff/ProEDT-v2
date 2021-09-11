<?php
$this->layout('_template');

$ecole = "";
if (isset($_COOKIE["ecole"])) $ecole = $_COOKIE["ecole"];
?>

<div class="container">
  <div class="columns">

    <?php if ($ecole == 'iut') { ?>
      <div class="column col-4 col-sm-12 col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title h5">Projet Etape 2: saisie des voeux avant <strong>lundi 13 18h</strong></div>
            <div class="card-subtitle">Auteur: <span class="chip bg-error">Rémi Coletta</span></div>
          </div>
          <div class="card-body">
            Bonjour, <br>
            <br>
            Les groupes étant constitués, nous allons passer à la saisie des voeux. <br>
            <br>
            Le premier login de chaque groupe (ou le second si le premier est un doublant) peut se connecter sur le site<br>
            <a href="https://webinfo.iutmontp.univ-montp2.fr/projets/">https://webinfo.iutmontp.univ-montp2.fr/projets/</a><br>
            <br>
            Et saisir exactement 5 voeux ordonnées. <br>
            <br>
            PS: attention, un sujet a été supprimé et un autre ajouté.
          </div>
        </div>
      </div>
    <?php } ?>

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
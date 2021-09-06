<?php
$this->layout('_template');

$ecole = "";
if (isset($_COOKIE["ecole"])) $ecole = $_COOKIE["ecole"];
?>

<div class="container">
  <div class="columns">

    <?php if ($ecole == 'iut') { ?>
      <div class="column col-4 col-sm-12 col-md-6">
        <div class="card">
          <div class="card-image">
            <img src="https://media.discordapp.net/attachments/618466800371367936/880476194171134034/affiche_soiree_inte.png" class="img-responsive">
          </div>
          <div class="card-header">
            <div class="card-title h5">Soirée d'intégration</div>
            <div class="card-subtitle">Auteur: <span class="chip bg-warning">BDE</span></div>
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
          ProEDT est enfin disponible dans sa nouvelle version !<br>
          N'hésitez pas à nous <a href="/about">contacter</a> si vous avez des idées, des questions ou un soucis.<br>
          Vous verrez ici prochainement les informations à propos de l'IUT ou encore des informations diffusé par le BDE.
        </div>
      </div>
    </div>

  </div>
</div>
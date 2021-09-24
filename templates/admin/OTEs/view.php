<?php
$this->layout('_template');
?>

<div class="container">
  <div class="columns">
    <div class="column col-3">

      <div class="card p-centered" style="max-width: 400px;">
        <div class="card-header">
          <div class="card-title h5">Ajouter</div>
        </div>
        <div class="card-body">
          <form>
            <div class="form-group">
              <label class="form-label" for="input-example-1">Nom/Titre*</label>
              <input class="form-input" type="text" id="name" name="name" placeholder="Photo de classe" required>
            </div>

            <div class="form-group">
              <label class="form-label" for="input-example-1">Endroit/Localisation*</label>
              <input class="form-input" type="text" id="loc" name="loc" placeholder="Bat K" required>
            </div>

            <div class="form-group columns">
              <label class="form-label column col-12" for="input-example-1">Début*</label>
              <input class="form-input column col-6" type="date" id="start-date" name="start-date" placeholder="Date début" required>
              <input class="form-input column col-6" type="time" id="start-time" name="start-time" placeholder="Heure début" required>
            </div>

            <div class="form-group columns">
              <label class="form-label column col-12" for="input-example-1">Fin*</label>
              <input class="form-input column col-6" type="date" id="end-date" name="end-date" placeholder="Date fin">
              <input class="form-input column col-6" type="time" id="end-time" name="end-time" placeholder="Heure fin">
            </div>

            <div class="form-group">
              <label class="form-switch">
                <input type="checkbox" id="all-day" name="all-day">
                <i class="form-icon"></i> Journée entiere ?
              </label>
            </div>

            <button class="btn">Valider</button>
            <span>* = requis</span>
          </form>
        </div>
      </div>
    </div>


    <div class="container column col-8" style="padding: 0px;">
      <div class="columns">

        <?php
        $titres = array('Photo de classe', 'Soirée d\'inté', 'Soirée cinéma', 'CGJ', 'Pas d\'élec');
        $locs = array("Bat K", "K123", "Comédie");
        $descs = array("Le 21/02/2020 à 18h", "Le 20/02/02 à 16h", "Le 02/03/20 de 18h à 00h", "Le 12/02/20 de 8h à 12h", "Du 12/02/20 8h au 13/02/20 12h");

        for ($i = 0; $i < 9; $i++) {
          shuffle($titres);
          shuffle($locs);
          shuffle($descs);

          $titre = $titres[0];
          $loc = $locs[0];
          $desc = $descs[0];

        ?>

          <div class="column col-3">
            <div class="card">
              <div class="card-header">
                <div class="card-title h5"><?php echo $titre ?></div>
                <div class="card-subtitle text-gray"><?php echo $loc ?></div>
              </div>
              <div class="card-body">
                <?php echo $desc ?>
              </div>
              <div class="card-footer">
                <button class="btn btn-primary">Modifier</button>
                <button class="btn btn-error">Supprimer</button>
              </div>
            </div>
          </div>

        <?php
        }
        ?>



      </div>
    </div>
  </div>
</div>
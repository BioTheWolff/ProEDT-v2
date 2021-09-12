<?php
$this->layout('_template');
?>

<div class="container">
  <div class="columns">
    <div class="column col-auto">

      <div class="card p-centered" style="max-width: 400px;">
        <div class="card-header">
          <div class="card-title h5">Ajouter</div>
        </div>
        <div class="card-body">
          <form>
            <input class="form-input" type="text" id="input-example-1" placeholder="Nom/Titre">
            <input class="form-input" type="text" id="input-example-1" placeholder="Endroit/Localisation">
            <input class="form-input" type="date" id="input-example-1" placeholder="Date début">
            <input class="form-input" type="time" id="input-example-1" placeholder="Heure début">
            <input class="form-input" type="date" id="input-example-1" placeholder="Date fin">
            <input class="form-input" type="time" id="input-example-1" placeholder="Heure fin">

            <div class="form-group">
              <label class="form-switch">
                <input type="checkbox">
                <i class="form-icon"></i> Journée entiere ?
              </label>
            </div>

            <button class="btn">Valider</button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>
<?php
$this->layout('_template');
?>

<div class="card">
  <div class="card-header">
    <div class="card-title h5">Votre groupe</div>
    <div class="card-subtitle">Veuillez choisir votre groupe de TD pour accéder à l'emploi du temps, au devoirs et informations.<br>
      Une fois sélectionné, cliquez sur "enregistrer".</div>
  </div>
  <div class="card-body">
    <select class="form-select">
      <option disabled selected value>Groupe de TD</option>
      <option>s1</option>
      <option>s2</option>
      <option>s3</option>
      <option>s4</option>
      <option>s5</option>
      <option>s6</option>
      <option>q1</option>
      <option>q2</option>
      <option>q3</option>
      <option>q4</option>
      <option>q5</option>
    </select>
    <button class="btn">Enregistrer</button>
  </div>
</div>
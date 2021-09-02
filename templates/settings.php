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
    <select class="form-select" id="groupe-select">
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
    <button class="btn" onclick="saveGroup()">Enregistrer</button>
  </div>
</div>

<script>
  const groupe_seleect = document.getElementById("groupe-select");

  window.onload = function(e) {
    let groupe_cookie = getCookie("groupe");
    if(groupe_cookie != null) groupe_seleect.value = groupe_cookie;
  }


  function saveGroup() {
    if (groupe_seleect.value === "") return;
    else {
      setCookie("groupe", groupe_seleect.value, 365);
      document.location.href = "/";
    }
  }

  function setCookie(name, value, days) {
    var expires = "";
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

  function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

  function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  }
</script>
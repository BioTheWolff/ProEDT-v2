<?php
$this->layout('_template');

function array_to_string(array $a): string
{
    $res = "[";
    foreach ($a as $e) $res .= "\"$e\", ";
    return $res . "]";
}
?>

<div class="card p-centered" style="max-width: 400px;">
  <div class="card-header">
    <div class="card-title h5">Votre groupe</div>
    <div class="card-subtitle">Veuillez choisir votre groupe de TD pour accéder à l'emploi du temps, au devoirs et informations.<br>
      Une fois sélectionné, cliquez sur "enregistrer".</div>
  </div>
  <div class="card-body">
    <select class="form-select" id="ecole-select" onchange="on_ecole_selected()">
      <option disabled selected value>Ecole et filière</option>
    </select>

    <select class="form-select" id="groupe-select" disabled>
      <option disabled selected value>Groupe</option>
    </select>
    <button class="btn" onclick="saveGroup()">Enregistrer</button>
  </div>
</div>

<script>
  const ecole_select = document.getElementById("ecole-select");
  const groupe_select = document.getElementById("groupe-select");
  const promos = [
      <?php foreach ($groups_data ?? [] as $school_name => $school): ?>
      {
        text: "<?= $school['fancy_name'] ?>",
        api_code: "<?= $school_name ?>",
        groupes: <?= array_to_string($school['classes']) ?>
      },
      <?php endforeach; ?>
  ];

  window.onload = function(e) {
    for (let ecole in promos) {
      let option = document.createElement("option");
      option.innerText = promos[ecole].text;
      ecole_select.append(option);
    }

    let ecole_cookie = getCookie("ecole");
    if (ecole_cookie != null) {
      let ecole = promos.filter(promo => {
        return promo.api_code === ecole_cookie
      });
      ecole_select.value = ecole[0].text;
      groupe_select.disabled = false;

      on_ecole_selected();

      let groupe_cookie = getCookie("groupe");
      groupe_select.value = groupe_cookie;
    }

  }


  function saveGroup() {
    if (groupe_select.value === "" || groupe_select.value == "Groupe") return;
    else {
      console.log(groupe_select.value)
      let promo = promos.filter(promo => {
        return promo.text === ecole_select.value
      });
      setCookie("ecole", promo[0].api_code, 365);
      setCookie("groupe", groupe_select.value, 365);
      document.location.href = "/calendar";
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

  function on_ecole_selected() {
    clear_groupe_select();
    let selected = ecole_select.value;
    let promo = promos.filter(promo => {
      return promo.text === selected
    });
    let groupes = promo[0].groupes;

    for (let groupe in groupes) {
      let option = document.createElement("option");
      option.innerText = groupes[groupe];
      groupe_select.append(option);
      groupe_select.disabled = false;
    }
  }

  function clear_groupe_select() {
    groupe_select.disabled = true;
    removeOptions(groupe_select);

  function removeOptions(selectElement) {
   var i, L = selectElement.options.length - 1;
   for(i = L; i >= 0; i--) {
      selectElement.remove(i);
   }
}

    let option = document.createElement("option");
    option.disabled = true;
    option.selected = true;
    option.innerText = "Groupe";
    groupe_select.append(option)
  }

  function eraseCookie(name) {
    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
  }
</script>
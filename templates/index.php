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
          <div class="card-title h5">The END</div>
          <div class="card-subtitle">Auteur: <span class="chip bg-primary">Nathan R.</span> <span class="chip bg-success">Changelog</span></div>
        </div>
        <div class="card-body">
          Bonjour ! 👋<br>
          Comme vous le savez peut-être, c'est le moment où les deuxièmes années partent en stage, et donc les développeurs de ce site y compris 😥
          <br>
          Le projet ne continuera pas l'année prochaine (il reste en ligne jusqu'à Juillet 2022), nous nous sommes mis d'accord pour dire que nous n'aurions pas le temps de gérer un site sans être sur place, il vous faudra donc trouver une alternative malheureusement.<br>
          Nous vous remercions énormément pour tout l'intérêt que vous avez apporté à ce projet, jamais je n'aurais pensé que celui-ci aille aussi loin.<br>
          Un énorme merci à vous en espérant que vous avez adoré ce projet ! ❤
          <br>
          A la prochaine, prenez soin de vous !
        </div>
      </div>
    </div>

    <?php if($ecole == "iut") { ?>
      <div class="column col-4 col-sm-12 col-md-12">
      <div class="card">
        <div class="card-image">
          <img src="/assets/img/2022.png" class="img-responsive" width="100">
        </div>
        <div class="card-header">
          <div class="card-title h5">Nouveaux edt (Informatique)</strong></div>
          <div class="card-subtitle">Auteur: <span class="chip bg-primary">Nathan R.</span> <span class="chip bg-success">EDT</span></div>
        </div>
        <div class="card-body">
          Hey,<br>
          Les edt pour le 2° et 4° semestre sont arrivés !
          <br>
          Seulement pour les deuxièmes années, il vous faudra changer de groupe avec (G1, G2, etc ...) dès que vous aurez votre groupe
          <br>
          📎 <a href="/settings">Changer de groupe</a>
          <br><br>
          👋
        </div>
      </div>
    </div>
    <?php } ?>

    <div class="column col-4 col-sm-12 col-md-12">
      <div class="card">
        <div class="card-header">
          <h1>🌓</h1>
          <div class="card-title h5">Mode sombre (aka darkmode)</strong></div>
          <div class="card-subtitle">Auteur: <span class="chip bg-primary">Nathan R.</span> <span class="chip bg-success">Changelog</span></div>
        </div>
        <div class="card-body">
          Hey ! 👋<br>
          Le mode sombre est enfin disponible, plus besoin de plisser vos yeux quand vous regardez votre emploi du temps à 1 heure du matin dans le noir.
          <br>
          Pour l'activer, regardez en bas à droite de la page 🌓 !
          <br><br>
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

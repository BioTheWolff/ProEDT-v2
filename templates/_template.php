<?php
$site_title = isset($site_title) && !empty($site_title) ? $this->e($site_title) : 'Pro EDT';
$displayed_title = $site_title;

// setting up the page title if there is any
$page_title = isset($page_title) ? $this->e($page_title) : '';
if (!empty($page_title)) $displayed_title .= " | $page_title";
?>

<html>
  <head>
        <title><?= $displayed_title ?></title>
        <link rel="shortcut icon" href="/assets/favicon.ico">
        <link rel="stylesheet" href="/assets/css/spectre.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
  </head>
<body>

<header class="navbar">
    <section class="navbar-section">
        <!-- Main page of the SSO auth server -->
        <a href="/" class="btn btn-link">Pro EDT</a>
    </section>
    <section class="navbar-center">
        <!-- You can put a link to your main website here (say you have accounts.example.com, you could point to example.com here) -->
        <img src="/assets/img/logo.png" alt="LOGO">
    </section>
    <section class="navbar-section">

    </section>
</header>


<!-- Container -->
<div class="container">
    <?=$this->section('content')?>
</div>

</body>
</html>
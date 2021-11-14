<?php

use App\Database\Interactions\UserInteraction;
use App\Services\Session\SessionInterface;
use App\Services\Neon;

$site_title = isset($site_title) && !empty($site_title) ? $this->e($site_title) : 'Pro EDT';
$displayed_title = $site_title;

// setting up the page title if there is any
$page_title = isset($page_title) ? $this->e($page_title) : '';
if (!empty($page_title)) $displayed_title .= " | $page_title";

$is_connected = isset($container) && UserInteraction::is_user_connected($container->get(SessionInterface::class));
$neon = isset($container) ? $container->get(Neon::class) : null;
$this->flashes = $neon->get();
?>

<html lang="fr">

<head>
    <title><?= $displayed_title ?></title>
    <meta charset="UTF-8">

    <link rel="stylesheet" type="text/css" href="/cdn/tui/tui-calendar.min.css" />

    <!-- If you use the default popups, use this. -->
    <link rel="stylesheet" type="text/css" href="/cdn/tui/tui-date-picker.css" />
    <link rel="stylesheet" type="text/css" href="/cdn/tui/tui-time-picker.css" />

    <link href="/cdn/css/materialdesignicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/calendar.css" />

    <link rel="shortcut icon" href="/assets/favicon.ico" />
    <link rel="stylesheet" href="/assets/css/spectre.min.css" />
    <link rel="stylesheet" href="/assets/css/icons.min.css" />
    <link rel="stylesheet" href="/assets/css/index.css" />

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon-16x16.png" />
    <link rel="manifest" href="manifest-proedt.json">


    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u = "//analytics.rtinox.fr/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '1']);
            var d = document,
                g = d.createElement('script'),
                s = d.getElementsByTagName('script')[0];
            g.async = true;
            g.src = u + 'matomo.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <!-- End Matomo Code -->
</head>

<body>
    <header class="navbar">
        <section class="navbar-section">
            <a href="/" class="btn btn-link">Accueil</a>
            <a href="/calendar" class="btn btn-link">EDT</a>
            <a href="/settings" class="btn btn-link">Paramètres</a>
        </section>
        <section class="navbar-center">
            <a href="/" class="navbar-brand mr-2">ProEDT</a>
        </section>
        <section class="navbar-section">
            <a href="/about" class="btn btn-link">Informations</a>
            <?php if ($is_connected) : ?>
                <a href="/logout" class="btn">Logout</a>
            <?php else : ?>
                <a href="/login" class="btn">Login</a>
            <?php endif; ?>
        </section>
    </header>

    <main id="container">
        <?php if (!empty($this->flashes)) : ?>
            <?php foreach ($this->flashes as $flash) : ?>
                <div class="flash <?= $flash['type'] == 'warning' ? 'bg-warning' : 'bg-error' ?>">
                    <?= $flash['message'] ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?= $this->section('content') ?>
    </main>

    <button class="btn btn-primary btn-action btn-lg" id="theme-mode-btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brightness-half" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 9a3 3 0 0 0 0 6v-6z" />
            <path d="M6 6h3.5l2.5 -2.5l2.5 2.5h3.5v3.5l2.5 2.5l-2.5 2.5v3.5h-3.5l-2.5 2.5l-2.5 -2.5h-3.5v-3.5l-2.5 -2.5l2.5 -2.5z" />
        </svg>
    </button>

    <footer class="text-center">
        &copy; Copyright 2021 - Nathan R. & Fabien Z.
        <?php if (isset($is_production) && !$is_production) : ?>
            <div class="bg-warning absolute bottom-0 p-5 w-screen text-center" style="width: 100vw">
                WARNING: DEVELOPMENT MODE ENABLED
            </div>
        <?php endif; ?>
    </footer>

    <script>
        const themeButton = document.getElementById("theme-mode-btn");
        themeButton.addEventListener('click', () => 
        {
            document.body.classList.toggle('theme--dark');
        });
    </script>
</body>

</html>
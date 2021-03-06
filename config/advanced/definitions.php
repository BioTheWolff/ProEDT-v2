<?php

use App\Database\Interactions\GroupsInteraction;
use App\Database\Interactions\HomeworkInteraction;
use App\Database\Interactions\UserInteraction;
use App\Database\Managers\GroupsManager;
use App\Database\Managers\HomeworkManager;
use App\Database\Managers\OTEManager;
use App\Database\Managers\UserManager;
use App\Services\Ical\IcalManager;
use App\Services\Ical\IcalProvider;
use App\Services\Neon;
use App\Services\Session\Palladium;
use App\Services\Session\SessionInterface;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

return [
    'templates_root' => dirname(__DIR__) . '/../templates',

    // league/plates Engine
    Engine::class => function (ContainerInterface $c) {
        $e = new Engine($c->get('templates_root'));
        $e->addData([
            'container' => $c,
            'site_title' => $c->get('site.title'),
            'is_production' => PRODUCTION
        ]);
        return $e;
    },

    // Ical service
    IcalProvider::class => DI\Autowire(),
    IcalManager::class => DI\Autowire(),

    // Database
    PDO::class => function (ContainerInterface $c) {
        $adapter = $c->get('database.type');
        $host = $c->get("database.host");
        $dbname = $c->get("database.dbname");
        $port = $c->get('database.port');

        try
        {
            return new PDO("$adapter:host=$host;dbname=$dbname;port=$port",
                $c->get('database.username'),
                $c->get('database.password'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            );
        }
        catch (PDOException $exception)
        {
            return null;
        }
    },

    // Database Interactions & Managers
    UserInteraction::class => DI\Autowire(),
    HomeworkInteraction::class => DI\Autowire(),
    GroupsInteraction::class => DI\Autowire(),
    UserManager::class => DI\Autowire(),
    GroupsManager::class => DI\Autowire(),
    HomeworkManager::class => DI\Autowire(),
    OTEManager::class => DI\Autowire(),

    // Session & flash services
    SessionInterface::class => DI\Autowire(Palladium::class),
    Neon::class => DI\Autowire(),
];
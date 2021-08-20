<?php

use App\Services\Ical\IcalManager;
use App\Services\Ical\IcalProvider;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

return [
    'templates_root' => dirname(__DIR__) . '/../templates',

    // league/plates Engine
    Engine::class => function (ContainerInterface $c) {
        $e = new Engine($c->get('templates_root'));
        $e->addData([
            'site_title' => $c->get('site.title'),
            'is_production' => PRODUCTION
        ]);
        return $e;
    },

    // Ical service
    IcalProvider::class => DI\Autowire(),
    IcalManager::class => DI\Autowire()
];
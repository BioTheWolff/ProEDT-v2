<?php

use App\Services\IcalManager;
use App\Services\IcalProvider;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

return [
    'templates_root' => dirname(__DIR__) . '/../templates',

    Engine::class => function (ContainerInterface $c) {
        $e = new Engine($c->get('templates_root'));
        $e->addData([
            'site_title' => $c->get('site.title'),
            'is_production' => PRODUCTION
        ]);
        return $e;
    },

    IcalProvider::class => DI\Autowire(),
    IcalManager::class => DI\Autowire()
];
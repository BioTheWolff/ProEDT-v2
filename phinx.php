<?php

require 'vendor/autoload.php';

$container = (new App\Factories\ContainerFactory)();

return
[
    'paths' => [
        'migrations' => [
            'App/Database/mgmt/migrations'
        ],
        'seeds' => [
            'App/Database/mgmt/seeds'
        ],
    ],
    'environments' => [
        'default_migration_table' => '_phinxlog',
        'default_environment' => 'database',
        'database' => [
            'adapter'   => $container->get('database.type'),
            'host'      => $container->get('database.host'),
            'name'      => $container->get('database.dbname'),
            'user'      => $container->get('database.username'),
            'pass'      => $container->get('database.password'),
            'port'      => $container->get('database.port'),
            'charset'   => 'utf8'
        ]
    ],
    'version_order' => 'creation'
];

<?php

use App\Controllers\APIController;
use App\Controllers\UserController;
use App\Controllers\VisualController;

return [
    // routes mapping
    'advanced.routes' => [
        // index page
        'index' => [ VisualController::class, "index" ],
        // all user-related routes
        'visual' => [
            'controller' => VisualController::class,
            'routes' => [
                'calendar' => "calendar"
            ]
        ],
        'user' => [
            'controller' => UserController::class,
            'routes' => [
                'login' => [
                    'GET'  => 'login_GET',
                    'POST' => 'login_POST'
                ],
                'logout' => 'logout'
            ]
        ],
        // API
        'api' => [
            'controller' => APIController::class,
            'routes' => [
                'ical.json' => "ical_json",
                'ical.raw' => "ical_raw"
            ]
        ]
        // etc.
    ]
];
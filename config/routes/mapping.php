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
                'settings' => "settings",
                'about' => "about",
                "calendar" => "calendar",
                "homework" => ["GET" => "homework_get", "POST" => "homework_post"],
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
                'json' => "json",
                'ics' => "ics"
            ]
        ]
        // etc.
    ]
];
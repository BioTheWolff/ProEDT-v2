<?php

use App\Controllers\APIController;
use App\Controllers\VisualController;

return [
    // index route path; should remain like that unless errors appear
    'path.index' => "/",

    // the routes
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
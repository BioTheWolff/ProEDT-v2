<?php

return [
    'advanced.path_prefix' => "path",
    'advanced.api_prefix' => "/api",

    // index route path; should remain like that unless errors appear
    'path.index' => "/",

    // Paths to the endpoints
    'path.visual.settings' => '/settings',
    'path.visual.about' => '/about',
    'path.visual.calendar' => '/calendar/{group}',

    'path.user.login' => '/login',
    'path.user.logout' => '/logout',

    'path.api.ical.json' => [
        "/api/ical/json/{major}/{group}",
        "/api/ical/json/{major}/{group}/{date}"
    ],
    'path.api.ical.raw' => "/api/ical/raw/{major}/{group}"
];
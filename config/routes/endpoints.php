<?php

return [
    'advanced.path_prefix' => "path",
    'advanced.api_prefix' => "/api",

    // index route path; should remain like that unless errors appear
    'path.index' => "/",

    // Paths to the endpoints
    'path.visual.settings' => '/settings',
    'path.visual.about' => '/about',
    'path.visual.calendar' => [
        '/calendar',
        '/calendar/{school}/{group}',
    ],
    'path.visual.homework' => '/homework/{uid}',

    'path.user.login' => '/login',
    'path.user.logout' => '/logout',

    'path.api.json' => [
        "/api/json/{major}/{group}",
        "/api/json/{major}/{group}/{date}"
    ],
    'path.api.ics' => "/api/ics/{major}/{group}",
    'path.api.manifest' => "/manifest-proedt.json",
];
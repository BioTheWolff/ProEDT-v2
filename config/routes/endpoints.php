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
        "/api/json/{school}/{group}",
        "/api/json/{school}/{group}/{date}"
    ],
    'path.api.ics' => "/api/ics/{school}/{group}",
    'path.api.manifest' => "/manifest-proedt.json",

    'path.OTEs.viewall' => '/events/view',
    'path.OTEs.edit' => '/events/edit/{uid}',
    'path.OTEs.delete' => '/events/delete/{uid}',
];
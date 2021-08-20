<?php

return [
    'advanced.path_prefix' => "path",

    // index route path; should remain like that unless errors appear
    'path.index' => "/",

    // Paths to the endpoints
    'path.visual.calendar' => '/calendar',

    'path.api.ical.json' => [
        "/api/ical/json/{major}/{group}",
        "/api/ical/json/{major}/{group}/{date}"
    ],
    'path.api.ical.raw' => "/api/ical/raw/{major}/{group}"
];
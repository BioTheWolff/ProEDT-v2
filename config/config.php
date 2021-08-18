<?php

return [
    // Site title
    'site.title' => 'Pro EDT',

    // Paths to the endpoints
    'path.visual.calendar' => '/calendar',

    'path.api.ical.json' => [
        "/api/ical/json/{major}/{group}",
        "/api/ical/json/{major}/{group}/{date}"
    ],
    'path.api.ical.raw' => "/api/ical/raw/{major}/{group}"
];
<?php

return [
    'ics.data' => [
        'school_name' => [
            /* leave empty if there is a whole different URL base for each class,
            else put the base URL and only fill data in each class 'url' entry */
            'url_base' => "https://yourdomain.com/path/to/calendar/?data=",

            'classes' => [
                [
                    'name' => "<class name here>",
                    'year' => 1 /* year 1 */,
                    'url' => "<url here>"
                ],
                // etc
            ],
        ],
    ],

    'database.type'     => "",
    'database.host'     => "",
    'database.dbname'   => "",
    'database.username' => "",
    'database.password' => "",
    'database.port'     => 0,

    'api.users' => [],
];
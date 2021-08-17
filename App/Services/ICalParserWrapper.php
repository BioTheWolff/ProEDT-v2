<?php

namespace App\Services;

use ICal\ICal;

class ICalParserWrapper
{

    public static function get_ical()
    {
        $ical = new ICal(__DIR__ . "/ics/test.ical", [
            'defaultTimeZone'             => 'UTC+1',
            'disableCharacterReplacement' => true
        ]);

        try {
            return $ical->eventsFromRange("2021-06-14", "2021-06-18");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
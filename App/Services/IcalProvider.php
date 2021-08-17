<?php

namespace App\Services;

use ICal\ICal;

class IcalProvider
{

    private const REFRESH_THRESHOLD = 10; // in minutes


    private static function should_refresh(string $path)
    {
        $tstamp = filemtime($path);
        if ($tstamp === false)
        {
            die("File was never edited or doesn't exist.");
        }

        $edited_at = new \DateTime();
        $edited_at->setTimestamp($tstamp);

       $dateDiff = $edited_at->diff(new \DateTime(), true);
       $deltaMinutes = ((new \DateTime())->setTimeStamp(0)->add($dateDiff)->getTimeStamp()) / 60;

       return $deltaMinutes >= self::REFRESH_THRESHOLD;
    }


    public static function get_ical()
    {
        $path = __DIR__ . "/ics/test.ical";

        $delta = self::should_refresh($path);

        $ical = new ICal($path, [
            'defaultTimeZone'             => 'UTC+1',
            'disableCharacterReplacement' => true
        ]);

        try {
            // return $ical->eventsFromRange("2021-06-14", "2021-06-18");
            return $delta;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
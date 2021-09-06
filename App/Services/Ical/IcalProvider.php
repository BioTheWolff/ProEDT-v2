<?php

namespace App\Services\Ical;

use DateInterval;
use DateTime;
use Exception;
use ICal\ICal;

/**
 * Provides the sanitized Ical events to the APIs
 * Uses {@link IcalManager}
 * @package App\Services
 * @author Fabien Zoccola
 */
class IcalProvider
{
    private $manager;
    private $ical;

    public function __construct(IcalManager $manager)
    {
        $this->manager = $manager;

        $this->ical = new ICal(false, [
            'defaultTimeZone'             => 'UTC+1',
            'disableCharacterReplacement' => true
        ]);
    }

    /**
     * Refreshes the Ical instance by either reading from the file, after file refresh if necessary
     */
    private function refresh_ical_instance(string $school, string $group)
    {
        // only refresh if needed
        if ($this->manager->should_refresh($school, $group))
        {
            $this->manager->refresh_ical($school, $group);
        }

        $this->ical->initFile($this->manager->get_file_name($school, $group));
    }

    /**
     * Returns the timestamp at which a group's calendar was last updated
     *
     * @param string $school the school name
     * @param string $group the group name
     * @return int the timestamp the file was last updated at
     */
    public function gathered_timestamp(string $school, string $group): int
    {
        if (!file_exists($this->manager->get_file_name($school, $group))) return -1;
        return filemtime($this->manager->get_file_name($school, $group));
    }

    public function group_exists(string $school, string $group): bool
    {
        return $this->manager->group_exists($school, $group);
    }

    public function is_date_valid(string $date): bool
    {
        if ($date == null) return false;
        $m = [];

        $res = preg_match("/([0-9]{4})-([0-9]{2})-([0-9]{2})/", $date, $m);
        if ($res == false) return false;

        // cast to int
        foreach ($m as $k => $v) $m[$k] = (int)$v;

        $is_leap_year = $m[1] % 400 == 0 || ($m[1] % 4 == 0 && $m[1] % 100 != 0);
        $is_in_month_with_31_days = in_array($m[2], [1, 3, 5, 7, 8, 10, 12]);

        return
            $m[1] >= 2020 && // YEAR: makes sure we don't go too far back in time
            $m[2] >= 1 && $m[2] <= 12 && // MONTH
            $m[3] >= 1 && // DAY low limit
            $m[3] <= ($is_leap_year ? 28 : ($is_in_month_with_31_days ? 31 : 30)); // DAY high limit
    }

    public function date_is_start_of_week(string $date): bool
    {
        return DateTime::createFromFormat("Y-m-d", $date)->format("N") == '1';
    }

    /**
     * @throws Exception
     */
    public function get_start_of_week(string $date): string
    {
        if ($this->date_is_start_of_week($date)) return $date;

        $dt = DateTime::createFromFormat("Y-m-d", $date);

        $day = (int)$dt->format("N");
        $sub = $day-1;

        return DateTime::createFromFormat("Y-m-d", $date)
            ->sub(new DateInterval("P" . $sub . "D"))
            ->format("Y-m-d");
    }

    /**
     * Returns the Ical events of the requested group
     *
     * @param string $school the school name
     * @param string $group the group name
     * @param string|null $date the date starting the week
     * @return array|false false if the group doesn't exist, else the array of events
     */
    public function get_ical(string $school, string $group, string $date = null): ?array
    {
        if (!$this->group_exists($school, $group)) return null;

        $this->refresh_ical_instance($school, $group);

        try {
            if (!is_null($date))
            {
                $start = $this->get_start_of_week($date);

                // evaluate the "end of range" date
                $end = DateTime::createFromFormat("Y-m-d", $start)
                    ->add(new DateInterval("P5D"))
                    ->format("Y-m-d");

                // return the events in the evaluated range
                return $this->ical->eventsFromRange($start, $end);
            }
            else return $this->ical->events();
        } catch (Exception $e) {
            return null;
        }
    }

    public function ical_raw(string $school, string $group)
    {
        $this->refresh_ical_instance($school, $group);
        return file_get_contents($this->manager->get_file_name($school, $group));
    }
}
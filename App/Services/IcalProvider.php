<?php

namespace App\Services;

use DateInterval;
use DateTime;
use Exception;
use Psr\Container\ContainerInterface;

/**
 * Provides the sanitized Ical events to the APIs
 * Uses {@link IcalManager}
 * @package App\Services
 * @author Fabien Zoccola
 */
class IcalProvider
{
    private $container;
    private $manager;
    private $ical;

    public function __construct(ContainerInterface $container, IcalManager $manager)
    {
        $this->container = $container;
        $this->manager = $manager;

        $this->ical = new ResetableIcal(false, [
            'defaultTimeZone'             => 'UTC+1',
            'disableCharacterReplacement' => true
        ]);
    }

    /**
     * Refreshes the Ical instance by either reading from the file, after file refresh if necessary
     * @param string $group the group name to read the calendar from
     */
    private function refresh_ical_instance(string $group)
    {
        // only refresh if needed
        if ($this->manager->should_refresh($group))
        {
            $this->manager->refresh_ical($group);
        }

        $this->ical->initFile($this->manager->file_name_from_group($group));
    }

    /**
     * Returns the timestamp at which a group's calendar was last updated
     * @param string $group the group name
     * @return int the timestamp the file was last updated at
     */
    public function gathered_timestamp(string $group): int
    {
        if (!file_exists($this->manager->file_name_from_group($group))) return -1;
        return filemtime($this->manager->file_name_from_group($group));
    }

    public function group_exists(string $group)
    {
        return $this->manager->group_exists($group);
    }

    public function is_date_valid(string $date): bool
    {
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

    /**
     * Returns the Ical events of the requested group
     *
     * @param string $group the group name
     * @param string|null $date the date starting the week
     * @return array|false false if the group doesn't exist, else the array of events
     */
    public function get_ical(string $group, string $date = null)
    {
        if (!$this->manager->group_exists($group) || (!is_null($date) && !$this->is_date_valid($date)))
        {
            return false; // malformed request
        }

        $this->refresh_ical_instance($group);

        try {
            if (!is_null($date))
            {
                // evaluate the "end of range" date
                $end = DateTime::createFromFormat("Y-m-d", $date)
                    ->add(new DateInterval("P4D"))
                    ->format("Y-m-d");

                // return the events in the evaluated range
                return $this->ical->eventsFromRange($date, $end);
            }
            else return $this->ical->events();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
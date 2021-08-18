<?php

namespace App\Services;

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

    /**
     * Returns the Ical events of the requested group
     * @param string $group the group name
     * @return array|false false if the group doesn't exist, else the array of events
     */
    public function get_ical(string $group)
    {
        if (!$this->manager->group_exists($group))
        {
            // bad group
            return false;
        }

        $this->refresh_ical_instance($group);

        try {
            return $this->ical->eventsFromRange("2021-06-14", "2021-06-18");
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
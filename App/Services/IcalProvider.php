<?php

namespace App\Services;

use Exception;
use Psr\Container\ContainerInterface;

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

    private function refresh_ical_instance(string $group)
    {
        // only refresh if needed
        if ($this->manager->should_refresh($group))
        {
            $this->manager->refresh_ical($group);
        }

        $this->ical->initFile($this->manager->file_name_from_group($group));
    }

    public function gathered_timestamp(string $group): int
    {
        if (!file_exists($this->manager->file_name_from_group($group))) return -1;
        return filemtime($this->manager->file_name_from_group($group));
    }

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
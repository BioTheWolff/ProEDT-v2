<?php

namespace App\Services;

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

    private function refresh_ical_instance()
    {
        // only refresh if needed
        if ($this->manager->should_refresh())
        {
            $this->manager->refresh_ical();
        }

        $this->ical->initFile(IcalManager::FILE_PATH);
    }

    public function get_ical()
    {
        $this->refresh_ical_instance();

        try {
            return $this->ical->eventsFromRange("2021-06-14", "2021-06-18");
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
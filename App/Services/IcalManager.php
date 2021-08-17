<?php

namespace App\Services;


use Psr\Container\ContainerInterface;

class IcalManager
{
    public const ICS_PATH = __DIR__ . "/ics/";
    public const FILE_EXT = ".ical";

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function group_exists(string $name)
    {
        return preg_match("/^iut-s[1-6]$/gi", $name);
    }

    public function file_name_from_group(string $name)
    {
        [$major, $group] = explode("-", trim($name));

        $major = strtolower($major);
        $group = strtolower($group);

        return self::ICS_PATH . "$major-$group" . self::FILE_EXT;
    }

    public function data_key_from_group(string $name)
    {
        [$major, $group] = explode("-", trim($name));

        $major = strtolower($major);
        $group = strtolower($group);

        return "ics.url.data.$major.$group";
    }

    public function should_refresh(string $group): bool
    {
        try
        {
            $stamp = filemtime($this->file_name_from_group($group));
        }
        catch (\ErrorException $e) // if the file is not found
        {
            $this->refresh_ical($group);
            return false;
        }

        if ($stamp === false)
        {
            // the file doesnt exist, create it
            $this->refresh_ical($group);
            return false;
        }

        $edited_at = new \DateTime();
        $edited_at->setTimestamp($stamp);

        $dateDiff = $edited_at->diff(new \DateTime(), true);
        $deltaMinutes = ((new \DateTime())->setTimeStamp(0)->add($dateDiff)->getTimeStamp()) / 60;

        return $deltaMinutes >= $this->container->get("ics.refresh_threshold");
    }

    public function refresh_ical(string $group)
    {
        $url = $this->container->get("ics.url.base.iut") . $this->container->get($this->data_key_from_group($group));

        $curl = \curl_init($url);
        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = \curl_exec($curl);

        \curl_close($curl);

        if ($result != false)
        {
            $file = fopen($this->file_name_from_group($group), "w");

            $time = new \DateTime();
            $time = $time->format(DATE_ATOM);

            fwrite($file, "$result\n");
            fwrite($file, "edited at $time\n");
        }
    }
}
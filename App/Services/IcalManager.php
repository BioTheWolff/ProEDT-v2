<?php

namespace App\Services;


use Psr\Container\ContainerInterface;

class IcalManager
{
    public const FILE_PATH = __DIR__ . "/ics/IUT.ical";

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function should_refresh(): bool
    {
        $stamp = filemtime(self::FILE_PATH);
        if ($stamp === false)
        {
            die("File was never edited or doesn't exist.");
        }

        $edited_at = new \DateTime();
        $edited_at->setTimestamp($stamp);

        $dateDiff = $edited_at->diff(new \DateTime(), true);
        $deltaMinutes = ((new \DateTime())->setTimeStamp(0)->add($dateDiff)->getTimeStamp()) / 60;

        return $deltaMinutes >= $this->container->get("ics.refresh_threshold");
    }

    public function refresh_ical()
    {
        $url = $this->container->get("ics.url.iut.base") . $this->container->get("ics.url.iut.data");

        $curl = \curl_init($url);
        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = \curl_exec($curl);

        \curl_close($curl);

        if ($result != false)
        {
            $file = fopen(self::FILE_PATH, "w");

            $time = new \DateTime();
            $time = $time->format(DATE_ATOM);

            fwrite($file, "$result\n");
            fwrite($file, "edited at $time\n");
        }
    }
}
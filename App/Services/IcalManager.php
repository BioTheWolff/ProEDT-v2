<?php

namespace App\Services;


use DateTime;
use Psr\Container\ContainerInterface;
use function curl_close;
use function curl_exec;
use function curl_init;
use function curl_setopt;

/**
 * Manages and sanitizes the Ical when updating them
 * Has util functions for {@link IcalProvider}
 * @package App\Services
 * @author Fabien Zoccola
 */
class IcalManager
{
    public const ICS_PATH = __DIR__ . "/ics/";
    public const FILE_EXT = ".ical";

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Check if a group name exists
     * @param string $name the group name
     * @return bool whether the group exists or not
     */
    public function group_exists(string $name): bool
    {
        return preg_match("/^iut-s[1-6]$/i", $name) != false;
    }

    /**
     * Returns the ICS file name from the given group
     * @param string $name the group name
     * @return string the file name
     */
    public function file_name_from_group(string $name): string
    {
        [$major, $group] = explode("-", trim($name));

        $major = strtolower($major);
        $group = strtolower($group);

        return self::ICS_PATH . "$major-$group" . self::FILE_EXT;
    }

    /**
     * Returns the container's data key (entry, of form ics.url.data.SOMETHING) from the given group name
     * @param string $name the group name
     * @return string the data key
     */
    public function data_key_from_group(string $name): string
    {
        [$major, $group] = explode("-", trim($name));

        $major = strtolower($major);
        $group = strtolower($group);

        return "ics.url.data.$major.$group";
    }

    /**
     * Checks if the given group's calendar needs to be refreshed from URL
     * @param string $group the group name
     * @return bool whether the current group's calendar should be refreshes
     */
    public function should_refresh(string $group): bool
    {
        if (!file_exists($this->file_name_from_group($group)))
        {
            $this->refresh_ical($group);
            return false;
        }

        $stamp = filemtime($this->file_name_from_group($group));
        if ($stamp === false)
        {
            // the file doesnt exist, create it
            $this->refresh_ical($group);
            return false;
        }

        $edited_at = new DateTime();
        $edited_at->setTimestamp($stamp);

        $dateDiff = $edited_at->diff(new DateTime(), true);
        $deltaMinutes = ((new DateTime())->setTimeStamp(0)->add($dateDiff)->getTimeStamp()) / 60;

        return $deltaMinutes >= $this->container->get("ics.refresh_threshold");
    }

    /**
     * Refreshes the Ical from a given URL to a given file
     * (see {@link data_key_from_group}, {@link file_name_from_group})
     * @param string $group the group name
     */
    public function refresh_ical(string $group)
    {
        $url = $this->container->get("ics.url.base.iut") . $this->container->get($this->data_key_from_group($group));

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        curl_close($curl);

        if ($result != false)
        {
            $cleaned = $this->clean_ical_lines($result);

            $file = fopen($this->file_name_from_group($group), "w");

            $time = new DateTime();
            $time = $time->format(DATE_ATOM);

            fwrite($file, "$cleaned\n");
            fwrite($file, "edited at $time\n");
        }
    }

    /**
     * Cleans up and sanitizes the Ical output before writing it to the file
     * @param string $str the content pulled by {@link refresh_ical}
     * @return string the edited file content
     */
    private function clean_ical_lines(string $str)
    {
        return preg_replace(
            [
                // CONTENT CLEANUP
                // [1] put the description into one line only
                "/(.*)\r?\n (.*)/U",

                // [2] remove the unused tags
                "/DTSTAMP:[0-9]{8}T[0-9]{6}Z/",
                "/SEQUENCE:[0-9]+/",
                "/LAST-MODIFIED:[0-9]{8}T[0-9]{6}Z/",
                "/CREATED:[0-9]{8}T[0-9]{6}Z/",
                "/UID:[a-zA-Z0-9]+/",

                // [3] sanitise the description
                "/Transféré/",
                "/#.*[0-9]{4}#/",
                "/\(Ex.*\)/",

                // STYLE REPLACEMENT
                // [4] remove the many newlines in the file
                "/(\r?\n)+/",

                // [5] format the description
                "/(DESCRIPTION):\\\\n(.*)(A[1-2]-Semestre-[1-2]|S[1-6]|G[1-4]|Q[1-4])(.*)\\\\n/",

                // [6] another layer of formatting to remove leading and trailing "\n"
                "/^(DESCRIPTION:.* \| Professeurs): (\\\\n)*(.*?)(\\\\n)*$/m",

                // [7] merge the many description's "\n" literals into one
                "/(\\\\n){2,}/",
                // then replace the remaining "\n" literals
                "/\\\\n/",

                // [8] also remove the many spaces between the names
                "/ {2,}/"
            ],
            [
                "$1$2", // [1]
                "", "", "", "", "", // [2]
                "", "", "", // [3]
                "\n", // [4]
                "$1:Groupe: $3 | Professeurs: $2$4", // [5]
                "$1: $3", // [6]
                "\\\\n", ", ", // [7]
                " ", // [8]
            ],
            $str
        );
    }
}
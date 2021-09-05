<?php

namespace App\Services\Ical;


use App\Database\Interactions\GroupsInteraction;
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

    /**
     * @var ContainerInterface $container
     * @var GroupsInteraction $interaction
     */
    private $container;
    private $interaction;

    public function __construct(ContainerInterface $container, GroupsInteraction $interaction)
    {
        $this->container = $container;
        $this->interaction = $interaction;
    }

    /**
     * Check if a group name exists
     * @return bool whether the group exists or not
     */
    public function group_exists(string $school, string $group): bool
    {
        return $this->interaction->is_school_group_available($school, $group);
    }

    /**
     * Returns the ICS file name from the given group
     * @return string the file name
     */
    public function get_file_name(string $school, string $group): string
    {
        $school = strtolower($school);
        $group = strtolower($group);

        return self::ICS_PATH . "$school-$group" . self::FILE_EXT;
    }

    /**
     * Checks if the given group's calendar needs to be refreshed from URL
     * @return bool whether the current group's calendar should be refreshes
     */
    public function should_refresh(string $school, string $group): bool
    {
        if (!file_exists($this->get_file_name($school, $group)))
        {
            $this->refresh_ical($school, $group);
            return false;
        }

        $stamp = filemtime($this->get_file_name($school, $group));
        if ($stamp === false)
        {
            // the file doesn't exist, create it
            $this->refresh_ical($school, $group);
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
     * (see {@link get_file_name})
     */
    public function refresh_ical(string $school, string $group)
    {
        $url = $this->interaction->get_school_group_url($school, $group);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        curl_close($curl);

        if ($result != false && str_contains($result, "BEGIN:VCALENDAR"))
        {
            $cleaned = $this->clean_ical_lines($result);

            $file = fopen($this->get_file_name($school, $group), "w");

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
    private function clean_ical_lines(string $str): string
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

                // [3] sanitise the description
                "/(Transféré|A valider)/",
                "/#.*[0-9]{4}#/",
                "/\(Ex.*\)/",

                // STYLE REPLACEMENT
                // [4] remove the many newlines in the file
                "/(\r?\n)+/",

                // [5] merge the many description's "\n" literals into one
                "/(\\\\n){2,}/",
                // then replace the remaining "\n" literals
                "/\\\\n/",

                // [6] also remove the many spaces between the names
                "/ {2,}/",

                // [7] format the description
                "/(DESCRIPTION):\|(A[1-2]-Semestre-[1-4]|S[1-6]|G[1-5]|Q[1-5])(.*)\|/",

                // [8] another layer of formatting to remove leading and trailing "\n"
                "/^(DESCRIPTION:.* \| Professeurs): \|*(.*?)\|*$/m",

                // [9] adding a placeholder teacher when none was provided
                "/^(DESCRIPTION:.* \| Professeurs:) +$/m",
            ],
            [
                "$1$2", // [1]
                "", "", "", "", // [2]
                "", "", "", // [3]
                "\n", // [4]
                "\\\\n", "|", // [5]
                " ", // [6]
                "$1:Groupe: $2 | Professeurs: $3", // [7]
                "$1: $2", // [8]
                "$1 Aucun professeur indiqué", // [9]
            ],
            $str
        );
    }
}
<?php

namespace App\Controllers;

use App\Database\Managers\HomeworkManager;
use App\Services\Ical\IcalProvider;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class APIController extends AbstractController
{

    private function format_events_to_json(array $events, int $gathered_at): ?array
    {
        if (empty($events)) return null;
        $final = [
            'generated_at' => time(),
            'gathered_at' => $gathered_at,
            'events' => []
        ];

        // collectings UIDs
        $uids = [];
        foreach ($events as $e) if (!is_null($e->uid)) $uids[] = $e->uid;

        // requesting homework
        $homework = $this->container->get(HomeworkManager::class)->fetch_homeworks_from_uids($uids);

        foreach ($events as $e)
        {
            [$group, $teachers] = explode(" | ", $e->description);

            $group = str_replace("Groupe: ", "", $group);
            $teachers = explode(", ", str_replace("Professeurs: ", "", $teachers));

            $final['events'][] = [
                "summary" => $e->summary,
                "start" => $e->dtstart,
                "end" => $e->dtend,
                "description" => [
                    "group" => $group,
                    "teachers" => $teachers
                ],
                "location" => str_contains($e->location, ",")
                    ? explode(",", $e->location)
                    : $e->location,
                "uid" => $e->uid,
                "homework" => !is_null($e->uid) ? $homework[$e->uid] ?? '' : ''
            ];
        }

        return $final;
    }

    public function json(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $provider = $this->container->get(IcalProvider::class);
        $group_name = $args['major'] . '-' . $args['group'];
        $date = $args['date'] ?? null;

        // if date was passed to the API
        if (!is_null($date) && !$provider->is_date_valid($date))
        {
            // refuse if date isn't correct
            return new JsonResponse([
                'status' => $this->container->get("api.status.error.ical.date"),
                'error' => 'invalid date format (expected YYYY-mm-dd)'
            ], 400);
        }

        // refuse if group isn't correct
        if (!$provider->group_exists($group_name))
        {
            return new JsonResponse([
                'status' => $this->container->get("api.status.error.ical.group"),
                'error' => 'invalid or unkown major/group combination'
            ], 400);
        }

        // request the calendar and check for unexpected refusal
        $calendar = $provider->get_ical($group_name, $date);
        if ($calendar == null)
        {
            return new JsonResponse([
                'status' => $this->container->get("api.status.error.internal"),
                'error' => 'unexpected refusal of calendar request'
            ], 500);
        }

        // prepare the response
        $gathered_at = $provider->gathered_timestamp($group_name);
        $events = $this->format_events_to_json($calendar, $gathered_at);

        if ($events === null)
        {
            return new EmptyResponse();  // No events
        }

        // evaluate success or partial
        if (!is_null($date))
        {
            // success if the current date is the same as the one requested, partial if we had to change it
            $status = [
                "status" => $provider->date_is_start_of_week($date)
                    ? $this->container->get("api.status.success")
                    : $this->container->get("api.status.partial.date")
            ];
            if (($d = $provider->get_start_of_week($date)) != $args['date'])
            {
                $status['partial'] = "Set date to monday [$d], was [" . $args['date'] . "]";
            }
        }
        else $status = ["status" => $this->container->get("api.status.success")];

        return new JsonResponse($status + $events);
    }

    public function ics(ServerRequestInterface $request, array $args): ResponseInterface
    {
        /**
         * @var IcalProvider $provider
         */
        $provider = $this->container->get(IcalProvider::class);
        $group_name = $args['major'] . '-' . $args['group'];

        if (!$provider->group_exists($group_name)) return new EmptyResponse(404);

        $content = $provider->ical_raw($group_name);
        if (!$content) return new EmptyResponse(500);


        // PROCESSING THE CALENDAR AND ADDING HOMEWORK
        /**
         * @var HomeworkManager $manager
         */
        $manager = $this->container->get(HomeworkManager::class);

        // matching the UIDs
        $matches = [];
        preg_match_all("/UID:(.*)\n/", $content, $matches, PREG_SET_ORDER);

        // storing them then querying the database to get matching homeworks
        $uids = [];
        foreach ($matches as $match) $uids[] = $match[1];
        $homework = $manager->fetch_homeworks_from_uids($uids);

        if (empty($homework))
        {
            // no homework to display, returning the basic calendar
            return new TextResponse($content, 200,
                [
                    "Content-Type" => "text/calendar",
                    "Content-Disposition" => "attachment; filename=$group_name.ics;",
                ]);
        }

        // creating the replacements arrays and injecting homework in the description
        $in = [];
        $out = [];
        foreach ($homework as $uid => $c)
        {
            $in[] = "/(DESCRIPTION:.*)\n(UID:$uid)/";
            $out[] = "$1 | Devoirs: $c\n$2";
        }

        // replacing the contents as wanted above
        $final_calendar = preg_replace($in, $out, $content);
        if (is_null($final_calendar)) return new EmptyResponse(500);

        // RETURNING THE CALENDAR
        return new TextResponse($final_calendar, 200,
            [
                "Content-Type" => "text/calendar",
                "Content-Disposition" => "attachment; filename=$group_name.ics;",
            ]);
    }
}
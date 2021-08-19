<?php

namespace App\Controllers;

use App\Services\IcalProvider;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
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
                    : $e->location
            ];
        }

        return $final;
    }

    public function ical_json(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $provider = $this->container->get(IcalProvider::class);
        $group_name = $args['major'] . '-' . $args['group'];
        $date = isset($args['date']) ? $args['date'] : null;

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
}
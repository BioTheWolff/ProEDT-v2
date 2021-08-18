<?php

namespace App\Controllers;

use App\Services\IcalProvider;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class APIController extends AbstractController
{
    private function prepare_send_json(array $data, int $status = 200)
    {
        return new JsonResponse($data, $status);
    }

    private function format_events_to_json(array $events, int $gathered_at)
    {
        if (empty($events)) return [];

        $final = [
            'status' => "success",
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
                "location" => $e->location
            ];
        }

        return $final;
    }

    public function json(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $provider = $this->container->get(IcalProvider::class);

        $group_name = $args['major'] . '-' . $args['group'];
        $calendar = $provider->get_ical($group_name);

        $gathered_at = $provider->gathered_timestamp($group_name);

        if ($calendar === false)
        {
            // bad group, create json and send it
            return $this->prepare_send_json([
                'status' => "error",
                'error' => 'unkown major/group combination provided'
            ], 404);
        }

        return $this->prepare_send_json($this->format_events_to_json($calendar, $gathered_at));
    }
}
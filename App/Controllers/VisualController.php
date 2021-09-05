<?php

namespace App\Controllers;

use App\Database\Interactions\GroupsInteraction;
use App\Database\Interactions\HomeworkInteraction;
use App\Database\Interactions\UserInteraction;
use App\Database\LayeredAbstractMigration;
use App\Services\Neon;
use App\Services\Session\SessionInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class VisualController extends AbstractController
{

    function index(): ResponseInterface
    {
        return $this->html_render("index");
    }

    function settings(): ResponseInterface
    {
        /** @var GroupsInteraction $interaction */
        $interaction = $this->container->get(GroupsInteraction::class);

        return $this->html_render("settings", [
            'groups_data' => $interaction->get_school_groups()
        ]);
    }

    function about(): ResponseInterface
    {
        return $this->html_render("about");
    }

    function calendar(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if (!empty($args)) {
            setcookie("ecole", $args['school'], 0, "/");
            setcookie("groupe", $args['group'], 0, "/");
        }
        return $this->html_render("calendar");
    }

    function homework_get(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if (!UserInteraction::is_user_connected($this->container->get(SessionInterface::class)))
        {
            return new RedirectResponse('/login');
        }

        $interaction = $this->container->get(HomeworkInteraction::class);
        return $this->html_render("user/homework", [
            "homework_uid" => $args['uid'],
            "homework_content" => $interaction->fetch_homework($args['uid'])->content ?? '',
        ]);
    }

    function homework_post(ServerRequestInterface $request): ResponseInterface
    {
        if (!UserInteraction::is_user_connected($this->container->get(SessionInterface::class)))
        {
            return new RedirectResponse('/login');
        }

        /**
         * @var Neon $neon
         * @var HomeworkInteraction $interaction
         */
        $neon = $this->container->get(Neon::class);
        $interaction = $this->container->get(HomeworkInteraction::class);
        $body = $request->getParsedBody();

        if (!UserInteraction::checkFormHasFields(['uid', 'content'], $body))
        {
            $neon->error("Form is not full");
            return $this->html_render("user/homework", [
                "homework_uid" => $body['uid'],
                "homework_content" => $interaction->fetch_homework($body['uid'])->content ?? '',
            ]);
        }

        if (!HomeworkInteraction::can_update_homework($body['content']))
        {
            $max = LayeredAbstractMigration::HOMEWORK_CONTENT_LENGTH;
            $neon->error("Les devoirs ne doivent pas faire plus de $max caractères.");
            return $this->html_render("user/homework", [
                "homework_uid" => $body['uid'],
                "homework_content" => $interaction->fetch_homework($body['uid'])->content ?? '',
            ]);
        }

        if (!$interaction->update_homework($body['uid'], $body['content']))
        {
            $neon->error("Les devoirs n'ont pas pu être mis à jour");
            return $this->html_render("user/homework", [
                "homework_uid" => $body['uid'],
                "homework_content" => $interaction->fetch_homework($body['uid'])->content ?? '',
            ]);
        }

        return new RedirectResponse("/calendar/");
    }
}
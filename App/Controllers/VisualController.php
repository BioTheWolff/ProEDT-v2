<?php

namespace App\Controllers;

use App\Database\Interactions\UserInteraction;
use App\Services\Session\SessionInterface;
use League\Route\Http\Exception\ForbiddenException;
use League\Route\Http\Exception\UnauthorizedException;
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
        return $this->html_render("settings");
    }

    function about(): ResponseInterface
    {
        return $this->html_render("about");
    }

    function calendar(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if (!empty($args)) setcookie("groupe", $args['group'], 0, "/");
        return $this->html_render("calendar");
    }

    /**
     * @throws ForbiddenException
     */
    function homework_get(ServerRequestInterface $request, array $args): ResponseInterface
    {;
        if (!UserInteraction::is_user_connected($this->container->get(SessionInterface::class)))
        {
            throw new ForbiddenException();
        }

        return $this->html_render("homework", [
            "homework_uid" => $args['uid'],
            "homework_content" => ""
        ]);
    }
}
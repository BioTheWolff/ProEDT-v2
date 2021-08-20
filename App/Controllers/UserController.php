<?php

namespace App\Controllers;

use App\Database\Interactions\UserInteraction;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends AbstractController
{
    public function login_GET(): ResponseInterface
    {
        return $this->html_render("user/login");
    }

    public function login_POST(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $interaction = $this->container->get(UserInteraction::class);

        if (!$interaction->checkFormFull(['username', 'password'], $body))
        {
            return $this->html_render("user/login");
        }

        if (!$interaction->checkUserValid($body))
        {
            return $this->html_render("user/login");
        }

        if (!$interaction->loginUser($body))
        {
            return $this->html_render("user/login");
        }

        return new RedirectResponse('/');
    }

    public function logout(): ResponseInterface
    {
        $this->container->get(UserInteraction::class)->logoutUser();
        return new RedirectResponse('/');
    }
}
<?php

namespace App\Controllers;

use App\Database\Interactions\UserInteraction;
use App\Services\Session\SessionInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends AbstractController
{
    private function check_not_connected()
    {
        $session = $this->container->get(SessionInterface::class);
        if (!is_null($session->get('__user')))
        {
            // the user is connected
            return new RedirectResponse('/');
        }
        else return null;
    }

    public function login_GET(): ResponseInterface
    {
        if (($rr = $this->check_not_connected()) != null) return $rr;
        return $this->html_render("user/login");
    }

    public function login_POST(ServerRequestInterface $request): ResponseInterface
    {
        if (($rr = $this->check_not_connected()) != null) return $rr;

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
<?php

namespace App\Controllers;

use App\Database\Interactions\UserInteraction;
use App\Services\Session\SessionInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OTEController extends AbstractController
{
    public function view(): ResponseInterface
    {
        if (!UserInteraction::is_user_connected($this->container->get(SessionInterface::class)))
            return new RedirectResponse("/");

        return $this->html_render("admin/OTEs/view");
    }

    public function edit_GET(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if (!UserInteraction::is_user_connected($this->container->get(SessionInterface::class)))
            return new RedirectResponse("/");

        return $this->html_render("admin/OTEs/edit");
    }

    public function delete_GET(ServerRequestInterface $request, array $args): ResponseInterface
    {
        if (!UserInteraction::is_user_connected($this->container->get(SessionInterface::class)))
            return new RedirectResponse("/");

        return $this->html_render("admin/OTEs/delete");
    }
}
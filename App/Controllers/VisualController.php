<?php

namespace App\Controllers;

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
        setcookie("groupe", $args['group'], 0, "/");
        return $this->html_render("index");
    }
}
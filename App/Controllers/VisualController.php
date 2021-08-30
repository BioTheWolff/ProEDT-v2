<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

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
}
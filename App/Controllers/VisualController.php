<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class VisualController extends AbstractController
{

    function index(): ResponseInterface
    {
        return $this->html_render("index");
    }

    function calendar(): ResponseInterface
    {
        return $this->html_render("calendar");
    }
}
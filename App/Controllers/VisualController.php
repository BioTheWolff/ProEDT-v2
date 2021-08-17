<?php

namespace App\Controllers;

use App\Services\ICalParserWrapper;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;

class VisualController extends AbstractController
{
    private function html_render(string $path, array $engine_data = null)
    {
        return new HtmlResponse($this->templateEngine->render($path, $engine_data));
    }

    function index(): ResponseInterface
    {
        return $this->html_render("index");
    }

    function calendar(): ResponseInterface
    {
        return $this->html_render("calendar", ["cal" => ICalParserWrapper::get_ical()]);
    }
}
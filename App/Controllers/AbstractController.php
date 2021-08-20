<?php

namespace App\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;

class AbstractController
{

    protected $container;
    protected $templateEngine;

    public function __construct(ContainerInterface $container, Engine $engine)
    {
        $this->container = $container;
        $this->templateEngine = $engine;
    }

    protected function html_render(string $path, array $engine_data = [])
    {
        return new HtmlResponse($this->templateEngine->render($path, $engine_data));
    }

}
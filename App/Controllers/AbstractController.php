<?php

namespace App\Controllers;

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

}
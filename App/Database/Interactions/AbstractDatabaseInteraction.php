<?php

namespace App\Database\Interactions;

use Psr\Container\ContainerInterface;

class AbstractDatabaseInteraction
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var PDO $database;
     */
    private $database;

    public function __construct(ContainerInterface $container, ?PDO $database)
    {
        $this->container = $container;
        $this->database = $database;
    }

    public function is_available(): bool
    {
        return !is_null($this->database);
    }
}
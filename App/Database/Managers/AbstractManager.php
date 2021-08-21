<?php


namespace App\Database\Managers;


use PDO;

abstract class AbstractManager
{
    /**
     * @var PDO $connection
     */
    protected $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public static function is_available(?PDO $pdo) {
        return $pdo != null;
    }

    public function self_available()
    {
        return $this->connection != null;
    }

}
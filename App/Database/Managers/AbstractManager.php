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

    public static function is_available(?PDO $pdo): bool
    {
        return $pdo != null;
    }

    public function self_available(): bool
    {
        return $this->connection != null;
    }

}
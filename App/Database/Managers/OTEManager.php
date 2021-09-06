<?php

namespace App\Database\Managers;


class OTEManager extends AbstractManager
{
    public function get_OTEs(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM onetimeevents");
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
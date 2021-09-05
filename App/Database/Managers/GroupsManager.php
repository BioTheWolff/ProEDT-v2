<?php

namespace App\Database\Managers;

use function App\e;

class GroupsManager extends AbstractManager
{
    public function select_groups_order_by_school(): array
    {
        $stmt = $this->connection->prepare(
            "SELECT school, fancy_name, g.name 
                        FROM groups g JOIN schools s on g.school = s.name
                        WHERE g.url IS NOT NULL
                        GROUP BY school, fancy_name, g.name
                        ORDER BY school, g.name"
        );

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_group_url(string $school, string $group)
    {
        $e_school = e($school);
        $e_group = e($group);

        $stmt = $this->connection->prepare(
            "SELECT url FROM groups 
                        WHERE url IS NOT NULL AND school = :s AND name = :g");
        $stmt->bindParam("s", $e_school);
        $stmt->bindParam("g", $e_group);

        $stmt->execute();
        $stmt->fetch();
    }
}
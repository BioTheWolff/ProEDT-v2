<?php

namespace App\Database\Managers;

use function App\e;

class HomeworkManager extends AbstractManager
{
    public function fetch_homework_from_uid(string $uid)
    {
        $e_uid = e($uid);

        $stmt = $this->connection->prepare("SELECT * FROM homework WHERE uid = :uid");
        $stmt->bindParam("uid", $e_uid);

        $stmt->execute();

        return $stmt->fetch();
    }

    public function fetch_homeworks_from_uids(array $uids): array
    {
        function compose($id, $uid, $content): array
        {
            return [$uid, $content];
        }

        $in  = str_repeat('?,', count($uids) - 1) . '?';
        $stmt = $this->connection->prepare("SELECT * FROM homework WHERE uid IN ($in)");

        $stmt->execute($uids);

        $result = $stmt->fetchAll(\PDO::FETCH_FUNC, "\App\Database\Managers\compose");

        // format in form [uid => content]
        $final = [];
        foreach ($result as $item) $final[$item[0]] = $item[1];

        return $final;
    }

    public function update_homework_from_uid(string $uid, string $content): bool
    {
        $e_uid = e($uid);
        $e_content = e($content);

        $stmt = $this->connection->prepare(
            "INSERT INTO homework (uid, content) VALUES (:uid, :content)
                        ON CONFLICT (uid) DO UPDATE SET content = :content"
        );
        $stmt->bindParam("uid", $e_uid);
        $stmt->bindParam("content", $e_content);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete_homework_content(string $uid): bool
    {
        $e_uid = e($uid);

        $stmt = $this->connection->prepare("DELETE FROM homework WHERE uid = :uid");
        $stmt->bindParam("uid", $e_uid);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
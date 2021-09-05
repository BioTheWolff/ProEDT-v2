<?php

namespace App\Database\Interactions;

use App\Database\LayeredAbstractMigration;
use App\Database\Managers\HomeworkManager;
use function App\e;

class HomeworkInteraction
{
    /**
     * @var HomeworkManager $manager;
     */
    private $manager;

    public function __construct(HomeworkManager $manager)
    {
        $this->manager = $manager;
    }

    public function fetch_homework(string $uid): ?object
    {
        $result = $this->manager->fetch_homework_from_uid($uid);
        return $result !== false ? $result : null;
    }

    public static function can_update_homework(string $content): bool
    {
        return strlen(e(trim($content))) <= LayeredAbstractMigration::HOMEWORK_CONTENT_LENGTH;
    }

    public function update_homework(string $uid, string $content): bool
    {
        if (!self::can_update_homework($content)) return false;

        if (empty(trim($content))) return $this->manager->delete_homework_content($uid);
        else return $this->manager->update_homework_from_uid($uid, trim($content));
    }
}
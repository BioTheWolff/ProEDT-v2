<?php
declare(strict_types=1);

use App\Database\LayeredAbstractMigration;

final class CreateHomeworkTable extends LayeredAbstractMigration
{
    public function change(): void
    {
        $this->table("homework")
            ->addColumn("uid", "string", ['limit' => self::EVENT_UID_LENGTH])
            ->addColumn("content", "string", ['limit' => self::HOMEWORK_CONTENT_LENGTH])
            ->addIndex("uid", ['unique' => true])
            ->create();
    }
}

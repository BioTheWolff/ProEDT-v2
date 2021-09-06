<?php
declare(strict_types=1);

use App\Database\LayeredAbstractMigration;

final class CreateOneTimeEventsTable extends LayeredAbstractMigration
{
    public function change(): void
    {
        $this->table("onetimeevents")
            ->addColumn("summary", "string", ['limit' => self::OTE_SUMMARY_LENGTH])
            ->addColumn("desc", "string", ['limit' => self::OTE_DESC_LENGTH, 'null' => true])
            ->addColumn("start", "datetime")
            ->addColumn("end", "datetime", ['null' => true])
            ->addColumn("isfullday", "boolean")
            ->create();
    }
}

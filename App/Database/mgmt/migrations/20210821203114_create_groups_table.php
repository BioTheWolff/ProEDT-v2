<?php
declare(strict_types=1);

use App\Database\LayeredAbstractMigration;

final class CreateGroupsTable extends LayeredAbstractMigration
{
    public function change(): void
    {
        $this->table("groups")
            ->addColumn("name", "string", ['limit' => self::GROUP_NAME_LENGTH])
            ->addColumn("school", "string", ['limit' => self::GROUP_NAME_LENGTH])
            ->addColumn("year", 'smallinteger')
            ->addColumn("url", "string", ['limit' => self::GROUP_URL_LENGTH, 'null' => true])
            ->addIndex("name", ['unique' => true])
            ->addForeignKey("school", "schools", "name")
            ->create();
    }
}

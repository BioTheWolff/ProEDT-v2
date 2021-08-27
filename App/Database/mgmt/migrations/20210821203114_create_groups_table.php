<?php
declare(strict_types=1);

use App\Database\LayeredAbstractMigration;

final class CreateGroupsTable extends LayeredAbstractMigration
{
    public function change(): void
    {
        $this->table("groups")
            ->addColumn("name", "string", ['limit' => self::GROUP_NAME_LENGTH])
            ->addColumn("major", "string", ['limit' => self::GROUP_NAME_LENGTH, 'null' => true])
            ->addColumn("parent", 'string', ['limit' => self::GROUP_NAME_LENGTH, 'null' => true])
            ->addColumn("url", "string", ['limit' => self::GROUP_URL_LENGTH, 'null' => true])
            ->addIndex("name", ['unique' => true])
            ->create();

        $this->table("groups")
            ->addForeignKey('parent', "groups", "name")
            ->update();
    }
}

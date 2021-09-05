<?php
declare(strict_types=1);

use App\Database\LayeredAbstractMigration;

final class CreateSchoolsTable extends LayeredAbstractMigration
{
    public function change(): void
    {
        $this->table("schools")
            ->addColumn("name", "string", ['limit' => self::GROUP_NAME_LENGTH])
            ->addColumn("url", "string", ['limit' => self::GROUP_URL_LENGTH, 'null' => true])
            ->addIndex("name", ['unique' => true])
            ->create();
    }
}

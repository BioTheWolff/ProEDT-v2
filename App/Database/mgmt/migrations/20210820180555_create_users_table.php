<?php
declare(strict_types=1);

use App\Database\LayeredAbstractMigration;

final class CreateUsersTable extends LayeredAbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table("users")
            ->addColumn("username", "string", ['limit' => self::USERNAME_LENGTH])
            ->addColumn("password", 'char', ['limit' => self::PASSWORD_LENGTH])
            ->addColumn("created_at", "datetime", ['default' => "CURRENT_TIMESTAMP"])
            ->addIndex("username", ['unique' => true])
            ->create()
        ;
    }
}

<?php

namespace App\Database;

use Phinx\Migration\AbstractMigration;

class LayeredAbstractMigration extends AbstractMigration
{

    const USERNAME_LENGTH = 60;
    const PASSWORD_LENGTH = 60;

}
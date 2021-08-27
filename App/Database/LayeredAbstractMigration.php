<?php

namespace App\Database;

use Phinx\Migration\AbstractMigration;

class LayeredAbstractMigration extends AbstractMigration
{

    const USERNAME_LENGTH = 60;
    const PASSWORD_LENGTH = 60;

    const GROUP_NAME_LENGTH = 5;
    const GROUP_URL_LENGTH = 300;

}
<?php

namespace App\Database;

use Phinx\Migration\AbstractMigration;

class LayeredAbstractMigration extends AbstractMigration
{

    const USERNAME_LENGTH = 60;
    const PASSWORD_LENGTH = 60;

    const GROUP_NAME_LENGTH = 5;
    const GROUP_URL_LENGTH = 300;

    const SCHOOL_FANCY_NAME_LENGTH = 50;

    const EVENT_UID_LENGTH = 100;
    const HOMEWORK_CONTENT_LENGTH = 300;

    const OTE_SUMMARY_LENGTH = 50;
    const OTE_DESC_LENGTH = 100;

}